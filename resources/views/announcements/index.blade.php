<x-app-layout>
    <x-slot name="header">Announcements</x-slot>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Total Posts</p>
            <p class="text-3xl font-bold text-indigo-700">{{ $summary['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Events</p>
            <p class="text-3xl font-bold text-blue-600">{{ $summary['events'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">News</p>
            <p class="text-3xl font-bold text-green-600">{{ $summary['news'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Reminders</p>
            <p class="text-3xl font-bold text-yellow-500">{{ $summary['reminders'] }}</p>
        </div>
    </div>

    {{-- Latest Announcement Highlight --}}
    @if($latest)
    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-5 mb-6">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xs bg-indigo-600 text-white px-2 py-0.5 rounded-full">Latest</span>
            <span class="text-xs text-indigo-400 capitalize">{{ $latest->category }}</span>
            <span class="text-xs text-indigo-400">· {{ $latest->created_at->diffForHumans() }}</span>
        </div>
        <h3 class="font-semibold text-indigo-900 text-lg mb-1">{{ $latest->title }}</h3>
        <p class="text-sm text-indigo-700 line-clamp-2">{{ $latest->content }}</p>
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100">
            <form method="GET" class="flex gap-2 flex-wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search announcements..."
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <select name="category" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">All Categories</option>
                    <option value="events"    {{ request('category') === 'events'    ? 'selected' : '' }}>Events</option>
                    <option value="news"      {{ request('category') === 'news'      ? 'selected' : '' }}>News</option>
                    <option value="reminders" {{ request('category') === 'reminders' ? 'selected' : '' }}>Reminders</option>
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Filter</button>
                @if(request()->hasAny(['search','category']))
                    <a href="{{ route('announcements.index') }}" class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Clear</a>
                @endif
            </form>
            <button onclick="document.getElementById('createModal').classList.remove('hidden')"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 whitespace-nowrap">
                + New Announcement
            </button>
        </div>

        <div class="overflow-x-auto scroll-touch">
            <table class="w-full text-sm min-w-[640px]">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-widest">
                    <tr>
                        <th class="px-6 py-3 text-left">Title</th>
                        <th class="px-6 py-3 text-left">Category</th>
                        <th class="px-6 py-3 text-left">Featured</th>
                        <th class="px-6 py-3 text-left">Posted By</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($announcements as $announcement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $announcement->title }}</td>
                        <td class="px-6 py-4">
                            @php
                                $badge = match($announcement->category) {
                                    'events'    => 'bg-blue-100 text-blue-700',
                                    'news'      => 'bg-green-100 text-green-700',
                                    'reminders' => 'bg-yellow-100 text-yellow-700',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badge }} capitalize">
                                {{ $announcement->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($announcement->is_featured)
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">Featured</span>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $announcement->creator->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $announcement->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <button onclick='openEdit(@json($announcement))'
                                class="text-indigo-600 hover:underline text-xs font-medium">Edit</button>
                            <form method="POST" action="{{ route('announcements.destroy', $announcement) }}"
                                onsubmit="return confirm('Delete this announcement?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">No announcements found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($announcements->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>

    {{-- CREATE MODAL --}}
    <div id="createModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">New Announcement</h2>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" action="{{ route('announcements.store') }}" class="px-6 py-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Title *</label>
                    <input type="text" name="title" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Content *</label>
                    <textarea name="content" rows="4" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Category *</label>
                    <select name="category" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="events">Events</option>
                        <option value="news">News</option>
                        <option value="reminders">Reminders</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" class="rounded border-gray-300">
                    <label for="is_featured" class="text-sm text-gray-600">Mark as Featured</label>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Post</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-end justify-center sm:items-center bg-black/40 p-0 sm:p-4 overscroll-contain">
        <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full max-w-lg max-h-[min(92dvh,92vh)] overflow-y-auto sm:mx-4">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">Edit Announcement</h2>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <form method="POST" id="editForm" class="px-6 py-4 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Title *</label>
                    <input type="text" name="title" id="edit_title" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Content *</label>
                    <textarea name="content" id="edit_content" rows="4" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Category *</label>
                    <select name="category" id="edit_category" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="events">Events</option>
                        <option value="news">News</option>
                        <option value="reminders">Reminders</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_featured" id="edit_is_featured" value="1" class="rounded border-gray-300">
                    <label for="edit_is_featured" class="text-sm text-gray-600">Mark as Featured</label>
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
        document.getElementById('edit_title').value           = a.title;
        document.getElementById('edit_content').value         = a.content;
        document.getElementById('edit_category').value        = a.category;
        document.getElementById('edit_is_featured').checked   = a.is_featured == 1;
        document.getElementById('editForm').action            = '/announcements/' + a.id;
        document.getElementById('editModal').classList.remove('hidden');
    }
    </script>

</x-app-layout>