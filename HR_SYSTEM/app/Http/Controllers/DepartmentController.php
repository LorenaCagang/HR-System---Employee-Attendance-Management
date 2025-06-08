<?php
namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with(['departmentHead', 'employees'])->get();
        $employees = Employee::all();
        return view('departments.index', compact('departments', 'employees'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:150',
                'description' => 'nullable|string', // Add description validation
                'dept_head_id' => 'nullable|exists:employees,id',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'required|boolean',
            ]);

            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('departments', 'public');
                $validated['profile_picture'] = $path;
            } else {
                $validated['profile_picture'] = null;
            }

            $validated['created_by'] = Auth::id() ?? 1;
            Department::create($validated);

            return response()->json(['message' => 'Department created successfully'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Store Department Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $department = Department::with(['departmentHead', 'employees'])->findOrFail($id);
            return response()->json($department);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Department not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $department = Department::findOrFail($id);
            $validated = $request->validate([
                'name' => 'required|string|max:150',
                'description' => 'nullable|string', // Add description validation
                'dept_head_id' => 'nullable|exists:employees,id',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'required|boolean',
            ]);

            if ($request->hasFile('profile_picture')) {
                if ($department->profile_picture) {
                    Storage::disk('public')->delete($department->profile_picture);
                }
                $path = $request->file('profile_picture')->store('departments', 'public');
                $validated['profile_picture'] = $path;
            } else {
                $validated['profile_picture'] = $department->profile_picture;
            }

            $validated['updated_by'] = Auth::id() ?? 1;
            $department->update($validated);

            return response()->json(['message' => 'Department updated successfully'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Update Department Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function toggle(Request $request, $id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->is_active = !$department->is_active;
            $department->updated_by = Auth::id() ?? 1;
            $department->save();

            return redirect()->back()->with('success', 'Department status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update department status.');
        }
    }
}