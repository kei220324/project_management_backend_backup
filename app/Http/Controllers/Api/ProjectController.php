<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{

public function index(Request $request)
{
    
    $user = $request->user();
 

    $projects = $user->projects()
        ->select([
            'projects.id',
            'projects.name',
            'projects.summary',
            'projects.due_date',
           
        ])
        
        ->withTaskStats()
        ->latest('projects.id')
        ->get();

    return response()->json($projects);
}


    public function show(Project $project)
    {
        
        $project->load([
            'users',
            'tasks' => function ($query) {
                $query
                    ->select(['id', 'project_id', 'name', 'is_done', 'due_date',"status","description"])
                    ->orderBy('due_date')
                    ->orderBy('id')
                    ->withCheckItemStats();
            },
        ]);
    
        $project->loadCount([
            'tasks',
            'tasks as done_tasks_count' => function ($query) {
                $query->where('is_done', true);
            },
        ]);
    
        return response()->json($project);
    }

    public function destroy(Project $project)
    {
       
        $project->delete();

        return response()->json([
            'message' => 'project deleted',
        ]);
    }
    public function store(StoreProjectRequest $request)
    {
        // 現在ログインしているユーザーを取得
        $user = $request->user();
    
        // プロジェクトを作成
        $project = Project::create($request->validated());
    
        // プロジェクトを作成したユーザーを
        // プロジェクトの担当者として登録
        $user->projects()->attach($project->id);
    
        return response()->json($project, 201);
    }

public function update(UpdateProjectRequest $request, Project $project)
{
    $project->update($request->validated());

    return response()->json($project);
}









}

