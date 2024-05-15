<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectTask extends Pivot
{
    protected $fillable = [
        'project_id', 'task_id'
    ];
}