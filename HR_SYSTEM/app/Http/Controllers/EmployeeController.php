<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\TimeLog;
class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        $selectedDate = $request->input('date') ?? Carbon::now()->format('Y-m-d');
        $search = $request->input('search');
        $departmentName = $request->input('department');

        // Get base query with department relationship
        $employeeQuery = Employee::with('department');

        // Search filter
        if (!empty($search)) {
            $employeeQuery->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%$search%")
                    ->orWhere('middlename', 'like', "%$search%")
                    ->orWhere('suffix', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // Department filter
        if (!empty($departmentName)) {
            $employeeQuery->whereHas('department', function ($q) use ($departmentName) {
                $q->where('name', $departmentName);
            });
        }

        $employees = $employeeQuery->get();

        // Get time in per employee based on selected date
        $timeIns = DB::table('timelogs')
            ->select('enroll_id', DB::raw('MIN(timelogs) as time_in'))
            ->whereDate('timelogs', $selectedDate)
            ->groupBy('enroll_id')
            ->pluck('time_in', 'enroll_id');

        // Attach time in and status to each employee
        foreach ($employees as $employee) {
            $timeIn = $timeIns[$employee->enroll_id] ?? null;
            $employee->time_in = $timeIn;

            if ($timeIn) {
                $parsedTime = Carbon::parse($timeIn);
                $employee->status = $parsedTime->lte(Carbon::parse('08:30:00')) ? 'On Time' : 'Late';
            } else {
                $employee->status = 'Absent';
            }
        }

        $departments = Department::all();

        return view('employees.index', compact('employees', 'departments', 'selectedDate'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'lastname' => 'required|string',
            'suffix' => 'nullable|string',
            'email' => 'required|email|unique:employees,email',
            'contact_number' => 'required|unique:employees,contact_number',
            'department_id' => 'required|exists:departments,id',
            'gender' => 'required|in:1,0',
            'birthday' => 'required|date',
            'position' => 'required|string',
            'hire_date' => 'required|date',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
        ]);


        $photoPath = null;
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photoPath = $request->file('photo')->store('employees', 'public');
        }

        $latestEnrollId = Employee::max('enroll_id') ?? 2009;
        $newEnrollId = $latestEnrollId + 1;

        Employee::create([
            'enroll_id' => $newEnrollId,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'suffix' => $request->suffix,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'department_id' => $request->department_id,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'position' => $request->position,
            'hire_date' => $request->hire_date,
            'photo' => $photoPath,
            'is_active' => $request->is_active,
            'remarks' => $request->remarks,
            'created_by' => 1,
        ]);

        return response()->json(['success' => true]);
    }

    public function toggle($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->is_active = !$employee->is_active;
        $employee->save();

        return redirect()->back()->with('success', 'Status successfully updated!');
    }


    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'lastname' => 'required|string',
            'suffix' => 'nullable|string',
            'email' => 'required|email|unique:employees,email,' . $id,
            'contact_number' => 'required|unique:employees,contact_number,' . $id,
            'department_id' => 'nullable|exists:departments,id',
            'gender' => 'required|in:1,0',
            'birthday' => 'required|date',
            'position' => 'required|string',
            'hire_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remarks' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);


        $employee = Employee::findOrFail($id);

        // Handle photo upload if exists
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $employee->photo = $request->file('photo')->store('employees', 'public');
        }

        $employee->firstname = $request->firstname;
        $employee->middlename = $request->middlename;
        $employee->lastname = $request->lastname;
        $employee->suffix = $request->suffix;
        $employee->email = $request->email;
        $employee->contact_number = $request->contact_number;
        $employee->department_id = $request->department_id;

        $employee->gender = $request->gender;

        $employee->birthday = $request->birthday;
        $employee->position = $request->position;
        $employee->hire_date = $request->hire_date;
        $employee->remarks = $request->remarks;
        $employee->is_active = $request->is_active;

        $employee->save();

        return response()->json(['success' => true]);
    }
    public function show($id)
    {
        $employee = Employee::with('department')->findOrFail($id);

        if (request()->ajax()) {
            return response()->json($employee);
        }

        return view('employees.show', compact('employee'));
    }




}