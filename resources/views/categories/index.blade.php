@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="max-w-4xl mx-auto" x-data="categoryManager()">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
                <p class="text-gray-500 text-sm mt-1">Organize your tasks into groups</p>
            </div>
            <button @click="openCreateModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Category
            </button>
        </div>

        <!-- Categories Grid -->
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categories as $category)
                    <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-sm transition-shadow group">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                     style="background-color: {{ $category->color ?? '#3B82F6' }}20">
                                    <div class="w-4 h-4 rounded-full" style="background-color: {{ $category->color ?? '#3B82F6' }}"></div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">{{ $category->name }}</h3>
                                    <p class="text-xs text-gray-400">{{ $category->tasks_count ?? 0 }} tasks</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ $category->color }}')"
                                        class="p-1.5 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('categories.destroy', $category->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this category? Tasks in this category will become uncategorized.')"
                                            class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Task Status Breakdown -->
                        @php
                            $total = $category->tasks_count ?? 0;
                            $completed = $category->completed_tasks_count ?? 0;
                            $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
                        @endphp
                        @if($total > 0)
                            <div class="mt-3">
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%; background-color: {{ $category->color ?? '#3B82F6' }}"></div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ $completed }}/{{ $total }} completed</p>
                            </div>
                        @endif

                        <!-- View Tasks Link -->
                        <a href="{{ route('tasks.index') }}?category_id={{ $category->id }}"
                           class="mt-3 inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-700 font-medium">
                            View tasks
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">No categories yet</h3>
                <p class="text-gray-500 text-sm mb-4">Create categories to organize your tasks better.</p>
                <button @click="openCreateModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Category
                </button>
            </div>
        @endif

        <!-- Create/Edit Modal -->
        <div x-show="showModal" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;">

            <!-- Backdrop -->
            <div class="absolute inset-0 bg-gray-900/50" @click="showModal = false"></div>

            <!-- Modal -->
            <div x-show="showModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

                <h2 class="text-lg font-bold text-gray-900 mb-5" x-text="isEditing ? 'Edit Category' : 'New Category'"></h2>

                <form :action="isEditing ? '{{ url('categories') }}/' + editId : '{{ route('categories.store') }}'" method="POST">
                    @csrf
                    <template x-if="isEditing">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <!-- Name -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" x-model="formName" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                               placeholder="e.g., Work, Personal, Shopping">
                    </div>

                    <!-- Color Picker -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="color in colors" :key="color">
                                <button type="button" @click="formColor = color"
                                        class="w-8 h-8 rounded-full border-2 transition-transform hover:scale-110"
                                        :style="`background-color: ${color}`"
                                        :class="formColor === color ? 'border-gray-900 scale-110' : 'border-transparent'">
                                </button>
                            </template>
                        </div>
                        <input type="hidden" name="color" x-model="formColor">
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="showModal = false"
                                class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors"
                                x-text="isEditing ? 'Save Changes' : 'Create Category'">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function categoryManager() {
                return {
                    showModal: false,
                    isEditing: false,
                    editId: null,
                    formName: '',
                    formColor: '#3B82F6',
                    colors: ['#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6', '#F97316', '#6B7280'],

                    openCreateModal() {
                        this.isEditing = false;
                        this.editId = null;
                        this.formName = '';
                        this.formColor = '#3B82F6';
                        this.showModal = true;
                    },

                    openEditModal(id, name, color) {
                        this.isEditing = true;
                        this.editId = id;
                        this.formName = name;
                        this.formColor = color || '#3B82F6';
                        this.showModal = true;
                    }
                }
            }
        </script>
    @endpush
@endsection
