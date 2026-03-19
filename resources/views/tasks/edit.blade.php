@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
    <div class="max-w-2xl mx-auto">

        <!-- Back Button & Header -->
        <div class="mb-6">
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Tasks
            </a>
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">Edit Task</h1>

                <!-- Completed Badge -->
                @if($task->status === 'completed')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 text-sm font-medium rounded-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Completed {{ $task->completed_at?->diffForHumans() }}
                </span>
                @endif
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 sm:p-8">
            <form method="POST" action="{{ route('tasks.update', $task->id) }}" x-data="{ loading: false }" @submit="loading = true">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-5">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Task Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow
                              @error('title') border-red-400 @enderror">
                    @error('title')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-5">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow resize-none
                                 @error('description') border-red-400 @enderror">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority & Status Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select id="priority" name="priority" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>🟢 Low</option>
                            <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                            <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>🔴 High</option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select id="status" name="status"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>

                <!-- Category & Due Date Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">Category</label>
                        <select id="category_id" name="category_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="">No Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1.5">Due Date</label>
                        <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                </div>

                <!-- Metadata -->
                <div class="flex items-center gap-4 text-xs text-gray-400 mb-6 pb-6 border-b border-gray-100">
                    <span>Created {{ $task->created_at->format('M d, Y \a\t h:i A') }}</span>
                    @if($task->updated_at->ne($task->created_at))
                        <span>· Updated {{ $task->updated_at->diffForHumans() }}</span>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between">
                    <button type="button" onclick="document.getElementById('delete-form-{{ $task->id }}').submit()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"
                            onclick="return confirm('Move this task to trash?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('tasks.index') }}"
                           class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit" :disabled="loading"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white text-sm font-semibold rounded-lg transition-colors flex items-center gap-2">
                            <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" style="display:none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <span x-text="loading ? 'Saving...' : 'Save Changes'">Save Changes</span>
                        </button>
                    </div>
                </div>
            </form>

            {{-- Delete form outside the update form to avoid nested form bug --}}
            <form id="delete-form-{{ $task->id }}" method="POST" action="{{ route('tasks.destroy', $task->id) }}" style="display:none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection
