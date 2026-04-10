<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of grades with search and filters.
     */
    public function index()
    {
        $query = Grade::query();

        // Search by Student Name or Student ID
        if (request('search')) {
            $query->where(function($q) {
                $q->where('student_name', 'like', '%' . request('search') . '%')
                  ->orWhere('student_id', 'like', '%' . request('search') . '%');
            });
        }

        // Filter by Subject
        if (request('subject')) {
            $query->where('subject', request('subject'));
        }

        // Filter by Passing/Failing Status (Remarks)
        if (request('remarks')) {
            $query->where('remarks', request('remarks'));
        }

        // Paginate results and maintain filter parameters in URL links
        $grades   = $query->latest()->paginate(10)->withQueryString();
        
        // Get unique subjects for the filter dropdown
        $subjects = Grade::distinct()->pluck('subject');

        // Statistics for the top cards
        $summary  = [
            'total'   => Grade::count(),
            'passing' => Grade::where('remarks', 'pass')->count(),
            'failing' => Grade::where('remarks', 'fail')->count(),
        ];

        return view('grades.index', compact('grades', 'subjects', 'summary'));
    }

    /**
     * Store a newly created grade entry.
     */
    public function store(StoreGradeRequest $request)
    {
        Grade::create([
            ...$request->validated(),
            'created_by' => auth()->id(), // Automatically assign the logged-in user
        ]);

        return redirect()->route('grades.index')
            ->with('success', 'Grade recorded successfully.');
    }

    /**
     * Update the specified grade entry.
     */
    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        $grade->update($request->validated());

        return redirect()->route('grades.index')
            ->with('success', 'Grade updated successfully.');
    }

    /**
     * Remove the specified grade entry.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('grades.index')
            ->with('success', 'Grade deleted successfully.');
    }
}