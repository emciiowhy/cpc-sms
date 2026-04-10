<x-app-layout>
    <x-slot name="header">{{ $organization->name }}</x-slot>

    {{-- Org Profile Card --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-xl font-bold text-gray-800">{{ $organization->name }}</h2>
                    @if($organization->acronym)
                        <span class="text-sm bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">{{ $organization->acronym }}</span>
                    @endif
                    <span class="text-sm px-2 py-0.5 rounded-full capitalize
                        {{ $organization->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $organization->status }}
                    </span>
                </div>
                @if($organization->description)
                    <p class="text-sm text-gray-500 mb-2">{{ $organization->description }}</p>
                @endif
                @if($organization->adviser)
                    <p class="text-sm text-gray-500">Adviser: <span class="font-medium text-gray-700">{{ $organization->adviser }}</span></p>
                @endif
            </div>
            <a href="{{ route('organizations.index') }}" class="text-sm text-indigo-600 hover:underline">← Back</a>
        </div>
    </div>

    {{-- Members Section --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100">
            <div>
                <h3 class="font-semibold text-gray-800">Members</h3>
                <p class="text-xs text-gray-400">{{ $organization->members()->count() }} total members</p>
            </div>
            <div class="flex gap-2 flex-wrap">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search member..."
                        class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <select name="role" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">All Roles</option>
                        <option value="president"      {{ request('role') === 'president'      ? 'selected' : '' }}>President</option>
                        <option value="vice_president" {{ request('role') === 'vice_president' ? 'selected' : '' }}>Vice President</option>
                        <option value="secretary"      {{ request('role') === 'secretary'      ? 'selected' : '' }}>Secretary</option>
                        <option value="treasurer"      {{ request('role') === 'treasurer'      ? 'selected' : '' }}>Treasurer</option>
                        <option value="member"         {{ request('role') === 'member'         ? 'selected' : '' }}>Member</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Filter</button>
                </form>
                <button onclick="document.getElementById('addMemberModal').classList.remove('hidden')"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 whitespace-nowrap">
                    + Add Member
                </button>
            </div>
        </div>

        <div class="overflow-x-auto scroll-touch">
            <table class="w-full text-sm min-w-[640px]">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-widest">
                    <tr>
                        <th class="px-6 py-3 text-left">Student ID</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-left">Course</th>
                        <th class="px-6 py-3 text-left">Year</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($members as $member)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $member->student_id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $member->student_name }}</td>
                        <td class="px-6 py-4">
                            @php
                                $roleBadge = match($member->role) {
                                    'president'      => 'bg-indigo-100 text-indigo-700',
                                    'vice_president' => 'bg-blue-100 text-blue-700',
                                    'secretary'      => 'bg-green-100 text-green-700',
                                    'treasurer'      => 'bg-yellow-100 text-yellow-700',
                                    default          => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $roleBadge }} capitalize">
                                {{ str_replace('_', ' ', $member->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $member->course ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $member->year_level ? 'Year ' . $member->year_level : '—' }}</td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('organizations.members.remove', [$organization, $member]) }}"
                                onsubmit="return confirm('Remove this member?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">No members yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $members->links() }}
        </div>
        @endif
    </div>

    {{-- ADD MEMBER MODAL --}}
    <div id="addMemberModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">Add Member</h2>
                <button onclick="document.getElementById('addMemberModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" action="{{ route('organizations.members.add', $organization) }}" class="px-6 py-4 space-y-4">
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
                    <label class="block text-xs text-gray-500 mb-1">Role *</label>
                    <select name="role" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="president">President</option>
                        <option value="vice_president">Vice President</option>
                        <option value="secretary">Secretary</option>
                        <option value="treasurer">Treasurer</option>
                        <option value="member" selected>Member</option>
                    </select>
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
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('addMemberModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Add Member</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>