<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name', 'pm_id', 'start_date', 'end_date', 'description', 'status'
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'project_tasks', 'project_id', 'task_id');
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'pm_id', 'id');
    }
}