<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserTask extends Pivot
{
    protected $fillable = [
        'task_id', 'user_id'
    ];
}