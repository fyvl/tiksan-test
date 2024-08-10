<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        try {
            $tasks = Task::all();
            return response()->json($tasks);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching tasks'], 500);
        }
    }

    public function show($id)
    {
        try {
            $task = Task::find($id);

            if (!$task) {
                return response()->json(['error' => 'Task not found'], 404);
            }

            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the task'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_completed' => 'boolean',
            ]);

            $task = Task::create($request->all());

            return response()->json($task, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating a task'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $task = Task::find($id);

            if (!$task) {
                return response()->json(['error' => 'Task not found'], 404);
            }

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_completed' => 'boolean',
            ]);

            $task->update($request->all());

            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the task'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::find($id);

            if (!$task) {
                return response()->json(['error' => 'Task not found'], 404);
            }

            $task->delete();

            return response()->json(['message' => 'Task deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the task'], 500);
        }
    }
}
