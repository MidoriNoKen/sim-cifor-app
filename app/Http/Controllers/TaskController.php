<?php

namespace App\Http\Controllers;

use App\Enums\PriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Services\ProjectService;
use App\Http\Services\TaskService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    private $taskService, $userService, $projectService, $loggedRole;

    public function __construct(TaskService $taskService, UserService $userService, ProjectService $projectService)
    {
        $this->taskService = $taskService;
        $this->userService = $userService;
        $this->projectService = $projectService;
        $this->loggedRole = $userService->getLoggedRole();
    }

    public function index()
    {
        $loggedRole = $this->loggedRole;
        $tasks = $this->taskService->getAll();
        return Inertia::render('Task/Index')->with(['loggedRole' => $loggedRole, 'tasks' => $tasks]);
    }

    public function create()
    {
        $users = $this->userService->getAll();
        $projects = $this->projectService->getAll();
        $priorities = PriorityEnum::PRIORITY;
        return Inertia::render('Task/Create')->with(['users' => $users, 'priorities' => $priorities, 'projects' => $projects]);
    }

    public function store(Request $request)
    {
        $this->taskService->store($request);
        return Inertia::location(route('tasks.index'));
    }

    public function show($id)
    {
        $task = $this->taskService->show($id);
        $project = $this->projectService->show($task->project_id);
        $user = $this->userService->getUserByIdWithRole($task->assigned_user);
        return Inertia::render('Task/Show', ['task' => $task, 'project' => $project, 'user' => $user]);
    }

    public function edit($id)
    {
        $task = $this->taskService->getByIdWithPM($id);
        $pms = $this->taskService->getTaskManager();
        return Inertia::render('Task/Edit', ['task' => $task, 'pms' => $pms]);
    }

    public function update(Request $request, $id)
    {
        $this->taskService->update($request, $id);
        return Inertia::location(route('tasks.index'));
    }

    public function destroy($id)
    {
        $this->taskService->delete($id);
        return Inertia::location(route('tasks.index'));
    }
}