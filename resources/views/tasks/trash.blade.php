@extends('layouts.app')

@section('title', 'Trash')

@section('content')
    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Trash</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $tasks->total() }} deleted tasks</p>
            </div>
            @if($tasks->total() > 0)
                <form method="POST" action="{{ route('tasks.force-delete-all') }}"
                      onsubmit="return confirm('Permanently delete all trashed tasks? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 border border-red-300 text-red-600 hover:bg-red-50 text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Empty Trash
                    </button>
                </form>
            @endif
        </div>

        <!-- Info Banner -->
        <div class="flex items-start gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-lg mb-6">
            <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-amber-800">
                Deleted tasks are kept for 30 days before being permanently removed. You can restore them anytime.
            </p>
        </div>

        <!-- Trashed Tasks List -->
        @if($tasks->count() > 0)
            <div class="space-y-2">
                @foreach($tasks as $task)
                    <div class="bg-white rounded-xl border border-gray-200 p-4 opacity-75 hover:opacity-100 transition-opacity">
                        <div class="flex items-center gap-4">

                            <!-- Task Info -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-600 line-through">{{ $task->title }}</p>
                                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $task->priority === 'high' ? 'bg-red-50 text-red-700' :
                                       ($task->priority === 'medium' ? 'bg-yellow-50 text-yellow-700' : 'bg-green-50 text-green-700') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>

                                    @if($task->category)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        <span class="w-2 h-2 rounded-full" style="background-color: {{ $task->category->color ?? '#6B7280' }}"></span>
                                        {{ $task->category->name }}
                                    </span>
                                    @endif

                                    <span class="text-xs text-gray-400">
                                    Deleted {{ $task->deleted_at->diffForHumans() }}
                                </span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 shrink-0">
                                <!-- Restore -->
                                <form method="POST" action="{{ route('tasks.restore', $task->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Restore task">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2M3 10l4 4M3 10l4-4"/>
                                        </svg>
                                        Restore
                                    </button>
                                </form>

                                <!-- Permanently Delete -->
                                <form method="POST" action="{{ route('tasks.force-delete', $task->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Permanently delete this task? This cannot be undone.')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Delete permanently">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $tasks->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Trash is empty</h3>
                <p class="text-gray-500 text-sm">No deleted tasks. Deleted tasks will appear here.</p>
            </div>
        @endif
    </div>
@endsection
