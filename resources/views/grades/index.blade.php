<x-app-layout>
    <x-slot name="header">Grading System</x-slot>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Total Records</p>
            <p class="text-3xl font-bold text-indigo-700">{{ $summary['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Passing</p>
            <p class="text-3xl font-bold text-green-600">{{ $summary['passing'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Failing</p>
            <p class="text-3xl font-bold text-red-500">{{ $summary['failing'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100">
            <form method="GET" class="flex gap-2 flex-wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search student..."
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <select name="subject" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject }}" {{ request('subject') === $subject ? 'selected' : '' }}>{{ $subject }}</option>
                    @endforeach
                </select>
                <select name="remarks" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">All Remarks</option>
                    <option value="pass" {{ request('remarks') === 'pass' ? 'selected' : '' }}>Pass</option>
                    <option value="fail" {{ request('remarks') === 'fail' ? 'selected' : '' }}>Fail</option>
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Filter</button>
                @if(request()->hasAny(['search','subject','remarks']))
                    <a href="{{ route('grades.index') }}" class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Clear</a>
                @endif
            </form>
            <button onclick="document.getElementById('createModal').classList.remove('hidden')"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 whitespace-nowrap">
                + Add Grade
            </button>
        </div>

        <div class="overflow-x-auto scroll-touch">
            <table class="w-full text-sm min-w-[640px]">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-widest">
                    <tr>
                        <th class="px-6 py-3 text-left">Student ID</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Subject</th>
                        <th class="px-6 py-3 text-left">Midterm</th>
                        <th class="px-6 py-3 text-left">Finals</th>
                        <th class="px-6 py-3 text-left">Average</th>
                        <th class="px-6 py-3 text-left">Remarks</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($grades as $grade)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $grade->student_id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $grade->student_name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $grade->subject }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $grade->midterm }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $grade->finals }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $grade->average }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium capitalize
                                {{ $grade->remarks === 'pass' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                {{ $grade->remarks }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <button onclick='openEdit(@json($grade))'
                                class="text-indigo-600 hover:underline text-xs font-medium">Edit</button>
                            <form method="POST" action="{{ route('grades.destroy', $grade) }}"
                                onsubmit="return confirm('Delete this grade?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">No grade records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($grades->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $grades->links() }}
        </div>
        @endif
    </div>

    {{-- CREATE MODAL --}}
    <div id="createModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">Add Grade</h2>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" action="{{ route('grades.store') }}" class="px-6 py-4 space-y-4">
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
                        <label class="block text-xs text-gray-500 mb-1">Subject *</label>
                        <input type="text" name="subject" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Course</label>
                        <input type="text" name="course" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Year Level</label>
                        <select name="year_level" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">—</option>
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}">Year {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Midterm *</label>
                        <input type="number" name="midterm" step="0.01" min="0" max="100" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                            oninput="computeAverage()">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Finals *</label>
                        <input type="number" name="finals" step="0.01" min="0" max="100" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                            oninput="computeAverage()">
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg px-4 py-3 flex items-center justify-between">
                    <span class="text-xs text-gray-500">Computed Average</span>
                    <span id="computedAverage" class="font-bold text-indigo-700 text-lg">—</span>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Save Grade</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">Edit Grade</h2>
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
                        <label class="block text-xs text-gray-500 mb-1">Subject *</label>
                        <input type="text" name="subject" id="edit_subject" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Course</label>
                        <input type="text" name="course" id="edit_course" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Year Level</label>
                        <select name="year_level" id="edit_year_level" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">—</option>
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}">Year {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Midterm *</label>
                        <input type="number" name="midterm" id="edit_midterm" step="0.01" min="0" max="100" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                            oninput="computeEditAverage()">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Finals *</label>
                        <input type="number" name="finals" id="edit_finals" step="0.01" min="0" max="100" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                            oninput="computeEditAverage()">
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg px-4 py-3 flex items-center justify-between">
                    <span class="text-xs text-gray-500">Computed Average</span>
                    <span id="editComputedAverage" class="font-bold text-indigo-700 text-lg">—</span>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Update Grade</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function computeAverage() {
        const mid = parseFloat(document.querySelector('[name="midterm"]').value) || 0;
        const fin = parseFloat(document.querySelector('[name="finals"]').value) || 0;
        const avg = ((mid + fin) / 2).toFixed(2);
        document.getElementById('computedAverage').textContent = avg;
    }

    function computeEditAverage() {
        const mid = parseFloat(document.getElementById('edit_midterm').value) || 0;
        const fin = parseFloat(document.getElementById('edit_finals').value) || 0;
        const avg = ((mid + fin) / 2).toFixed(2);
        document.getElementById('editComputedAverage').textContent = avg;
    }

    function openEdit(g) {
        document.getElementById('edit_student_id').value   = g.student_id;
        document.getElementById('edit_student_name').value = g.student_name;
        document.getElementById('edit_subject').value      = g.subject;
        document.getElementById('edit_course').value       = g.course ?? '';
        document.getElementById('edit_year_level').value   = g.year_level ?? '';
        document.getElementById('edit_midterm').value      = g.midterm;
        document.getElementById('edit_finals').value       = g.finals;
        document.getElementById('editComputedAverage').textContent = g.average;
        document.getElementById('editForm').action         = '/grades/' + g.id;
        document.getElementById('editModal').classList.remove('hidden');
    }
    </script>

</x-app-layout>