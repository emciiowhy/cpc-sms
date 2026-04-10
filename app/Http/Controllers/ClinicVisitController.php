<?php

namespace App\Http\Controllers;

use App\Models\ClinicVisit;
use App\Http\Requests\StoreClinicVisitRequest;
use App\Http\Requests\UpdateClinicVisitRequest;
use Illuminate\Http\Request;

class ClinicVisitController extends Controller
{
    /**
     * Display a listing of the clinic visits with filters.
     */
    public function index()
    {
        $query = ClinicVisit::query();

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

        // Filter by Date
        if (request('date')) {
            $query->whereDate('visit_date', request('date'));
        }

        // Paginate results and keep filters in the URL
        $visits  = $query->latest()->paginate(10)->withQueryString();
        
        // Calculate counts for dashboard cards
        $today   = ClinicVisit::whereDate('visit_date', today())->count();
        $summary = [
            'total'      => ClinicVisit::count(),
            'today'      => $today,
            'treated'    => ClinicVisit::where('status', 'treated')->count(),
            'referred'   => ClinicVisit::where('status', 'referred')->count(),
        ];

        return view('clinic.index', compact('visits', 'summary'));
    }

    /**
     * Store a newly created clinic visit.
     */
    public function store(StoreClinicVisitRequest $request)
    {
        ClinicVisit::create([
            ...$request->validated(),
            'created_by' => auth()->id(), // Automatically track which user recorded the visit
        ]);

        return redirect()->route('clinic.index')
            ->with('success', 'Visit recorded successfully.');
    }

    /**
     * Update the specified clinic visit.
     */
    public function update(UpdateClinicVisitRequest $request, ClinicVisit $clinicVisit)
    {
        $clinicVisit->update($request->validated());

        return redirect()->route('clinic.index')
            ->with('success', 'Visit updated successfully.');
    }

    /**
     * Remove the specified clinic visit.
     */
    public function destroy(ClinicVisit $clinicVisit)
    {
        $clinicVisit->delete();

        return redirect()->route('clinic.index')
            ->with('success', 'Visit deleted.');
    }

    // Methods typically handled via Modals in the index view
    public function create() { }
    public function show(ClinicVisit $clinicVisit) { }
    public function edit(ClinicVisit $clinicVisit) { }
}