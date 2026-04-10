<x-app-layout>
    <x-slot name="header">Counseling System</x-slot>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Total Cases</p>
            <p class="text-3xl font-bold text-indigo-700">{{ $summary['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Open</p>
            <p class="text-3xl font-bold text-red-500">{{ $summary['open'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Ongoing</p>
            <p class="text-3xl font-bold text-yellow-500">{{ $summary['ongoing'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Resolved</p>
            <p class="text-3xl font-bold text-green-600">{{ $summary['resolved'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100">
            <form method="GET" class="flex gap-2 flex-wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search student..."
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <select name="category" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">All Categories</option>
                    <option value="academic"   {{ request('category') === 'academic'   ? 'selected' : '' }}>Academic</option>
                    <option value="personal"   {{ request('category') === 'personal'   ? 'selected' : '' }}>Personal</option>
                    <option value="behavioral" {{ request('category') === 'behavioral' ? 'selected' : '' }}>Behavioral</option>
                    <option value="career"     {{ request('category') === 'career'     ? 'selected' : '' }}>Career</option>
                </select>
                <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">All Status</option>
                    <option value="open"     {{ request('status') === 'open'     ? 'selected' : '' }}>Open</option>
                    <option value="ongoing"  {{ request('status') === 'ongoing'  ? 'selected' : '' }}>Ongoing</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Filter</button>
                @if(request()->hasAny(['search','category','status']))
                    <a href="{{ route('counseling.index') }}" class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Clear</a>
                @endif
            </form>
            <button onclick="document.getElementById('createModal').classList.remove('hidden')"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 whitespace-nowrap">
                + New Record
            </button>
        </div>

        <div class="overflow-x-auto scroll-touch">
            <table class="w-full text-sm min-w-[640px]">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-widest">
                    <tr>
                        <th class="px-6 py-3 text-left">Student ID</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Category</th>
                        <th class="px-6 py-3 text-left">Concern</th>
                        <th class="px-6 py-3 text-left">Session Date</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($records as $record)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $record->student_id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $record->student_name }}</td>
                        <td class="px-6 py-4">
                            @php
                                $catBadge = match($record->category) {
                                    'academic'   => 'bg-blue-100 text-blue-700',
                                    'personal'   => 'bg-purple-100 text-purple-700',
                                    'behavioral' => 'bg-orange-100 text-orange-700',
                                    'career'     => 'bg-teal-100 text-teal-700',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $catBadge }} capitalize">
                                {{ $record->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $record->concern }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $record->session_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusBadge = match($record->status) {
                                    'open'     => 'bg-red-100 text-red-600',
                                    'ongoing'  => 'bg-yellow-100 text-yellow-700',
                                    'resolved' => 'bg-green-100 text-green-700',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusBadge }} capitalize">
                                {{ $record->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <button onclick='openEdit(@json($record))'
                                class="text-indigo-600 hover:underline text-xs font-medium">Edit</button>
                            <form method="POST" action="{{ route('counseling.destroy', $record) }}"
                                onsubmit="return confirm('Delete this record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">No counseling records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $records->links() }}
        </div>
        @endif
    </div>

    {{-- CREATE MODAL --}}
    <div id="createModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">New Counseling Record</h2>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" action="{{ route('counseling.store') }}" class="px-6 py-4 space-y-4">
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
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Course</label>
                        <input type="text" name="course" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Year Level</label>
                        <select name="year_level" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">—</option>
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}">Year {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Category *</label>
                        <select name="category" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="academic">Academic</option>
                            <option value="personal">Personal</option>
                            <option value="behavioral">Behavioral</option>
                            <option value="career">Career</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Session Date *</label>
                        <input type="date" name="session_date" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Concern *</label>
                    <textarea name="concern" rows="3" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Action Taken</label>
                    <textarea name="action_taken" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Status *</label>
                    <select name="status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="open">Open</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="resolved">Resolved</option>
                    </select>
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
                <h2 class="font-semibold text-gray-800">Edit Counseling Record</h2>
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
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Course</label>
                        <input type="text" name="course" id="edit_course" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Year Level</label>
                        <select name="year_level" id="edit_year_level" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">—</option>
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}">Year {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Category *</label>
                        <select name="category" id="edit_category" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="academic">Academic</option>
                            <option value="personal">Personal</option>
                            <option value="behavioral">Behavioral</option>
                            <option value="career">Career</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Session Date *</label>
                        <input type="date" name="session_date" id="edit_session_date" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Concern *</label>
                    <textarea name="concern" id="edit_concern" rows="3" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Action Taken</label>
                    <textarea name="action_taken" id="edit_action_taken" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Status *</label>
                    <select name="status" id="edit_status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="open">Open</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="resolved">Resolved</option>
                    </select>
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
    function openEdit(r) {
        document.getElementById('edit_student_id').value    = r.student_id;
        document.getElementById('edit_student_name').value  = r.student_name;
        document.getElementById('edit_course').value        = r.course ?? '';
        document.getElementById('edit_year_level').value    = r.year_level ?? '';
        document.getElementById('edit_category').value      = r.category;
        document.getElementById('edit_session_date').value  = r.session_date;
        document.getElementById('edit_concern').value       = r.concern;
        document.getElementById('edit_action_taken').value  = r.action_taken ?? '';
        document.getElementById('edit_status').value        = r.status;
        document.getElementById('editForm').action          = '/counseling/' + r.id;
        document.getElementById('editModal').classList.remove('hidden');
    }
    </script>

</x-app-layout>