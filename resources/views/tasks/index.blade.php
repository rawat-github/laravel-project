@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tasks</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $tasks->total() }} total tasks</p>
            </div>
            <a href="{{ route('tasks.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Task
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6" x-data="{ filtersOpen: false }">
            <div class="flex items-center justify-between">
                <form method="GET" action="{{ route('tasks.index') }}" class="flex-1 flex gap-3">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-md">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <button type="button" @click="filtersOpen = !filtersOpen"
                            class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filters
                        @if(request()->hasAny(['status', 'priority', 'category_id', 'due_before', 'due_after']))
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        @endif
                    </button>

                    <!-- Expanded Filters -->
                    <div x-show="filtersOpen" x-transition class="absolute left-0 right-0 top-full mt-2 bg-white rounded-xl border border-gray-200 p-4 shadow-lg z-10"
                         style="display: none; position: relative; top: 0; margin-top: 0;">
                    </div>
                </form>
            </div>

            <!-- Filter Row (expandable) -->
            <div x-show="filtersOpen" x-collapse class="mt-4 pt-4 border-t border-gray-100">
                <form method="GET" action="{{ route('tasks.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <!-- Preserve search -->
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Priority</label>
                        <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">All Priorities</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Category</label>
                        <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Due After</label>
                        <input type="date" name="due_after" value="{{ request('due_after') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Due Before</label>
                        <input type="date" name="due_before" value="{{ request('due_before') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div class="sm:col-span-2 lg:col-span-5 flex items-center gap-3 pt-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Apply Filters
                        </button>
                        <a href="{{ route('tasks.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 font-medium">
                            Clear All
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sort Bar -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Sort by:</span>
                @php
                    $currentSort = request('sort_by', 'created_at');
                    $currentOrder = request('sort_order', 'desc');
                @endphp
                @foreach(['created_at' => 'Newest', 'due_date' => 'Due Date', 'priority' => 'Priority', 'title' => 'Name'] as $field => $label)
                    <a href="{{ route('tasks.index', array_merge(request()->query(), ['sort_by' => $field, 'sort_order' => ($currentSort == $field && $currentOrder == 'asc') ? 'desc' : 'asc'])) }}"
                       class="px-2.5 py-1 rounded-md transition-colors {{ $currentSort == $field ? 'bg-blue-50 text-blue-700 font-medium' : 'hover:bg-gray-100' }}">
                        {{ $label }}
                        @if($currentSort == $field)
                            <span>{{ $currentOrder == 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Task List -->
        @if($tasks->count() > 0)
            <div class="space-y-2">
                @foreach($tasks as $task)
                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-sm transition-shadow group"
                         x-data="{ showActions: false }">
                        <div class="flex items-start gap-4">

                            <!-- Toggle Complete -->
                            <form method="POST" action="{{ route('tasks.toggle', $task->id) }}" class="shrink-0 mt-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors
                                {{ $task->status === 'completed'
                                    ? 'bg-green-500 border-green-500'
                                    : 'border-gray-300 hover:border-blue-500' }}">
                                    @if($task->status === 'completed')
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @endif
                                </button>
                            </form>

                            <!-- Task Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('tasks.edit', $task->id) }}"
                                           class="text-sm font-medium {{ $task->status === 'completed' ? 'text-gray-400 line-through' : 'text-gray-900' }} hover:text-blue-600 transition-colors">
                                            {{ $task->title }}
                                        </a>
                                        @if($task->description)
                                            <p class="text-sm text-gray-400 mt-0.5 truncate">{{ $task->description }}</p>
                                        @endif

                                        <!-- Meta -->
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            <!-- Priority Badge -->
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                            {{ $task->priority === 'high' ? 'bg-red-50 text-red-700' :
                                               ($task->priority === 'medium' ? 'bg-yellow-50 text-yellow-700' : 'bg-green-50 text-green-700') }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>

                                            <!-- Status Badge -->
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                            {{ $task->status === 'completed' ? 'bg-green-50 text-green-700' :
                                               ($task->status === 'in_progress' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                                            {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                        </span>

                                            <!-- Category -->
                                            @if($task->category)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                <span class="w-2 h-2 rounded-full" style="background-color: {{ $task->category->color ?? '#6B7280' }}"></span>
                                                {{ $task->category->name }}
                                            </span>
                                            @endif

                                            <!-- Due Date -->
                                            @if($task->due_date)
                                                @php
                                                    $isOverdue = $task->due_date->isPast() && $task->status !== 'completed';
                                                    $isToday   = $task->due_date->isToday();
                                                @endphp
                                                <span class="inline-flex items-center gap-1 text-xs {{ $isOverdue ? 'text-red-600' : ($isToday ? 'text-yellow-600' : 'text-gray-400') }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $isToday ? 'Today' : $task->due_date->format('M d, Y') }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="relative shrink-0" @click.away="showActions = false">
                                        <button @click="showActions = !showActions"
                                                class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 opacity-0 group-hover:opacity-100 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/>
                                            </svg>
                                        </button>

                                        <!-- Dropdown -->
                                        <div x-show="showActions" x-transition
                                             class="absolute right-0 mt-1 w-40 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-20"
                                             style="display: none;">
                                            <a href="{{ route('tasks.edit', $task->id) }}"
                                               class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('tasks.destroy', $task->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Move this task to trash?')"
                                                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $tasks->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">No tasks found</h3>
                <p class="text-gray-500 text-sm mb-4">
                    @if(request()->hasAny(['search', 'status', 'priority', 'category_id']))
                        Try adjusting your filters or search terms.
                    @else
                        Get started by creating your first task.
                    @endif
                </p>
                <a href="{{ route('tasks.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Task
                </a>
            </div>
        @endif
    </div>
@endsection
