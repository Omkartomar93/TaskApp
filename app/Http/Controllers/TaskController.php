<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
class TaskController extends Controller
{
    // Get all tasks
    public function view()
    {

        return view('welcome');
    }
    public function index()
    {
        return Task::all();
    }

    // Store a new task
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tasks,name',
        ]);

        $task = new Task;
        $task->name = $request->name;
        $task->save();

        return response()->json($task);
    }

    // Mark task as completed
    public function toggleComplete(Task $task)
    {
        $task->completed = !$task->completed;
        $task->save();

        return response()->json($task);
    }

    // Delete a task
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['success' => true]);
    }
}
