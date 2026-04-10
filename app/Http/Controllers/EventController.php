<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the events with search and filters.
     */
    public function index()
    {
        // Eager load the 'creator' relationship for better performance
        $query = Event::with('creator');

        // Search by title
        if (request('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }

        // Filter by status (upcoming, ongoing, done)
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filter by specific date
        if (request('date')) {
            $query->whereDate('start_date', request('date'));
        }

        // Paginate and keep the search/filter parameters in the links
        $events = $query->latest()->paginate(10)->withQueryString();
        
        // Total count for stat cards
        $total = Event::count();

        return view('events.index', compact('events', 'total'));
    }

    /**
     * Store a newly created event.
     */
    public function store(StoreEventRequest $request)
    {
        Event::create([
            ...$request->validated(),
            'created_by' => auth()->id(), // Automatically assign the logged-in user
        ]);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Update the specified event.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->validated());

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }
}