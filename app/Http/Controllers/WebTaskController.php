<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebTaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = Task::with('category')
            ->where('user_id', auth()->id());

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('due_after')) {
            $query->whereDate('due_date', '>=', $request->due_after);
        }

        if ($request->filled('due_before')) {
            $query->whereDate('due_date', '<=', $request->due_before);
        }

        $sortBy    = in_array($request->sort_by, ['created_at', 'due_date', 'priority', 'title']) ? $request->sort_by : 'created_at';
        $sortOrder = $request->sort_order === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $tasks      = $query->paginate(15);
        $categories = Category::all();

        return view('tasks.index', compact('tasks', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high',
            'status'      => 'required|in:pending,in_progress,completed',
            'category_id' => 'nullable|exists:categories,id',
            'due_date'    => 'nullable|date',
        ]);

        $validated['user_id'] = auth()->id();

        if ($validated['status'] === 'completed') {
            $validated['completed_at'] = now();
        }

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        $this->authorize('update', $task);
        $categories = Category::all();
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        \Log::info('UPDATE called', ['task_id' => $task->id, '_method' => $request->input('_method'), 'all' => $request->all()]);
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high',
            'status'      => 'required|in:pending,in_progress,completed',
            'category_id' => 'nullable|exists:categories,id',
            'due_date'    => 'nullable|date',
        ]);

        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        \Log::info('DESTROY called', ['task_id' => $task->id]);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task moved to trash.');
    }

    public function toggle(Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        if ($task->status === 'completed') {
            $task->update(['status' => 'pending', 'completed_at' => null]);
        } else {
            $task->update(['status' => 'completed', 'completed_at' => now()]);
        }

        return back();
    }

    public function trash(): View
    {
        $tasks = Task::onlyTrashed()->where('user_id', auth()->id())->paginate(15);
        return view('tasks.trash', compact('tasks'));
    }

    public function restore(int $id): RedirectResponse
    {
        $task = Task::onlyTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $task->restore();
        return back()->with('success', 'Task restored.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $task = Task::onlyTrashed()->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $task->forceDelete();
        return back()->with('success', 'Task permanently deleted.');
    }

    public function forceDeleteAll(): RedirectResponse
    {
        Task::onlyTrashed()->where('user_id', auth()->id())->forceDelete();
        return back()->with('success', 'Trash emptied.');
    }
}