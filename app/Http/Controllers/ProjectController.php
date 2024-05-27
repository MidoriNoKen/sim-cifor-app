<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Services\ProjectService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    private $projectService, $userService;

    public function __construct(ProjectService $projectService, UserService $userService)
    {
        $this->projectService = $projectService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 5);
        $projects = $this->projectService->getAllWithPM($page, $perPage);
        return Inertia::render('Project/Index')->with(['projects' => $projects]);
    }

    public function create()
    {
        $pms = $this->userService->getProjectManager();
        return Inertia::render('Project/Create')->with('pms', $pms);
    }

    public function store(Request $request)
    {
        $this->projectService->store($request);
        return Inertia::location(route('projects.index'));
    }

    public function show($id)
    {
        $project = $this->projectService->show($id);
        $pm = $project->projectManager;
        return Inertia::render('Project/Show', ['project' => $project, 'pm' => $pm]);
    }

    public function edit($id)
    {
        $project = $this->projectService->getByIdWithPM($id);
        $pms = $this->userService->getProjectManager();
        $statuses = ProjectStatusEnum::STATUSES;
        return Inertia::render('Project/Edit', ['project' => $project, 'pms' => $pms, 'statuses' => $statuses]);
    }

    public function update(Request $request, $id)
    {
        $this->projectService->update($request, $id);
        return Inertia::location(route('projects.index'));
    }

    public function destroy($id)
    {
        $this->projectService->delete($id);
        return Inertia::location(route('projects.index'));
    }
}