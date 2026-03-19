<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebCategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::where('user_id', auth()->id())
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($q) {
                $q->where('status', 'completed');
            }])
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        $validated['user_id'] = auth()->id();

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        abort_if($category->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        abort_if($category->user_id !== auth()->id(), 403);

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}