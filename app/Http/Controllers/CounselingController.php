<?php

namespace App\Http\Controllers;

use App\Models\CounselingRecord;
use App\Http\Requests\StoreCounselingRequest;
use App\Http\Requests\UpdateCounselingRequest;

class CounselingController extends Controller
{
    public function index()
    {
        $query = CounselingRecord::query();

        if (request('search')) {
            $query->where(function($q) {
                $q->where('student_name', 'like', '%' . request('search') . '%')
                  ->orWhere('student_id', 'like', '%' . request('search') . '%');
            });
        }

        if (request('category')) {
            $query->where('category', request('category'));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $records = $query->latest()->paginate(10)->withQueryString();
        $summary = [
            'total'    => CounselingRecord::count(),
            'open'     => CounselingRecord::where('status', 'open')->count(),
            'ongoing'  => CounselingRecord::where('status', 'ongoing')->count(),
            'resolved' => CounselingRecord::where('status', 'resolved')->count(),
        ];

        return view('counseling.index', compact('records', 'summary'));
    }

    public function store(StoreCounselingRequest $request)
    {
        CounselingRecord::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('counseling.index')
            ->with('success', 'Counseling record created successfully.');
    }

    public function update(UpdateCounselingRequest $request, CounselingRecord $counseling)
    {
        $counseling->update($request->validated());

        return redirect()->route('counseling.index')
            ->with('success', 'Record updated successfully.');
    }

    public function destroy(CounselingRecord $counseling)
    {
        $counseling->delete();

        return redirect()->route('counseling.index')
            ->with('success', 'Record deleted.');
    }
}