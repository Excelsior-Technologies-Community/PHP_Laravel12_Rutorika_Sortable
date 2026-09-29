<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display task manager with search and filters.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $priority = $request->input('priority');
        $status = $request->input('status');

        $query = Task::query();

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $tasks = $query->orderBy('position')->get();

        return view('tasks', compact(
            'tasks',
            'search',
            'priority',
            'status'
        ));
    }

    /**
     * Store a new task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
        ]);

        Task::create([
            'title' => $validated['title'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task added successfully.');
    }

    /**
     * Update task priority and status.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'task' => $task,
        ]);
    }

    /**
     * Display task analytics dashboard.
     */
    public function dashboard()
    {
        $totalTasks = Task::count();

        $pendingTasks = Task::where('status', 'pending')->count();

        $inProgressTasks = Task::where('status', 'in_progress')->count();

        $completedTasks = Task::where('status', 'completed')->count();

        $highPriorityTasks = Task::where('priority', 'high')->count();

        $mediumPriorityTasks = Task::where('priority', 'medium')->count();

        $lowPriorityTasks = Task::where('priority', 'low')->count();

        $averagePosition = round(
            Task::avg('position') ?? 0,
            2
        );

        $recentTasks = Task::orderByDesc('updated_at')
            ->limit(8)
            ->get();

        $priorityStats = [
            'high' => $highPriorityTasks,
            'medium' => $mediumPriorityTasks,
            'low' => $lowPriorityTasks,
        ];

        $statusStats = [
            'pending' => $pendingTasks,
            'in_progress' => $inProgressTasks,
            'completed' => $completedTasks,
        ];

        return view('dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'highPriorityTasks',
            'mediumPriorityTasks',
            'lowPriorityTasks',
            'averagePosition',
            'recentTasks',
            'priorityStats',
            'statusStats'
        ));
    }
}