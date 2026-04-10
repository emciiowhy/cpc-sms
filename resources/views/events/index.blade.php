<x-app-layout>
    <x-slot name="header">Event Management</x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Total Events</p>
            <p class="text-3xl font-bold text-indigo-700">{{ $total }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Upcoming</p>
            <p class="text-3xl font-bold text-blue-600">{{ $events->where('status','upcoming')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Ongoing</p>
            <p class="text-3xl font-bold text-green-600">{{ $events->where('status','ongoing')->count() }}</p>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100">
            <form method="GET" class="flex flex-col sm:flex-row gap-2 flex-wrap w-full sm:w-auto min-w-0">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search events..."
                    class="w-full sm:w-auto min-w-0 flex-1 sm:flex-none border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <select name="status" class="w-full sm:w-auto border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">All Status</option>
                    <option value="upcoming"  {{ request('status') === 'upcoming'  ? 'selected' : '' }}>Upcoming</option>
                    <option value="ongoing"   {{ request('status') === 'ongoing'   ? 'selected' : '' }}>Ongoing</option>
                    <option value="done"      {{ request('status') === 'done'      ? 'selected' : '' }}>Done</option>
                </select>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full sm:w-auto border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Filter</button>
                @if(request()->hasAny(['search','status','date']))
                    <a href="{{ route('events.index') }}" class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Clear</a>
                @endif
            </form>

            <button type="button" onclick="document.getElementById('createModal').classList.remove('hidden')"
                class="w-full sm:w-auto bg-indigo-600 text-white px-4 py-3 sm:py-2 rounded-lg text-sm hover:bg-indigo-700 whitespace-nowrap touch-manipulation">
                + New Event
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto scroll-touch">
            <table class="w-full text-sm min-w-[720px]">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-widest">
                    <tr>
                        <th class="px-6 py-3 text-left">Title</th>
                        <th class="px-6 py-3 text-left">Location</th>
                        <th class="px-6 py-3 text-left">Start Date</th>
                        <th class="px-6 py-3 text-left">End Date</th>
                        <th class="px-6 py-3 text-left">Participants</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($events as $event)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $event->title }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $event->location ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $event->start_date->format('M d, Y h:i A') }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $event->end_date->format('M d, Y h:i A') }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ number_format($event->participant_count) }}</td>
                        <td class="px-6 py-4">
                            @php
                                $badge = match($event->status) {
                                    'upcoming' => 'bg-blue-100 text-blue-700',
                                    'ongoing'  => 'bg-green-100 text-green-700',
                                    'done'     => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badge }} capitalize">
                                {{ $event->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <button onclick='openEdit(@json($event))'
                                class="text-indigo-600 hover:underline text-xs font-medium">Edit</button>
                            <form method="POST" action="{{ route('events.destroy', $event) }}"
                                onsubmit="return confirm('Delete this event?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">No events found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($events->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $events->links() }}
        </div>
        @endif
    </div>

    {{-- CREATE MODAL --}}
    <div id="createModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">Create New Event</h2>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" action="{{ route('events.store') }}" class="px-6 py-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Title *</label>
                    <input type="text" name="title" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Location</label>
                    <input type="text" name="location" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Start Date *</label>
                        <input type="datetime-local" name="start_date" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">End Date *</label>
                        <input type="datetime-local" name="end_date" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Status *</label>
                        <select name="status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Participant Count</label>
                        <input type="number" name="participant_count" min="0" value="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Create Event</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">Edit Event</h2>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" id="editForm" class="px-6 py-4 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Title *</label>
                    <input type="text" name="title" id="edit_title" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Description</label>
                    <textarea name="description" id="edit_description" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Location</label>
                    <input type="text" name="location" id="edit_location" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Start Date *</label>
                        <input type="datetime-local" name="start_date" id="edit_start_date" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">End Date *</label>
                        <input type="datetime-local" name="end_date" id="edit_end_date" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Status *</label>
                        <select name="status" id="edit_status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Participant Count</label>
                        <input type="number" name="participant_count" id="edit_participant_count" min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Update Event</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEdit(event) {
            // Helper function to format date for datetime-local input
            const formatDate = (dateString) => {
                if (!dateString) return '';
                const d = new Date(dateString);
                // Adjusts for local timezone to ensure the input shows the correct time
                const pad = (num) => String(num).padStart(2, '0');
                return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
            };

            // Set values in the modal
            document.getElementById('edit_title').value = event.title;
            document.getElementById('edit_description').value = event.description ?? '';
            document.getElementById('edit_location').value = event.location ?? '';
            document.getElementById('edit_start_date').value = formatDate(event.start_date);
            document.getElementById('edit_end_date').value = formatDate(event.end_date);
            document.getElementById('edit_status').value = event.status;
            document.getElementById('edit_participant_count').value = event.participant_count;

            // Set form action URL
            document.getElementById('editForm').action = '/events/' + event.id;

            // Show the modal
            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>