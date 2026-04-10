<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController; 
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CounselingController;
use App\Http\Controllers\ClinicVisitController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\MedicalRecordController;

use App\Models\Enrollment;
use App\Models\Event;
use App\Models\Appointment;
use App\Models\ClinicVisit;
use Illuminate\Support\Facades\Route;

// UPDATED ROOT ROUTE
Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard') 
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    $stats = [
        'students'     => Enrollment::count(),
        'events'       => Event::count(),
        'appointments' => Appointment::where('status', 'pending')->count(),
        'clinic_today' => ClinicVisit::whereDate('visit_date', today())->count(),
    ];
    return view('dashboard', compact('stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

// 2. Event & Organization Routes (Admin & SAO)
Route::middleware(['auth', 'role:admin,sao'])->group(function () {
    Route::resource('events', EventController::class);
    Route::resource('organizations', OrganizationController::class);
    
    Route::post('organizations/{organization}/members', [OrganizationController::class, 'addMember'])
        ->name('organizations.members.add');
    Route::delete('organizations/{organization}/members/{member}', [OrganizationController::class, 'removeMember'])
        ->name('organizations.members.remove');
});

// 3. Enrollment & Grading Routes (Admin & Registrar)
Route::middleware(['auth', 'role:admin,registrar'])->group(function () {
    Route::resource('enrollment', EnrollmentController::class);
    Route::resource('grades', GradeController::class);
});

// 4. Guidance Routes (Admin & Guidance)
Route::middleware(['auth', 'role:admin,guidance'])->group(function () {
    Route::resource('appointments', AppointmentController::class);
    Route::resource('counseling', CounselingController::class);
});

// 5. Clinic Routes (Admin & Clinic)
Route::middleware(['auth', 'role:admin,clinic'])->group(function () {
    Route::resource('clinic', ClinicVisitController::class);
    Route::resource('medical-records', MedicalRecordController::class);
});

// 6. Announcement Routes (All Authenticated Users)
Route::middleware(['auth'])->group(function () {
    Route::resource('announcements', AnnouncementController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';