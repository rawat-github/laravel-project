<?php

use App\Http\Controllers\AddressBookController;
use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WebTaskController;
use App\Http\Controllers\WebCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return '<h1>Login direct HTML works</h1>';
})->name('login');
Route::get('/', function () {
    return view('welcome');
});

//Route::get('/test', function () {
//    return '<h1>Routes are working!</h1>';
//});

Route::get('/addressbook', [AddressBookController::class, 'index']);
Route::get('/add-contact', [AddressBookController::class, 'create']);
Route::post('/store-contact', [AddressBookController::class, 'store']);
Route::get('/edit-contact/{id}', [AddressBookController::class, 'edit']);
Route::put('/update-contact/{id}', [AddressBookController::class, 'update']);
Route::get('/delete-contact/{id}', [AddressBookController::class, 'destroy']);

// Auth routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/login', [WebAuthController::class, 'login'])->name('login.submit');
Route::post('/register', [WebAuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// ======= DASHBOARD =======

Route::get('/dashboard', function () {
    return redirect()->route('dashboard');
});
Route::get('/', function () {
    $userId = auth()->id();

    $tasks = \App\Models\Task::where('user_id', $userId)->get();

    $stats = [
        'total'       => $tasks->count(),
        'completed'   => $tasks->where('status', 'completed')->count(),
        'in_progress' => $tasks->where('status', 'in_progress')->count(),
        'overdue'     => $tasks->where('status', '!=', 'completed')
                               ->filter(fn($t) => $t->due_date && $t->due_date->isPast())
                               ->count(),
        'priority' => [
            'high'   => $tasks->where('priority', 'high')->count(),
            'medium' => $tasks->where('priority', 'medium')->count(),
            'low'    => $tasks->where('priority', 'low')->count(),
        ],
        'categories' => \App\Models\Category::where('user_id', $userId)
            ->withCount('tasks')
            ->get()
            ->map(fn($c) => ['name' => $c->name, 'color' => $c->color, 'tasks_count' => $c->tasks_count])
            ->toArray(),
    ];

    $upcomingTasks = \App\Models\Task::where('user_id', $userId)
        ->where('status', '!=', 'completed')
        ->whereNotNull('due_date')
        ->orderBy('due_date')
        ->limit(5)
        ->get();

    return view('dashboard', compact('stats', 'upcomingTasks'));
})->name('dashboard');

// ======= TASKS =======
Route::get('/tasks',                          [WebTaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/trash',                    [WebTaskController::class, 'trash'])->name('tasks.trash');
Route::get('/tasks/create',                   [WebTaskController::class, 'create'])->name('tasks.create');
Route::post('/tasks',                         [WebTaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}/edit',              [WebTaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{task}',                   [WebTaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}',                [WebTaskController::class, 'destroy'])->name('tasks.destroy');
Route::patch('/tasks/{task}/toggle',          [WebTaskController::class, 'toggle'])->name('tasks.toggle');
Route::patch('/tasks/{id}/restore',           [WebTaskController::class, 'restore'])->name('tasks.restore');
Route::delete('/tasks/{id}/force-delete',     [WebTaskController::class, 'forceDelete'])->name('tasks.force-delete');
Route::delete('/tasks-trash/empty',           [WebTaskController::class, 'forceDeleteAll'])->name('tasks.force-delete-all');

// ======= CATEGORIES =======
Route::get('/categories',                  [WebCategoryController::class, 'index'])->name('categories.index');
Route::post('/categories',                 [WebCategoryController::class, 'store'])->name('categories.store');
Route::put('/categories/{category}',       [WebCategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}',    [WebCategoryController::class, 'destroy'])->name('categories.destroy');
