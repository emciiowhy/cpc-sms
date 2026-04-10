<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Requests\UpdateMedicalRecordRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of medical records with search and status filters.
     */
    public function index()
    {
        $query = MedicalRecord::query();

        // Search by name or student ID
        if (request('search')) {
            $query->where(function($q) {
                $q->where('student_name', 'like', '%' . request('search') . '%')
                  ->orWhere('student_id', 'like', '%' . request('search') . '%');
            });
        }

        // Filter by "Fit" or "Not Fit" status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        $records = $query->latest()->paginate(10)->withQueryString();

        // Dashboard Summary Stats
        $summary = [
            'total'   => MedicalRecord::count(),
            'fit'     => MedicalRecord::where('status', 'fit')->count(),
            'not_fit' => MedicalRecord::where('status', 'not_fit')->count(),
        ];

        return view('medical_records.index', compact('records', 'summary'));
    }

    /**
     * Store a newly created medical record with file upload.
     */
    public function store(StoreMedicalRecordRequest $request)
    {
        $data = $request->validated();

        // Handle File Upload
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('medical_attachments', 'public');
        }

        MedicalRecord::create([
            ...$data,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('medical-records.index')
            ->with('success', 'Medical record created successfully.');
    }

    /**
     * Update the specified medical record and replace attachment if needed.
     */
    public function update(UpdateMedicalRecordRequest $request, MedicalRecord $medicalRecord)
    {
        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            // Delete the old file from storage if a new one is uploaded
            if ($medicalRecord->attachment) {
                Storage::disk('public')->delete($medicalRecord->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('medical_attachments', 'public');
        }

        $medicalRecord->update($data);

        return redirect()->route('medical-records.index')
            ->with('success', 'Medical record updated successfully.');
    }

    /**
     * Remove the record and its associated file.
     */
    public function destroy(MedicalRecord $medicalRecord)
    {
        // Clean up storage before deleting the database entry
        if ($medicalRecord->attachment) {
            Storage::disk('public')->delete($medicalRecord->attachment);
        }

        $medicalRecord->delete();

        return redirect()->route('medical-records.index')
            ->with('success', 'Record deleted.');
    }

    // Standard resource methods usually handled via modals in your UI
    public function create() { }
    public function show(MedicalRecord $medicalRecord) { }
    public function edit(MedicalRecord $medicalRecord) { }
}