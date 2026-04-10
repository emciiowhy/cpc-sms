<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments with filters and summary stats.
     */
    public function index()
    {
        $query = Enrollment::query();

        // Search by Student Name or Student ID
        if (request('search')) {
            $query->where(function($q) {
                $q->where('student_name', 'like', '%' . request('search') . '%')
                  ->orWhere('student_id', 'like', '%' . request('search') . '%');
            });
        }

        // Filter by Status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filter by Year Level
        if (request('year_level')) {
            $query->where('year_level', request('year_level'));
        }

        // Filter by Course
        if (request('course')) {
            $query->where('course', request('course'));
        }

        // Pagination and Data Fetching with Query String persistence
        $enrollments = $query->latest()->paginate(10)->withQueryString();
        
        // Get unique courses for the filter dropdown
        $courses = Enrollment::distinct()->pluck('course');

        // Statistics for the dashboard stat cards
        $summary = [
            'total'    => Enrollment::count(),
            'pending'  => Enrollment::where('status', 'pending')->count(),
            'approved' => Enrollment::where('status', 'approved')->count(),
            'rejected' => Enrollment::where('status', 'rejected')->count(),
        ];

        return view('enrollments.index', compact('enrollments', 'courses', 'summary'));
    }

    /**
     * Store a newly created enrollment.
     */
    public function store(StoreEnrollmentRequest $request)
    {
        Enrollment::create([
            ...$request->validated(),
            'created_by' => auth()->id(), // Automatically assign the logged-in user
        ]);

        return redirect()->route('enrollment.index')
            ->with('success', 'Enrollment created successfully.');
    }

    /**
     * Update the specified enrollment.
     */
    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment)
    {
        $enrollment->update($request->validated());

        return redirect()->route('enrollment.index')
            ->with('success', 'Enrollment updated successfully.');
    }

    /**
     * Remove the specified enrollment from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return redirect()->route('enrollment.index')
            ->with('success', 'Enrollment deleted successfully.');
    }
}