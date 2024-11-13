<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'type',
        'status',
        'due_date',
        'assigned_to',

    ];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
    public function statusUpdates()
    {
        return $this->hasMany(TaskStatusUpdate::class);
    }

    public function dependencies()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_task_id');
    }

    // Tasks that are dependent on this task

    public function dependentTasks()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'depends_on_task_id', 'task_id');
    }
}
