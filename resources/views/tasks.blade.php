<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Task Manager - Rutorika Sortable</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;600;700&display=swap"
        rel="stylesheet"
    >

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"
    >

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .sortable-ghost {
            opacity: 0.4;
            background-color: #f3f4f6;
            border: 2px dashed #a8a29e;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen py-10 px-4">

<div class="max-w-6xl mx-auto">

    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 mb-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fa-solid fa-list-check text-indigo-600 mr-2"></i>
                    Task Manager
                </h1>

                <p class="text-gray-500 mt-1">
                    Manage, filter and reorder your tasks.
                </p>
            </div>

            <a
                href="{{ route('tasks.dashboard') }}"
                class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-lg font-medium transition"
            >
                <i class="fa-solid fa-chart-column mr-2"></i>
                Analytics Dashboard
            </a>

        </div>

    </div>


    <!-- Success Message -->
    @if(session('success'))

        <div class="bg-green-100 border border-green-300 text-green-700 px-5 py-4 rounded-xl mb-6">
            <i class="fa-solid fa-circle-check mr-2"></i>
            {{ session('success') }}
        </div>

    @endif


    <!-- Validation Errors -->
    @if($errors->any())

        <div class="bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-xl mb-6">

            <div class="font-semibold mb-2">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                Please fix the following errors:
            </div>

            <ul class="list-disc ml-6 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    <!-- Add Task -->
    <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 mb-6">

        <h2 class="text-xl font-semibold text-gray-800 mb-5">
            <i class="fa-solid fa-plus-circle text-indigo-600 mr-2"></i>
            Add New Task
        </h2>

        <form
            action="{{ route('tasks.store') }}"
            method="POST"
        >

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                <!-- Task Title -->
                <div class="md:col-span-6">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Task Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        placeholder="Enter task title..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >

                </div>


                <!-- Priority -->
                <div class="md:col-span-3">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Priority
                    </label>

                    <select
                        name="priority"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="low">Low</option>

                        <option
                            value="medium"
                            selected
                        >
                            Medium
                        </option>

                        <option value="high">High</option>
                    </select>

                </div>


                <!-- Add Button -->
                <div class="md:col-span-3 flex items-end">

                    <button
                        type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-lg font-medium transition"
                    >
                        <i class="fa-solid fa-plus mr-2"></i>
                        Add Task
                    </button>

                </div>

            </div>

        </form>

    </div>


    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 mb-6">

        <div class="flex items-center justify-between mb-5">

            <h2 class="text-xl font-semibold text-gray-800">
                <i class="fa-solid fa-filter text-indigo-600 mr-2"></i>
                Search & Filters
            </h2>

            <a
                href="{{ route('tasks.index') }}"
                class="text-sm text-indigo-600 hover:text-indigo-800 font-medium"
            >
                <i class="fa-solid fa-rotate-left mr-1"></i>
                Clear Filters
            </a>

        </div>

        <form
            action="{{ route('tasks.index') }}"
            method="GET"
        >

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                <!-- Search -->
                <div class="md:col-span-5">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Search Task
                    </label>

                    <div class="relative">

                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-4 text-gray-400"></i>

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search by task title..."
                            class="w-full border border-gray-300 rounded-lg pl-11 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >

                    </div>

                </div>


                <!-- Priority -->
                <div class="md:col-span-3">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Priority
                    </label>

                    <select
                        name="priority"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >

                        <option value="">All Priorities</option>

                        <option
                            value="high"
                            {{ $priority === 'high' ? 'selected' : '' }}
                        >
                            High
                        </option>

                        <option
                            value="medium"
                            {{ $priority === 'medium' ? 'selected' : '' }}
                        >
                            Medium
                        </option>

                        <option
                            value="low"
                            {{ $priority === 'low' ? 'selected' : '' }}
                        >
                            Low
                        </option>

                    </select>

                </div>


                <!-- Status -->
                <div class="md:col-span-3">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >

                        <option value="">All Statuses</option>

                        <option
                            value="pending"
                            {{ $status === 'pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="in_progress"
                            {{ $status === 'in_progress' ? 'selected' : '' }}
                        >
                            In Progress
                        </option>

                        <option
                            value="completed"
                            {{ $status === 'completed' ? 'selected' : '' }}
                        >
                            Completed
                        </option>

                    </select>

                </div>


                <!-- Filter Button -->
                <div class="md:col-span-1 flex items-end">

                    <button
                        type="submit"
                        class="w-full bg-gray-800 hover:bg-gray-900 text-white px-4 py-3 rounded-lg font-medium transition"
                    >
                        <i class="fa-solid fa-filter"></i>
                    </button>

                </div>

            </div>

        </form>

    </div>


    <!-- Task List -->
    <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">

            <div>

                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fa-solid fa-list text-indigo-600 mr-2"></i>
                    Tasks
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Drag tasks using the handle to change their position.
                </p>

            </div>

            <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-lg text-sm font-medium">
                {{ $tasks->count() }} task(s) found
            </div>

        </div>


        <ul
            id="sortable-list"
            class="space-y-3"
        >

            @forelse($tasks as $task)

                <li
                    class="group bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition"
                    data-id="{{ $task->id }}"
                >

                    <div class="flex items-center gap-3">

                        <!-- Drag Handle -->
                        <span
                            class="drag-handle cursor-grab active:cursor-grabbing text-gray-300 group-hover:text-indigo-400 transition p-2"
                            title="Drag to reorder"
                        >
                            <i class="fa-solid fa-grip-vertical text-lg"></i>
                        </span>


                        <!-- Position -->
                        <div class="hidden sm:flex w-8 h-8 items-center justify-center bg-gray-100 rounded-full text-gray-500 text-sm font-semibold">
                            {{ $loop->iteration }}
                        </div>


                        <!-- Task Title -->
                        <div class="flex-1 min-w-0">

                            <div class="font-semibold text-gray-800 truncate">
                                {{ $task->title }}
                            </div>

                            <div class="text-xs text-gray-400 mt-1">
                                Position: {{ $task->position }}
                            </div>

                        </div>


                        <!-- Priority -->
                        <div>

                            <select
                                class="priority-select text-xs font-semibold rounded-full px-3 py-2 border-0 focus:ring-2 focus:ring-indigo-400
                                @if($task->priority === 'high')
                                    bg-red-100 text-red-700
                                @elseif($task->priority === 'medium')
                                    bg-yellow-100 text-yellow-700
                                @else
                                    bg-green-100 text-green-700
                                @endif"
                                data-id="{{ $task->id }}"
                            >

                                <option
                                    value="high"
                                    {{ $task->priority === 'high' ? 'selected' : '' }}
                                >
                                    High
                                </option>

                                <option
                                    value="medium"
                                    {{ $task->priority === 'medium' ? 'selected' : '' }}
                                >
                                    Medium
                                </option>

                                <option
                                    value="low"
                                    {{ $task->priority === 'low' ? 'selected' : '' }}
                                >
                                    Low
                                </option>

                            </select>

                        </div>


                        <!-- Status -->
                        <div>

                            <select
                                class="status-select text-xs font-semibold rounded-lg px-3 py-2 border border-gray-200 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-indigo-400"
                                data-id="{{ $task->id }}"
                            >

                                <option
                                    value="pending"
                                    {{ $task->status === 'pending' ? 'selected' : '' }}
                                >
                                    Pending
                                </option>

                                <option
                                    value="in_progress"
                                    {{ $task->status === 'in_progress' ? 'selected' : '' }}
                                >
                                    In Progress
                                </option>

                                <option
                                    value="completed"
                                    {{ $task->status === 'completed' ? 'selected' : '' }}
                                >
                                    Completed
                                </option>

                            </select>

                        </div>

                    </div>

                </li>

            @empty

                <li class="bg-blue-50 border-l-4 border-blue-400 p-5 rounded-md">

                    <div class="flex items-center">

                        <i class="fa-solid fa-circle-info text-blue-400 mr-3"></i>

                        <div>
                            <p class="text-sm text-blue-700 font-medium">
                                No tasks found.
                            </p>

                            <p class="text-xs text-blue-600 mt-1">
                                Try changing your search or filters.
                            </p>
                        </div>

                    </div>

                </li>

            @endforelse

        </ul>

    </div>

</div>


<script
    type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/toastify-js"
></script>


<script>

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');


    function showToast(message, type = 'success')
    {
        Toastify({
            text: message,
            duration: 3000,
            gravity: 'bottom',
            position: 'right',

            style: {
                background: type === 'success'
                    ? '#10B981'
                    : '#EF4444',

                color: '#fff',
                borderRadius: '8px',
                fontFamily: "'Poppins', sans-serif"
            },

            stopOnFocus: true

        }).showToast();
    }


    /*
    |--------------------------------------------------------------------------
    | Drag & Drop Sorting
    |--------------------------------------------------------------------------
    */

    document.addEventListener('DOMContentLoaded', function () {

        const sortableList = document.getElementById('sortable-list');

        if (sortableList) {

            Sortable.create(sortableList, {

                animation: 250,

                handle: '.drag-handle',

                ghostClass: 'sortable-ghost',

                dragClass: 'cursor-grabbing',

                onEnd: function (evt) {

                    const itemEl = evt.item;

                    const id = itemEl.getAttribute('data-id');

                    let type = 'moveAfter';

                    let positionEntityId = null;


                    if (evt.newIndex === 0) {

                        type = 'moveBefore';

                        const nextEl = itemEl.nextElementSibling;

                        positionEntityId = nextEl
                            ? nextEl.getAttribute('data-id')
                            : null;

                    } else {

                        const prevEl = itemEl.previousElementSibling;

                        positionEntityId = prevEl
                            ? prevEl.getAttribute('data-id')
                            : null;
                    }


                    if (!positionEntityId) {

                        showToast(
                            'Task position could not be updated.',
                            'error'
                        );

                        return;
                    }


                    fetch("{{ route('sort') }}", {

                        method: 'POST',

                        headers: {

                            'Content-Type': 'application/json',

                            'X-CSRF-TOKEN': csrfToken,

                            'Accept': 'application/json'

                        },

                        body: JSON.stringify({

                            type: type,

                            entityName: 'tasks',

                            id: id,

                            positionEntityId: positionEntityId

                        })

                    })

                    .then(response => {

                        if (response.ok) {

                            showToast(
                                '✅ Task position saved successfully!'
                            );

                        } else {

                            showToast(
                                '❌ Task position could not be saved.',
                                'error'
                            );

                        }

                    })

                    .catch(error => {

                        console.error(error);

                        showToast(
                            '❌ Something went wrong.',
                            'error'
                        );

                    });

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Priority & Status Update
        |--------------------------------------------------------------------------
        */

        const prioritySelects =
            document.querySelectorAll('.priority-select');

        const statusSelects =
            document.querySelectorAll('.status-select');


        function updateTask(taskId, priority, status, selectElement)
        {

            fetch(`/tasks/${taskId}`, {

                method: 'PATCH',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': csrfToken,

                    'Accept': 'application/json'

                },

                body: JSON.stringify({

                    priority: priority,

                    status: status

                })

            })

            .then(response => {

                if (!response.ok) {
                    throw new Error('Update failed.');
                }

                return response.json();

            })

            .then(data => {

                if (data.success) {

                    showToast(
                        '✅ Task updated successfully!'
                    );

                }

            })

            .catch(error => {

                console.error(error);

                showToast(
                    '❌ Task update failed.',
                    'error'
                );

            });

        }


        prioritySelects.forEach(select => {

            select.addEventListener('change', function () {

                const taskId = this.dataset.id;

                const taskRow = this.closest('li');

                const statusSelect =
                    taskRow.querySelector('.status-select');

                updateTask(
                    taskId,
                    this.value,
                    statusSelect.value,
                    this
                );

            });

        });


        statusSelects.forEach(select => {

            select.addEventListener('change', function () {

                const taskId = this.dataset.id;

                const taskRow = this.closest('li');

                const prioritySelect =
                    taskRow.querySelector('.priority-select');

                updateTask(
                    taskId,
                    prioritySelect.value,
                    this.value,
                    this
                );

            });

        });

    });

</script>

</body>
</html>