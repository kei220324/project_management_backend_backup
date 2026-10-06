<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

use App\Models\Project;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, Project $project)
    {
       $task = $project->tasks()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
            'is_done' => false,
            
        ]);
    
        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */public function update(UpdateTaskRequest $request, Task $task)
    {
       $task->update($request->validated());
    
        return response()->json($task);
    }
    /**
     * Remove the specified resource from storage.
     */

    
    public function toggle(Task $task)
    {
        $task->update([
            'is_done' => !$task->is_done,
        ]);
    
        return response()->json($task);
    }

    public function destroy(Task $task)
{
    $task->delete();

    return response()->json([
        'message' => 'タスクを削除しました',
    ]);
}

    
    
}
