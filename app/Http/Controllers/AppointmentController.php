<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource with search and filters.
     */
    public function index()
    {
        $query = Appointment::query();

        // Filter by Search (Student Name or ID)
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
            $query->whereDate('appointment_date', request('date'));
        }

        // Fetch paginated results and keep filter parameters in the URL links
        $appointments = $query->latest()->paginate(10)->withQueryString();

        // Summary Data for Dashboard Stats Cards
        $summary = [
            'total'    => Appointment::count(),
            'pending'  => Appointment::where('status', 'pending')->count(),
            'approved' => Appointment::where('status', 'approved')->count(),
            'done'     => Appointment::where('status', 'done')->count(),
        ];

        return view('appointments.index', compact('appointments', 'summary'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'       => 'required|string',
            'student_name'     => 'required|string',
            'purpose'          => 'required|string',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status'           => 'required|in:pending,approved,done',
            'notes'            => 'nullable|string',
        ]);
    
        // Automatically assign the logged-in user as the creator
        $validated['created_by'] = auth()->id();
    
        Appointment::create($validated);
    
        return redirect()->back()->with('success', 'Appointment created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'student_id'       => 'required|string',
            'student_name'     => 'required|string',
            'purpose'          => 'required|string',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status'           => 'required|in:pending,approved,done',
            'notes'            => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->back()->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->back()->with('success', 'Appointment deleted.');
    }

    // Methods not needed when using Modals within the Index view
    public function create() { }
    public function show(string $id) { }
    public function edit(string $id) { }
}