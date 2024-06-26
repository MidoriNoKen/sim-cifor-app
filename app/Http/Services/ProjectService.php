<?php

namespace App\Http\Services;

use App\Enums\ProjectStatusEnum;
use App\Models\Project;
use Exception;

class ProjectService
{
    public function getAll() {
        return Project::all();
    }

    public function getAllWithPM($page, $perPage) {
        $projects = Project::query()->paginate($perPage, ['*'], 'page', $page);
        foreach ($projects as $project) {
            $project->pm = $project->projectManager->name;
        }
        return $projects;
    }

    public function getByIdWithPM($id) {
        $project = Project::find($id);
        $project->pm = $project->projectManager->name;
        return $project;
    }

    public function show($id) {
        return Project::with('tasks', 'projectManager')->find($id);
    }

    public function store($request) {
        try {
            $request->validate([
                'name' => 'required',
                'pm_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'description' => 'required',
            ]);

            $project = Project::create([
                'name' => $request->name,
                'pm_id' => $request->pm_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
                'status' => ProjectStatusEnum::BACKLOG,
            ]);

            if (!$project) {
                Throw new Exception("An error occurred while storing the project.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function update($request, $id) {
        try {
            $request->validate([
                'name' => 'required',
                'pm_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'description' => 'required',
                'status' => 'required',
            ]);

            $project = Project::find($id);
            $project->name = $request->name;
            $project->pm_id = $request->pm_id;
            $project->start_date = $request->start_date;
            $project->end_date = $request->end_date;
            $project->description = $request->description;
            $project->status = $request->status;
            $project->save();

            if (!$project) {
                Throw new Exception("An error occurred while updating the project.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $project = Project::find($id);
            $project->delete();

            if (!$project) {
                Throw new Exception("An error occurred while deleting the project.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}