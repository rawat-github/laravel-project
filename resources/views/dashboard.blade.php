@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name ?? 'John' }}!</h1>
            <p class="text-gray-500 mt-1">Here's an overview of your tasks</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

            <!-- Total Tasks -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Tasks</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                    <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Completed</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['completed'] ?? 0 }}</p>
                    </div>
                    <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                @if(($stats['total'] ?? 0) > 0)
                    <div class="mt-3">
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ round(($stats['completed'] / $stats['total']) * 100) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">{{ round(($stats['completed'] / $stats['total']) * 100) }}% done</p>
                    </div>
                @endif
            </div>

            <!-- In Progress -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">In Progress</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['in_progress'] ?? 0 }}</p>
                    </div>
                    <div class="w-11 h-11 bg-yellow-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Overdue -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Overdue</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['overdue'] ?? 0 }}</p>
                    </div>
                    <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Tasks by Priority -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Tasks by Priority</h3>
                <div class="space-y-3">
                    @php
                        $priorities = [
                            'high'   => ['label' => 'High',   'color' => 'red',    'count' => $stats['priority']['high'] ?? 0],
                            'medium' => ['label' => 'Medium', 'color' => 'yellow', 'count' => $stats['priority']['medium'] ?? 0],
                            'low'    => ['label' => 'Low',    'color' => 'green',  'count' => $stats['priority']['low'] ?? 0],
                        ];
                        $maxCount = max(array_column($priorities, 'count')) ?: 1;
                    @endphp
                    @foreach($priorities as $key => $p)
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-600">{{ $p['label'] }}</span>
                                <span class="font-medium text-gray-900">{{ $p['count'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-{{ $p['color'] }}-500 h-2 rounded-full transition-all duration-500"
                                     style="width: {{ round(($p['count'] / $maxCount) * 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tasks by Category -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Tasks by Category</h3>
                @if(isset($stats['categories']) && count($stats['categories']) > 0)
                    <div class="space-y-3">
                        @foreach($stats['categories'] as $cat)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full" style="background-color: {{ $cat['color'] ?? '#6B7280' }}"></span>
                                    <span class="text-sm text-gray-600">{{ $cat['name'] }}</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $cat['tasks_count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-sm text-gray-400">No categories yet</p>
                        <a href="{{ route('categories.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium mt-1 inline-block">Create one</a>
                    </div>
                @endif
            </div>

            <!-- Upcoming Deadlines -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-900">Upcoming Deadlines</h3>
                    <a href="{{ route('tasks.index') }}?sort_by=due_date&sort_order=asc" class="text-xs text-blue-600 hover:text-blue-700 font-medium">View all</a>
                </div>
                @if(isset($upcomingTasks) && count($upcomingTasks) > 0)
                    <div class="space-y-3">
                        @foreach($upcomingTasks as $task)
                            <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <form method="POST" action="{{ route('tasks.toggle', $task->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="mt-0.5 w-5 h-5 rounded-full border-2 border-gray-300 hover:border-blue-500 flex items-center justify-center transition-colors shrink-0">
                                    </button>
                                </form>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $task->title }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($task->due_date)
                                            @php
                                                $isOverdue = $task->due_date->isPast();
                                                $isToday   = $task->due_date->isToday();
                                                $isTomorrow = $task->due_date->isTomorrow();
                                            @endphp
                                            <span class="text-xs {{ $isOverdue ? 'text-red-600' : ($isToday ? 'text-yellow-600' : 'text-gray-400') }}">
                                            {{ $isToday ? 'Today' : ($isTomorrow ? 'Tomorrow' : ($isOverdue ? 'Overdue' : $task->due_date->format('M d'))) }}
                                        </span>
                                        @endif
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium
                                        {{ $task->priority === 'high' ? 'bg-red-50 text-red-700' : ($task->priority === 'medium' ? 'bg-yellow-50 text-yellow-700' : 'bg-green-50 text-green-700') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-sm text-gray-400">No upcoming deadlines</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Add Task -->
        <div class="mt-6 bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Quick Add Task</h3>
            <form method="POST" action="{{ route('tasks.store') }}" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="hidden" name="status" value="pending">
                <input type="text" name="title" placeholder="What needs to be done?" required
                       class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <select name="priority" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-600">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
                <input type="date" name="due_date" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-600">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors whitespace-nowrap">
                    Add Task
                </button>
            </form>
        </div>
    </div>
@endsection
