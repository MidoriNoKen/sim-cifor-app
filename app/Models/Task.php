<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name', 'assigned_user', 'start_date', 'end_date', 'priority', 'description', 'status'
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_tasks', 'task_id', 'project_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user', 'id');
    }
}