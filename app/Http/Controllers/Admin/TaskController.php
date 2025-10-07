<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['assignments.user'])->orderBy('id','desc')->get();
        $volunteers = User::whereHas('roles', function($q) {
            $q->where('name', 'volunteer');
        })->get();

        return view('admin.pages.tasks.index', compact('tasks', 'volunteers'));
    }

    public function create()
    {
        $volunteers = User::whereHas('roles', function($q) {
            $q->where('name', 'volunteer');
        })->get();

        return view('admin.pages.tasks.create', compact('volunteers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id'
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'pending'
        ]);

        if ($request->has('assigned_to')) {
            foreach ($request->assigned_to as $userId) {
                TaskAssignment::create([
                    'task_id' => $task->id,
                    'user_id' => $userId,
                    'assigned_by' => auth()->id(),
                    'status' => 'pending'
                ]);
            }
        }

        return redirect()->route('admin.tasks.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Task created successfully!'
        ]);
    }

    public function edit($id)
    {
        $task = Task::with('assignments')->findOrFail($id);
        $volunteers = User::whereHas('roles', function($q) {
            $q->where('name', 'volunteer');
        })->get();

        return view('admin.pages.tasks.edit', compact('task', 'volunteers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id'
        ]);

        $task = Task::findOrFail($id);
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);

        if ($request->has('assigned_to')) {
            $task->assignments()->whereNotIn('user_id', $request->assigned_to)->delete();

            $existingAssignees = $task->assignments->pluck('user_id')->toArray();
            foreach ($request->assigned_to as $userId) {
                if (!in_array($userId, $existingAssignees)) {
                    TaskAssignment::create([
                        'task_id' => $task->id,
                        'user_id' => $userId,
                        'assigned_by' => auth()->id(),
                        'status' => 'pending'
                    ]);
                }
            }
        } else {
            $task->assignments()->delete();
        }

        return redirect()->route('admin.tasks.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Task updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('admin.tasks.index')->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Task deleted successfully!'
        ]);
    }

    public function toggleStatus(Request $request, $task)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $task = Task::findOrFail($task);
        $task->status = $request->status;
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'new_status' => $task->status
        ]);
    }

    public function assign(Request $request, $task)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        TaskAssignment::firstOrCreate([
            'task_id' => $task,
            'user_id' => $request->user_id
        ], [
            'assigned_by' => auth()->id(),
            'status' => 'pending'
        ]);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Task assigned successfully!'
        ]);
    }

    public function unassign($task, $user)
    {
        TaskAssignment::where('task_id', $task)
            ->where('user_id', $user)
            ->delete();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Task unassigned successfully!'
        ]);
    }
}
