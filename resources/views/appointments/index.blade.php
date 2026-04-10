<x-app-layout>
    <x-slot name="header">Appointment System</x-slot>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Total</p>
            <p class="text-3xl font-bold text-indigo-700">{{ $summary['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Pending</p>
            <p class="text-3xl font-bold text-yellow-500">{{ $summary['pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Approved</p>
            <p class="text-3xl font-bold text-green-600">{{ $summary['approved'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Done</p>
            <p class="text-3xl font-bold text-gray-500">{{ $summary['done'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100">
            <form method="GET" class="flex gap-2 flex-wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search student..."
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">All Status</option>
                    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="done"     {{ request('status') === 'done'     ? 'selected' : '' }}>Done</option>
                </select>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Filter</button>
                @if(request()->hasAny(['search','status','date']))
                    <a href="{{ route('appointments.index') }}" class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Clear</a>
                @endif
            </form>
            <button onclick="document.getElementById('createModal').classList.remove('hidden')"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 whitespace-nowrap">
                + New Appointment
            </button>
        </div>

        <div class="overflow-x-auto scroll-touch">
            <table class="w-full text-sm min-w-[640px]">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-widest">
                    <tr>
                        <th class="px-6 py-3 text-left">Student ID</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Purpose</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Time</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($appointments as $appt)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $appt->student_id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $appt->student_name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $appt->purpose }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $appt->appointment_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $badge = match($appt->status) {
                                    'pending'  => 'bg-yellow-100 text-yellow-700',
                                    'approved' => 'bg-green-100 text-green-700',
                                    'done'     => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badge }} capitalize">
                                {{ $appt->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <button onclick='openEdit(@json($appt))'
                                class="text-indigo-600 hover:underline text-xs font-medium">Edit</button>
                            <form method="POST" action="{{ route('appointments.destroy', $appt) }}"
                                onsubmit="return confirm('Delete this appointment?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">No appointments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appointments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $appointments->links() }}
        </div>
        @endif
    </div>

    {{-- CREATE MODAL --}}
    <div id="createModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">New Appointment</h2>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" action="{{ route('appointments.store') }}" class="px-6 py-4 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Student ID *</label>
                        <input type="text" name="student_id" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Student Name *</label>
                        <input type="text" name="student_name" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Purpose *</label>
                    <input type="text" name="purpose" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Date *</label>
                        <input type="date" name="appointment_date" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Time *</label>
                        <input type="time" name="appointment_time" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Status *</label>
                    <select name="status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="done">Done</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">Edit Appointment</h2>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" id="editForm" class="px-6 py-4 space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Student ID *</label>
                        <input type="text" name="student_id" id="edit_student_id" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Student Name *</label>
                        <input type="text" name="student_name" id="edit_student_name" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Purpose *</label>
                    <input type="text" name="purpose" id="edit_purpose" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Date *</label>
                        <input type="date" name="appointment_date" id="edit_date" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Time *</label>
                        <input type="time" name="appointment_time" id="edit_time" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Status *</label>
                    <select name="status" id="edit_status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="done">Done</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Notes</label>
                    <textarea name="notes" id="edit_notes" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEdit(a) {
        document.getElementById('edit_student_id').value   = a.student_id;
        document.getElementById('edit_student_name').value = a.student_name;
        document.getElementById('edit_purpose').value      = a.purpose;
        document.getElementById('edit_date').value         = a.appointment_date;
        document.getElementById('edit_time').value         = a.appointment_time;
        document.getElementById('edit_status').value       = a.status;
        document.getElementById('edit_notes').value        = a.notes ?? '';
        document.getElementById('editForm').action         = '/appointments/' + a.id;
        document.getElementById('editModal').classList.remove('hidden');
    }
    </script>

</x-app-layout>