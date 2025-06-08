<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Schedule; // Add this to import your Schedule model
use App\Models\Department; // Add this to import your Schedule model

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = $request->input('filter_date', now()->format('Y-m-d'));
        $today_date = \Carbon\Carbon::parse($today);
          $start_of_month = $today_date->copy()->startOfMonth();
        $cutoff_time = '08:30:00';

        // Accept year query parameter, default to current year
        $selectedYear = $request->input('year', now()->year);

        // Existing queries...

        $employee_count = DB::table('employees')->count();

        $senior_total = DB::table('employees')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birthday, CURDATE()) >= 60')
            ->count();

        $senior_present = DB::table('employees as e')
            ->join('timelogs as t', 'e.enroll_id', '=', 't.enroll_id')
            ->whereRaw('TIMESTAMPDIFF(YEAR, e.birthday, CURDATE()) >= 60')
            ->whereDate('t.timelogs', $today)
            ->distinct('e.id')
            ->count('e.id');

      
        $head_count = DB::table('departments')
        ->whereNotNull('dept_head_id')
        ->distinct()
        ->count('dept_head_id');

        $head_present = DB::table('departments as d')
        ->join('employees as e', 'd.dept_head_id', '=', 'e.id') 
        ->join('timelogs as t', 'e.enroll_id', '=', 't.enroll_id') 
        ->whereDate('t.timelogs', $today) 
        ->distinct()
        ->count('e.id'); 
        
        $dept_head_ids = DB::table('departments')
            ->whereNotNull('dept_head_id')
            ->pluck('dept_head_id');

        $colleagues_present = DB::table('employees as e')
            ->join('timelogs as t', 'e.enroll_id', '=', 't.enroll_id')
            ->whereDate('t.timelogs', $today)
            ->whereNotIn('e.id', $dept_head_ids)
            ->distinct('e.id')
            ->count('e.id');
        
            $present_count = DB::table('employees as e')
            ->join('timelogs as t', 'e.enroll_id', '=', 't.enroll_id')
            ->whereDate('t.timelogs', $today)
            ->distinct('e.id')
            ->count('e.id');
        

        $absent_count = $employee_count - $present_count;

        $male_total = DB::table('employees as e')
            ->join('timelogs as t', 'e.enroll_id', '=', 't.enroll_id')
            ->where('e.gender', 1)
            ->whereDate('t.timelogs', $today)
            ->distinct('e.id')
            ->count('e.id');

        $female_total = DB::table('employees as e')
            ->join('timelogs as t', 'e.enroll_id', '=', 't.enroll_id')
            ->where('e.gender', 0)
            ->whereDate('t.timelogs', $today)
            ->distinct('e.id')
            ->count('e.id');

        $total_gender = $male_total + $female_total;
        $male_percent = $total_gender > 0 ? round(($male_total / $total_gender) * 100) : 0;
        $female_percent = 100 - $male_percent;

        


        $selectedDepartmentId = $request->input('department_id');
        $search = $request->input('search');
        
        $attendance_query = DB::table('employees as e')
            ->leftJoin('departments as d', 'e.department_id', '=', 'd.id')
            ->leftJoin('timelogs as t', function ($join) use ($today) {
                $join->on('e.enroll_id', '=', 't.enroll_id')
                    ->whereDate('t.timelogs', '=', $today);
            })
            ->when($selectedDepartmentId, function ($query) use ($selectedDepartmentId) {
                $query->where('e.department_id', $selectedDepartmentId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('e.firstname', 'like', "%{$search}%")
                    ->orWhere('e.middlename', 'like', "%{$search}%")
                    ->orWhere('e.lastname', 'like', "%{$search}%")
                    ->orWhere('e.suffix', 'like', "%{$search}%");
                });
            })
            ->select(
                'e.id',
                DB::raw("CONCAT(e.firstname, ' ', e.lastname) AS name"),
                'e.photo',
                'e.enroll_id',
                DB::raw('MIN(t.timelogs) AS time_in'),
                'd.name as department_name'
            )
            ->groupBy('e.id', 'e.firstname', 'e.lastname', 'e.photo', 'e.enroll_id', 'd.name')
            ->paginate(4);

        


            $employee_rows = [];
            foreach ($attendance_query->items() as $row) {
            
            $log_time = $row->time_in ? date('H:i:s', strtotime($row->time_in)) : null;

            if (!$log_time) {
                $status = 'Absent';
                $remarks = '—';
                $time_in_display = '—';
            } else {
                $status = (strtotime($log_time) > strtotime($cutoff_time)) ? 'Late' : 'On Time';
                $remarks = ($status === 'Late') ? 'No Remarks' : '—';
                $time_in_display = date('h:i A', strtotime($log_time));
            }

           $today_date = \Carbon\Carbon::parse($today);
$start_of_month = $today_date->copy()->startOfMonth();

            $working_days = [];
            $period = new \DatePeriod($start_of_month, new \DateInterval('P1D'), $today_date->copy()->addDay());

            foreach ($period as $date) {
                if (!in_array($date->format('N'), [6, 7])) { 
                    $working_days[] = $date->format('Y-m-d');
                }
            }

            $log_dates = DB::table('timelogs')
                ->where('enroll_id', $row->enroll_id)
                ->whereBetween('timelogs', [$start_of_month->format('Y-m-d'), $today_date->format('Y-m-d')])
                ->selectRaw('DISTINCT DATE(timelogs) as log_date')
                ->pluck('log_date')
                ->toArray();

            $present_days = count($log_dates);
            $absent_days = count(array_diff($working_days, $log_dates));

            $employee_rows[] = [
                'id' => $row->id,
                'name' => $row->name,
                'photo' => $row->photo ?: 'default.jpg',
                'date' => $today,
                'time_in' => $time_in_display,
                'status' => $status,
                'remarks' => $remarks,
                'present_days' => $present_days,
                'absent_days' => $absent_days,
            ];
        }

        $timeliness = DB::table('timelogs as t')
            ->selectRaw("
                MONTH(t.timelogs) as month,
                t.enroll_id,
                MIN(t.timelogs) as first_log,
                CASE 
                    WHEN TIME(MIN(t.timelogs)) <= ? THEN 'On Time'
                    ELSE 'Late'
                END as status
            ", [$cutoff_time])
            ->whereYear('t.timelogs', $selectedYear)
            ->groupBy('month', 't.enroll_id')
            ->orderBy('month')
            ->get();

        $monthly_on_time = array_fill(1, 12, 0);
        $monthly_late = array_fill(1, 12, 0);

        foreach ($timeliness as $row) {
            $month = (int) $row->month;
            $status = $row->status;
            if ($status === 'On Time') {
                $monthly_on_time[$month]++;
            } else {
                $monthly_late[$month]++;
            }
        }

        $monthly_on_time_values = array_values($monthly_on_time);
        $monthly_late_values = array_values($monthly_late);

        $schedules = Schedule::orderBy('schedule_date')->orderBy('schedule_time')->get();
        $departments = Department::orderBy('name')->get();

   return view('dashboard', compact(
    'employee_count',
    'senior_total',
    'senior_present',
    'head_count',
    'head_present', 
    'present_count',
    'absent_count',
    'male_percent',
    'female_percent',
    'colleagues_present', 
    'total_gender',
    'employee_rows',
    'monthly_on_time_values',
    'monthly_late_values',
    'attendance_query',

    'selectedYear',
    'schedules',
    'departments'
));

        
        
    }

    
}
