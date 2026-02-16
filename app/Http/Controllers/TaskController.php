<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use ValueResearch\Scaffold\Controllers\BaseController;

class TaskController extends BaseController
{
/**
* Get all tasks for the authenticated user.
*
* @OA\Get(
*     path="/api/tasks",
*     operationId="getTasks",
*     description="Retrieve all tasks for the authenticated user",
*     summary="Get tasks",
*     tags={"Tasks"},
*     security={{"sanctum": {}}},
*     responses={
*         @OA\Response(
*             response=Response::HTTP_OK,
*             description="Tasks retrieved successfully",
*             content={
*                 @OA\JsonContent(
*                     properties={
*                         @OA\Property(property="success", type="boolean", example=true),
*                         @OA\Property(property="message", type="string", example="Tasks retrieved successfully"),
*                         @OA\Property(property="data", type="array", items=@OA\Items(ref="#/components/schemas/Task"))
*                     }
*                 )
*             }
*         )
*     }
* )
*/
public function index(): JsonResponse
{
$tasks = auth()->user()->tasks; // Get tasks for the authenticated user
return response()->json([
'success' => true,
'message' => 'Tasks retrieved successfully',
'data' => $tasks
], Response::HTTP_OK);
}

/**
* Create a new task for the authenticated user.
*
* @OA\Post(
*     path="/api/tasks",
*     operationId="createTask",
*     description="Create a new task for the authenticated user",
*     summary="Create task",l
*     tags={"Tasks"},
*     security={{"sanctum": {}}},
*     requestBody={
*         @OA\RequestBody(
*             required=true,
*             content={
*                 @OA\JsonContent(
*                     required={"title", "description", "deadline"},
*                     properties={
*                         @OA\Property(property="title", type="string", example="New Task Title"),
*                         @OA\Property(property="description", type="string", example="Task Description"),
*                         @OA\Property(property="deadline", type="string", format="date", example="2025-12-31")
*                     }
*                 )
*             }
*         )
*     },
*     responses={
*         @OA\Response(
*             response=Response::HTTP_CREATED,
*             description="Task created successfully",
*             content={
*                 @OA\JsonContent(
*                     properties={
*                         @OA\Property(property="success", type="boolean", example=true),
*                         @OA\Property(property="message", type="string", example="Task created successfully"),
*                         @OA\Property(property="data", ref="#/components/schemas/Task")
*                     }
*                 )
*             }
*         )
*     }
* )
*/
public function store(Request $request): JsonResponse
{
    print_r("Dfsfs");
    die;
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'deadline' => 'required|date',
    ]);


// Create the task and associate it with the authenticated user
$task = auth()->user()->tasks()->create($validated);

return response()->json([
'success' => true,
'message' => 'Task created successfully',
'data' => $task
], Response::HTTP_CREATED);
}

/**
* Update an existing task.
*
* @OA\Put(
*     path="/api/tasks/{task}",
*     operationId="updateTask",
*     description="Update a task for the authenticated user",
*     summary="Update task",
*     tags={"Tasks"},
*     security={{"sanctum": {}}},
*     @OA\Parameter(
*         name="task",
*         in="path",
*         required=true,
*         description="ID of the task to update",
*         @OA\Schema(type="integer")
*     ),
*     requestBody={
*         @OA\RequestBody(
*             required=true,
*             content={
*                 @OA\JsonContent(
*                     required={"title", "description", "deadline"},
*                     properties={
*                         @OA\Property(property="title", type="string", example="Updated Task Title"),
*                         @OA\Property(property="description", type="string", example="Updated Task Description"),
*                         @OA\Property(property="deadline", type="string", format="date", example="2026-01-01")
*                     }
*                 )
*             }
*         )
*     },
*     responses={
*         @OA\Response(
*             response=Response::HTTP_OK,
*             description="Task updated successfully",
*             content={
*                 @OA\JsonContent(
*                     properties={
*                         @OA\Property(property="success", type="boolean", example=true),
*                         @OA\Property(property="message", type="string", example="Task updated successfully"),
*                         @OA\Property(property="data", ref="#/components/schemas/Task")
*                     }
*                 )
*             }
*         )
*     }
* )
*/
public function update(Request $request, Task $task): JsonResponse
{
$this->authorize('update', $task); // Ensure the user is authorized to update the task

$validated = $request->validate([
'title' => 'required|string|max:255',
'description' => 'required|string',
'deadline' => 'required|date',
]);

$task->update($validated);

return response()->json([
'success' => true,
'message' => 'Task updated successfully',
'data' => $task
], Response::HTTP_OK);
}

/**
* Mark a task as completed.
*
* @OA\Put(
*     path="/api/tasks/{task}/complete",
*     operationId="completeTask",
*     description="Mark a task as completed",
*     summary="Complete task",
*     tags={"Tasks"},
*     security={{"sanctum": {}}},
*     @OA\Parameter(
*         name="task",
*         in="path",
*         required=true,
*         description="ID of the task to mark as completed",
*         @OA\Schema(type="integer")
*     ),
*     responses={
*         @OA\Response(
*             response=Response::HTTP_OK,
*             description="Task marked as completed",
*             content={
*                 @OA\JsonContent(
*                     properties={
*                         @OA\Property(property="success", type="boolean", example=true),
*                         @OA\Property(property="message", type="string", example="Task completed successfully"),
*                         @OA\Property(property="data", ref="#/components/schemas/Task")
*                     }
*                 )
*             }
*         )
*     }
* )
*/
public function complete(Task $task): JsonResponse
{
$this->authorize('update', $task);

$task->update(['status' => 'completed']);

return response()->json([
'success' => true,
'message' => 'Task completed successfully',
'data' => $task
], Response::HTTP_OK);
}

public function destroy(Task $task): JsonResponse
{
// Ensure the task belongs to the authenticated user
$this->authorize('delete', $task); // Check if user is authorized to delete the task

// Delete the task
$task->delete();

// Return success response
return response()->json([
'success' => true,
'message' => 'Task deleted successfully',
], Response::HTTP_OK);
}
}
