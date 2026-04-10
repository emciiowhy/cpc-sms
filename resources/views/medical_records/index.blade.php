<x-app-layout>
    <x-slot name="header">Medical Records</x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Total Records</p>
            <p class="text-3xl font-bold text-indigo-700">{{ $summary['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Fit</p>
            <p class="text-3xl font-bold text-green-600">{{ $summary['fit'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Not Fit</p>
            <p class="text-3xl font-bold text-red-500">{{ $summary['not_fit'] }}</p>
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
                    <option value="fit"     {{ request('status') === 'fit'     ? 'selected' : '' }}>Fit</option>
                    <option value="not_fit" {{ request('status') === 'not_fit' ? 'selected' : '' }}>Not Fit</option>
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Filter</button>
                @if(request()->hasAny(['search','status']))
                    <a href="{{ route('medical-records.index') }}" class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Clear</a>
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
                        <th class="px-6 py-3 text-left">Blood Type</th>
                        <th class="px-6 py-3 text-left">Height</th>
                        <th class="px-6 py-3 text-left">Weight</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">File</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($records as $record)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $record->student_id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $record->student_name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $record->blood_type ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $record->height ? $record->height . ' cm' : '—' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $record->weight ? $record->weight . ' kg' : '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $record->status === 'fit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                {{ $record->status === 'fit' ? 'Fit' : 'Not Fit' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($record->attachment)
                                <a href="{{ Storage::url($record->attachment) }}" target="_blank"
                                    class="text-indigo-600 hover:underline text-xs">View File</a>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <button onclick='openEdit(@json($record))'
                                class="text-indigo-600 hover:underline text-xs font-medium">Edit</button>
                            <form method="POST" action="{{ route('medical-records.destroy', $record) }}"
                                onsubmit="return confirm('Delete this record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">No medical records found.</td>
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
                <h2 class="font-semibold text-gray-800">New Medical Record</h2>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" action="{{ route('medical-records.store') }}" enctype="multipart/form-data" class="px-6 py-4 space-y-4">
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
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Blood Type</label>
                        <select name="blood_type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">—</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                                <option value="{{ $bt }}">{{ $bt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Height (cm)</label>
                        <input type="number" name="height" step="0.01" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Weight (kg)</label>
                        <input type="number" name="weight" step="0.01" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Medical History</label>
                    <textarea name="medical_history" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Allergies</label>
                    <textarea name="allergies" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Status *</label>
                        <select name="status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="fit">Fit</option>
                            <option value="not_fit">Not Fit</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Attachment (PDF/Image)</label>
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Save Record</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">Edit Medical Record</h2>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" id="editForm" enctype="multipart/form-data" class="px-6 py-4 space-y-4">
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
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Blood Type</label>
                        <select name="blood_type" id="edit_blood_type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">—</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                                <option value="{{ $bt }}">{{ $bt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Height (cm)</label>
                        <input type="number" name="height" id="edit_height" step="0.01" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Weight (kg)</label>
                        <input type="number" name="weight" id="edit_weight" step="0.01" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Medical History</label>
                    <textarea name="medical_history" id="edit_medical_history" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Allergies</label>
                    <textarea name="allergies" id="edit_allergies" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Status *</label>
                        <select name="status" id="edit_status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="fit">Fit</option>
                            <option value="not_fit">Not Fit</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Replace Attachment</label>
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                    </div>
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
        document.getElementById('edit_student_id').value      = r.student_id;
        document.getElementById('edit_student_name').value    = r.student_name;
        document.getElementById('edit_course').value          = r.course ?? '';
        document.getElementById('edit_year_level').value      = r.year_level ?? '';
        document.getElementById('edit_blood_type').value      = r.blood_type ?? '';
        document.getElementById('edit_height').value          = r.height ?? '';
        document.getElementById('edit_weight').value          = r.weight ?? '';
        document.getElementById('edit_medical_history').value = r.medical_history ?? '';
        document.getElementById('edit_allergies').value       = r.allergies ?? '';
        document.getElementById('edit_status').value          = r.status;
        document.getElementById('editForm').action            = '/medical-records/' + r.id;
        document.getElementById('editModal').classList.remove('hidden');
    }
    </script>

</x-app-layout>