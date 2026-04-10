<x-app-layout>
    <x-slot name="header">Student Organizations</x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Total Orgs</p>
            <p class="text-3xl font-bold text-indigo-700">{{ $summary['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Active</p>
            <p class="text-3xl font-bold text-green-600">{{ $summary['active'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Inactive</p>
            <p class="text-3xl font-bold text-gray-400">{{ $summary['inactive'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Total Members</p>
            <p class="text-3xl font-bold text-blue-600">{{ $summary['members'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100">
            <form method="GET" class="flex gap-2 flex-wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search organization..."
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">All Status</option>
                    <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Filter</button>
                @if(request()->hasAny(['search','status']))
                    <a href="{{ route('organizations.index') }}" class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Clear</a>
                @endif
            </form>
            <button onclick="document.getElementById('createModal').classList.remove('hidden')"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 whitespace-nowrap">
                + New Organization
            </button>
        </div>

        <div class="overflow-x-auto scroll-touch">
            <table class="w-full text-sm min-w-[640px]">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-widest">
                    <tr>
                        <th class="px-6 py-3 text-left">Organization</th>
                        <th class="px-6 py-3 text-left">Acronym</th>
                        <th class="px-6 py-3 text-left">Adviser</th>
                        <th class="px-6 py-3 text-left">Members</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($organizations as $org)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $org->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $org->acronym ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $org->adviser ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-indigo-700">{{ $org->members_count }}</span>
                            <span class="text-gray-400 text-xs">members</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium capitalize
                                {{ $org->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $org->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('organizations.show', $org) }}"
                                class="text-blue-600 hover:underline text-xs font-medium">View</a>
                            <button onclick='openEdit(@json($org))'
                                class="text-indigo-600 hover:underline text-xs font-medium">Edit</button>
                            <form method="POST" action="{{ route('organizations.destroy', $org) }}"
                                onsubmit="return confirm('Delete this organization?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">No organizations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($organizations->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $organizations->links() }}
        </div>
        @endif
    </div>

    {{-- CREATE MODAL --}}
    <div id="createModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">New Organization</h2>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" action="{{ route('organizations.store') }}" class="px-6 py-4 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Organization Name *</label>
                        <input type="text" name="name" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Acronym</label>
                        <input type="text" name="acronym" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Adviser</label>
                        <input type="text" name="adviser" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Status *</label>
                        <select name="status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Create</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">Edit Organization</h2>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" id="editForm" class="px-6 py-4 space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Organization Name *</label>
                        <input type="text" name="name" id="edit_name" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Acronym</label>
                        <input type="text" name="acronym" id="edit_acronym" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Description</label>
                    <textarea name="description" id="edit_description" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Adviser</label>
                        <input type="text" name="adviser" id="edit_adviser" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Status *</label>
                        <select name="status" id="edit_status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
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
    function openEdit(o) {
        document.getElementById('edit_name').value        = o.name;
        document.getElementById('edit_acronym').value     = o.acronym ?? '';
        document.getElementById('edit_description').value = o.description ?? '';
        document.getElementById('edit_adviser').value     = o.adviser ?? '';
        document.getElementById('edit_status').value      = o.status;
        document.getElementById('editForm').action        = '/organizations/' + o.id;
        document.getElementById('editModal').classList.remove('hidden');
    }
    </script>

</x-app-layout>