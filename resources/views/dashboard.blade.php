<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Task Analytics Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    <style>

        body {
            font-family: 'Poppins', sans-serif;
        }

    </style>

</head>


<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen py-10 px-4">

<div class="max-w-7xl mx-auto">


    <!-- Header -->

    <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 mb-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <h1 class="text-3xl font-bold text-gray-800">

                    <i class="fa-solid fa-chart-column text-indigo-600 mr-2"></i>

                    Task Analytics Dashboard

                </h1>

                <p class="text-gray-500 mt-1">

                    Monitor task status, priority and sorting statistics.

                </p>

            </div>


            <a
                href="{{ route('tasks.index') }}"
                class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-lg font-medium"
            >

                <i class="fa-solid fa-list-check mr-2"></i>

                Task Manager

            </a>

        </div>

    </div>


    <!-- Statistics -->

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-6">


        <!-- Total -->

        <div class="bg-white rounded-2xl shadow-xl p-6">

            <p class="text-sm text-gray-500">
                Total Tasks
            </p>

            <h2 class="text-3xl font-bold text-indigo-600 mt-2">
                {{ $totalTasks }}
            </h2>

        </div>


        <!-- Pending -->

        <div class="bg-white rounded-2xl shadow-xl p-6">

            <p class="text-sm text-gray-500">
                Pending
            </p>

            <h2 class="text-3xl font-bold text-yellow-600 mt-2">
                {{ $pendingTasks }}
            </h2>

        </div>


        <!-- Progress -->

        <div class="bg-white rounded-2xl shadow-xl p-6">

            <p class="text-sm text-gray-500">
                In Progress
            </p>

            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                {{ $inProgressTasks }}
            </h2>

        </div>


        <!-- Completed -->

        <div class="bg-white rounded-2xl shadow-xl p-6">

            <p class="text-sm text-gray-500">
                Completed
            </p>

            <h2 class="text-3xl font-bold text-green-600 mt-2">
                {{ $completedTasks }}
            </h2>

        </div>


        <!-- Completion -->

        <div class="bg-white rounded-2xl shadow-xl p-6">

            <p class="text-sm text-gray-500">
                Completion Rate
            </p>

            <h2 class="text-3xl font-bold text-purple-600 mt-2">
                {{ $completedRate }}%
            </h2>

        </div>

    </div>


    <!-- Priority -->

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">


        <div class="bg-white rounded-2xl shadow-xl p-6">

            <p class="text-sm text-gray-500">
                High Priority
            </p>

            <p class="text-3xl font-bold text-red-600 mt-2">
                {{ $highPriorityTasks }}
            </p>

        </div>


        <div class="bg-white rounded-2xl shadow-xl p-6">

            <p class="text-sm text-gray-500">
                Medium Priority
            </p>

            <p class="text-3xl font-bold text-yellow-600 mt-2">
                {{ $mediumPriorityTasks }}
            </p>

        </div>


        <div class="bg-white rounded-2xl shadow-xl p-6">

            <p class="text-sm text-gray-500">
                Low Priority
            </p>

            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $lowPriorityTasks }}
            </p>

        </div>

    </div>


    <!-- Sorting Statistics -->

    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">

        <h2 class="text-xl font-semibold text-gray-800 mb-5">

            <i class="fa-solid fa-arrow-down-wide-short text-indigo-600 mr-2"></i>

            Sorting Statistics

        </h2>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">


            <div class="bg-indigo-50 rounded-xl p-5">

                <p class="text-sm text-indigo-600 font-medium">
                    Average Position
                </p>

                <p class="text-3xl font-bold text-indigo-800 mt-2">
                    {{ $averagePosition }}
                </p>

            </div>


            <div class="bg-purple-50 rounded-xl p-5">

                <p class="text-sm text-purple-600 font-medium">
                    Highest Position
                </p>

                <p class="text-3xl font-bold text-purple-800 mt-2">
                    {{ $highestPosition }}
                </p>

            </div>


            <div class="bg-pink-50 rounded-xl p-5">

                <p class="text-sm text-pink-600 font-medium">
                    Sortable Tasks
                </p>

                <p class="text-3xl font-bold text-pink-800 mt-2">
                    {{ $totalTasks }}
                </p>

            </div>

        </div>

    </div>


    <!-- Status Progress -->

    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">

        <h2 class="text-xl font-semibold text-gray-800 mb-6">

            <i class="fa-solid fa-chart-pie text-indigo-600 mr-2"></i>

            Task Status Overview

        </h2>


        <!-- Pending -->

        <div class="mb-5">

            <div class="flex justify-between mb-2">

                <span class="font-medium">
                    Pending
                </span>

                <span class="font-semibold text-yellow-600">
                    {{ $pendingTasks }}
                </span>

            </div>

            <div class="w-full bg-gray-200 rounded-full h-3">

                <div
                    class="bg-yellow-500 h-3 rounded-full"
                    style="width: {{ $totalTasks > 0 ? ($pendingTasks / $totalTasks) * 100 : 0 }}%"
                ></div>

            </div>

        </div>


        <!-- In Progress -->

        <div class="mb-5">

            <div class="flex justify-between mb-2">

                <span class="font-medium">
                    In Progress
                </span>

                <span class="font-semibold text-blue-600">
                    {{ $inProgressTasks }}
                </span>

            </div>

            <div class="w-full bg-gray-200 rounded-full h-3">

                <div
                    class="bg-blue-500 h-3 rounded-full"
                    style="width: {{ $totalTasks > 0 ? ($inProgressTasks / $totalTasks) * 100 : 0 }}%"
                ></div>

            </div>

        </div>


        <!-- Completed -->

        <div>

            <div class="flex justify-between mb-2">

                <span class="font-medium">
                    Completed
                </span>

                <span class="font-semibold text-green-600">
                    {{ $completedTasks }}
                </span>

            </div>

            <div class="w-full bg-gray-200 rounded-full h-3">

                <div
                    class="bg-green-500 h-3 rounded-full"
                    style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%"
                ></div>

            </div>

        </div>

    </div>


    <!-- Recent Tasks -->

    <div class="bg-white rounded-2xl shadow-xl p-6">

        <div class="flex items-center justify-between mb-5">

            <h2 class="text-xl font-semibold text-gray-800">

                <i class="fa-solid fa-clock-rotate-left text-indigo-600 mr-2"></i>

                Recent Task Activity

            </h2>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="border-b border-gray-200">

                        <th class="text-left py-3 px-3">
                            Task
                        </th>

                        <th class="text-left py-3 px-3">
                            Priority
                        </th>

                        <th class="text-left py-3 px-3">
                            Status
                        </th>

                        <th class="text-left py-3 px-3">
                            Position
                        </th>

                        <th class="text-left py-3 px-3">
                            Updated
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($recentTasks as $task)

                        <tr class="border-b border-gray-100">

                            <td class="py-4 px-3 font-medium">
                                {{ $task->title }}
                            </td>


                            <td class="py-4 px-3">

                                @if($task->priority === 'high')

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        High
                                    </span>

                                @elseif($task->priority === 'medium')

                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Medium
                                    </span>

                                @else

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Low
                                    </span>

                                @endif

                            </td>


                            <td class="py-4 px-3">

                                @if($task->status === 'pending')

                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Pending
                                    </span>

                                @elseif($task->status === 'in_progress')

                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        In Progress
                                    </span>

                                @else

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Completed
                                    </span>

                                @endif

                            </td>


                            <td class="py-4 px-3">
                                {{ $task->position }}
                            </td>


                            <td class="py-4 px-3 text-sm text-gray-500">

                                {{ $task->updated_at?->format('d M Y, h:i A') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-8 text-gray-500"
                            >

                                No task activity available.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>