<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Auth;


// Dashboard route (only one, no conflict)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Schedule store route
Route::post('/schedule/store', [ScheduleController::class, 'store'])->name('schedule.store');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
Route::get('/get-all-employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/find-employee/{id}', [EmployeeController::class, 'search'])->name('employees.search');
Route::get('/employees/add', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');

Route::patch('/employees/{employee}/toggle', [EmployeeController::class, 'toggle'])->name('employees.toggle');
Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
Route::resource('employees', EmployeeController::class);
Route::patch('/employees/{employee}/toggle', [EmployeeController::class, 'toggle'])->name('employees.toggle');
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::patch('/employees/{employee}/toggle', [EmployeeController::class, 'toggle'])->name('employees.toggle');
Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');

//Department Routes
Route::resource('departments', DepartmentController::class);
Route::patch('departments/{department}/toggle', [DepartmentController::class, 'toggle'])->name('departments.toggle');
Route::get('/get-all-departments', [DepartmentController::class, 'index'])->name('departments.get-all');

//Sidebar Routes
Route::resource('departments', DepartmentController::class)->only(['index', 'store', 'show', 'update']);
Route::patch('departments/{department}/toggle', [DepartmentController::class, 'toggle'])->name('departments.toggle');
Route::resource('employees', EmployeeController::class)->only(['index']); // Minimal for Employee module



Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
