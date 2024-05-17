<?php

namespace App\Http\Services;

use App\Enums\PositionEnum;
use App\Enums\TaskStatusEnum;
use App\Enums\RoleEnum;
use App\Models\ProjectTask;
use App\Models\Task;
use App\Models\Role;
use App\Models\User;
use App\Models\UserTask;
use Exception;

class TaskService
{
    public function getAll() {
        return Task::all();
    }

    public function getByIdWithPM($id) {
        $task = Task::find($id);
        $task->pm = $task->taskManager->name;
        return $task;
    }

    public function getTaskManager() {
        $admin = Role::where('name', RoleEnum::ADMIN)->value('id');
        return User::whereNotIn('role_id', [$admin])
            ->whereNotIn('position', [PositionEnum::JUNIOR])
            ->get();
    }

    public function show($id) {
        return Task::find($id);
    }

    public function store($request) {
        try {
            $request->validate([
                'name' => 'required',
                'project_id' => 'required',
                'assigned_user' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'priority' => 'required',
                'description' => 'required'
            ]);

            $task = Task::create([
                'name' => $request->name,
                'project_id' => $request->project_id,
                'assigned_user' => $request->assigned_user,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'priority' => $request->priority,
                'description' => $request->description,
                'status' => TaskStatusEnum::ASSIGNED,
            ]);

            $projectTask = ProjectTask::create([
                'project_id' => $request->project_id,
                'task_id' => $task->id
            ]);

            $userTask = UserTask::create([
                'user_id' => $request->assigned_user,
                'task_id' => $task->id
            ]);

            if (!$task || !$projectTask || !$userTask) {
                Throw new Exception("An error occurred while storing the task.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function update($request, $id) {
        try {
            $request->validate([
                'name' => 'required',
                'project_id' => 'required',
                'assigned_user' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'priority' => 'required',
                'description' => 'required',
                'status' => 'required',
            ]);

            $task = Task::find($id);
            $task->name = $request->name;
            $task->project_id = $request->project_id;
            $task->assigned_user = $request->assigned_user;
            $task->start_date = $request->start_date;
            $task->end_date = $request->end_date;
            $task->priority = $request->priority;
            $task->description = $request->description;
            $task->status = $request->status;
            $task->save();

            if (!$task) {
                Throw new Exception("An error occurred while updating the task.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $task = Task::find($id);
            $task->delete();

            if (!$task) {
                Throw new Exception("An error occurred while deleting the task.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
