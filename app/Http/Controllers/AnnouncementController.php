<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements with filters and summary stats.
     */
    public function index()
    {
        // Eager load the creator relationship to optimize performance
        $query = Announcement::with('creator');

        // Search by title
        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }

        // Filter by category (events, news, reminders)
        if (request('category')) {
            $query->where('category', request('category'));
        }

        // Paginate results and maintain URL parameters
        $announcements = $query->latest()->paginate(10)->withQueryString();
        
        // Fetch the single most recent announcement for a "Featured" or "Latest" UI section
        $latest = Announcement::latest()->first();

        // Statistics for dashboard cards
        $summary = [
            'total'     => Announcement::count(),
            'events'    => Announcement::where('category', 'events')->count(),
            'news'      => Announcement::where('category', 'news')->count(),
            'reminders' => Announcement::where('category', 'reminders')->count(),
        ];

        return view('announcements.index', compact('announcements', 'latest', 'summary'));
    }

    /**
     * Store a newly created announcement.
     */
    public function store(StoreAnnouncementRequest $request)
    {
        Announcement::create([
            ...$request->validated(),
            // Ensure is_featured is treated as a boolean even if sent from a checkbox
            'is_featured' => $request->has('is_featured'),
            'created_by'  => auth()->id(),
        ]);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement posted successfully.');
    }

    /**
     * Update the specified announcement.
     */
    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        $announcement->update([
            ...$request->validated(),
            // Re-evaluate the featured status during update
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted.');
    }

    // Modal-driven views usually don't require these methods
    public function create() { }
    public function show(Announcement $announcement) { }
    public function edit(Announcement $announcement) { }
}