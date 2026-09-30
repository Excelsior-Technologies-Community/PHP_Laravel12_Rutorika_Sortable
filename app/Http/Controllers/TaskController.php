<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TaskController extends Controller
{
    /**
     * Display task manager with search, filters, sorting and pagination.
     */
    public function index(Request $request)
    {
        $query = $this->filteredQuery($request);

        $tasks = $query
            ->paginate(5)
            ->withQueryString();

        $filteredCount = (clone $query)->count();

        $filteredCompleted = (clone $query)
            ->where('status', 'completed')
            ->count();

        $filteredPending = (clone $query)
            ->where('status', 'pending')
            ->count();

        $filteredInProgress = (clone $query)
            ->where('status', 'in_progress')
            ->count();

        $completionRate = $filteredCount > 0
            ? round(($filteredCompleted / $filteredCount) * 100, 1)
            : 0;

        return view('tasks', [
            'tasks' => $tasks,
            'search' => $request->input('search'),
            'priority' => $request->input('priority'),
            'status' => $request->input('status'),
            'sort' => $request->input('sort', 'position'),
            'direction' => $request->input('direction', 'asc'),
            'filteredCount' => $filteredCount,
            'filteredCompleted' => $filteredCompleted,
            'filteredPending' => $filteredPending,
            'filteredInProgress' => $filteredInProgress,
            'completionRate' => $completionRate,
        ]);
    }


    /**
     * Build filtered and sorted task query.
     */
    private function filteredQuery(Request $request)
    {
        $search = $request->input('search');
        $priority = $request->input('priority');
        $status = $request->input('status');

        $sort = $request->input('sort', 'position');

        $direction = strtolower(
            $request->input('direction', 'asc')
        );

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $allowedSorts = [
            'position',
            'title',
            'priority',
            'status',
            'created_at',
            'updated_at',
        ];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'position';
        }

        $query = Task::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search) {
            $query->where(
                'title',
                'like',
                '%' . $search . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Priority Filter
        |--------------------------------------------------------------------------
        */

        if ($priority) {
            $query->where(
                'priority',
                $priority
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($status) {
            $query->where(
                'status',
                $status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Custom Priority Sorting
        |--------------------------------------------------------------------------
        */

        if ($sort === 'priority') {

            $priorityOrder = $direction === 'asc'
                ? "CASE
                    WHEN priority = 'low' THEN 1
                    WHEN priority = 'medium' THEN 2
                    WHEN priority = 'high' THEN 3
                   END"
                : "CASE
                    WHEN priority = 'high' THEN 1
                    WHEN priority = 'medium' THEN 2
                    WHEN priority = 'low' THEN 3
                   END";

            $query->orderByRaw($priorityOrder);

        }

        /*
        |--------------------------------------------------------------------------
        | Custom Status Sorting
        |--------------------------------------------------------------------------
        */

        elseif ($sort === 'status') {

            $statusOrder = $direction === 'asc'
                ? "CASE
                    WHEN status = 'pending' THEN 1
                    WHEN status = 'in_progress' THEN 2
                    WHEN status = 'completed' THEN 3
                   END"
                : "CASE
                    WHEN status = 'completed' THEN 1
                    WHEN status = 'in_progress' THEN 2
                    WHEN status = 'pending' THEN 3
                   END";

            $query->orderByRaw($statusOrder);

        }

        else {

            $query->orderBy(
                $sort,
                $direction
            );

        }

        return $query;
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

        $lastPosition = Task::max('position') ?? 0;

        Task::create([
            'title' => $validated['title'],
            'priority' => $validated['priority'],
            'status' => 'pending',
            'position' => $lastPosition + 1,
        ]);

        return redirect()
            ->route('tasks.index')
            ->with(
                'success',
                'Task added successfully.'
            );
    }


    /**
     * Update task title, priority and status.
     */
    public function update(
        Request $request,
        Task $task
    ) {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'task' => $task->fresh(),
        ]);
    }


    /**
     * Delete one task.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with(
                'success',
                'Task deleted successfully.'
            );
    }


    /**
     * Duplicate a task.
     */
    public function duplicate(Task $task)
    {
        $newTask = $task->replicate();

        $newTask->title = $task->title . ' Copy';

        $newTask->position = (Task::max('position') ?? 0) + 1;

        $newTask->status = 'pending';

        $newTask->save();

        return redirect()
            ->route('tasks.index')
            ->with(
                'success',
                'Task duplicated successfully.'
            );
    }


    /**
     * Bulk delete tasks.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'integer|exists:tasks,id',
        ]);

        $count = Task::whereIn(
            'id',
            $validated['task_ids']
        )->delete();

        return redirect()
            ->route('tasks.index')
            ->with(
                'success',
                $count . ' task(s) deleted successfully.'
            );
    }


    /**
     * Bulk status update.
     */
    public function bulkStatus(Request $request)
    {
        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'integer|exists:tasks,id',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $count = Task::whereIn(
            'id',
            $validated['task_ids']
        )->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('tasks.index')
            ->with(
                'success',
                $count . ' task(s) status updated successfully.'
            );
    }


    /**
     * Bulk priority update.
     */
    public function bulkPriority(Request $request)
    {
        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'integer|exists:tasks,id',
            'priority' => 'required|in:low,medium,high',
        ]);

        $count = Task::whereIn(
            'id',
            $validated['task_ids']
        )->update([
            'priority' => $validated['priority'],
        ]);

        return redirect()
            ->route('tasks.index')
            ->with(
                'success',
                $count . ' task(s) priority updated successfully.'
            );
    }


    /**
     * Export filtered tasks as CSV.
     */
    public function export(Request $request)
    {
        $tasks = $this->filteredQuery($request)->get();

        $filename =
            'tasks-' .
            now()->format('Y-m-d-H-i-s') .
            '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' =>
                'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($tasks) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID',
                'Title',
                'Position',
                'Priority',
                'Status',
                'Created At',
                'Updated At',
            ]);

            foreach ($tasks as $task) {

                fputcsv($file, [
                    $task->id,
                    $task->title,
                    $task->position,
                    ucfirst($task->priority),
                    ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $task->status
                        )
                    ),
                    optional($task->created_at)
                        ->format('Y-m-d H:i:s'),
                    optional($task->updated_at)
                        ->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream(
            $callback,
            200,
            $headers
        );
    }


    /**
     * Display task analytics dashboard.
     */
    public function dashboard()
    {
        $totalTasks = Task::count();

        $pendingTasks = Task::where(
            'status',
            'pending'
        )->count();

        $inProgressTasks = Task::where(
            'status',
            'in_progress'
        )->count();

        $completedTasks = Task::where(
            'status',
            'completed'
        )->count();

        $highPriorityTasks = Task::where(
            'priority',
            'high'
        )->count();

        $mediumPriorityTasks = Task::where(
            'priority',
            'medium'
        )->count();

        $lowPriorityTasks = Task::where(
            'priority',
            'low'
        )->count();

        $averagePosition = round(
            Task::avg('position') ?? 0,
            2
        );

        $highestPosition = Task::max('position') ?? 0;

        $oldestTask = Task::orderBy(
            'created_at',
            'asc'
        )->first();

        $latestTask = Task::orderBy(
            'created_at',
            'desc'
        )->first();

        $recentTasks = Task::orderByDesc(
            'updated_at'
        )
            ->limit(8)
            ->get();

        $completedRate = $totalTasks > 0
            ? round(
                ($completedTasks / $totalTasks) * 100,
                1
            )
            : 0;

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

        return view(
            'dashboard',
            compact(
                'totalTasks',
                'pendingTasks',
                'inProgressTasks',
                'completedTasks',
                'highPriorityTasks',
                'mediumPriorityTasks',
                'lowPriorityTasks',
                'averagePosition',
                'highestPosition',
                'oldestTask',
                'latestTask',
                'recentTasks',
                'completedRate',
                'priorityStats',
                'statusStats'
            )
        );
    }
}