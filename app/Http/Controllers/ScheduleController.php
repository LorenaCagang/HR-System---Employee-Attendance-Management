<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::orderBy('schedule_date')
            ->orderBy('schedule_time')
            ->get();

        return view('dashboard', compact('schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'schedule_date' => 'required|date',
            'schedule_time' => 'required',
        ]);

        Schedule::create([
            'title' => $request->title,
            'description' => $request->description,
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schedule_time,
        ]);

        return redirect()->back()->with('success', 'Schedule added successfully.');
    }

    // Add this method to handle deleting schedules
    public function destroy($id)
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found',
            ], 404);
        }

        $schedule->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
