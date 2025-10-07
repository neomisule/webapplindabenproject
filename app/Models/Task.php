<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'due_date'
    ];

     public function volunteers()
         {
        return $this->belongsToMany(User::class, 'task_assignments', 'task_id', 'user_id')
            ->withPivot('status', 'assigned_by')
            ->withTimestamps();
    }
    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_assignments')
            ->withPivot('status', 'notes')
            ->withTimestamps();
    }

    public function completedAssignmentsCount()
    {
        return $this->assignments()->where('status', 'completed')->count();
    }

    public function pendingAssignmentsCount()
    {
        return $this->assignments()->where('status', '!=', 'completed')->count();
    }

    public function getAssignableUsers()
    {
        $roleIds = $this->assignedRoles->pluck('id');
        return User::whereHas('roles', function ($query) use ($roleIds) {
            $query->whereIn('role_id', $roleIds);
        })->get();
    }

    public function ngos()
{
    return $this->belongsToMany(Ngo::class, 'ngo_task', 'task_id', 'ngo_id');
}

}
