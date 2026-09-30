<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Task Manager - Rutorika Sortable</title>

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    <link
        rel="stylesheet"
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
            border: 2px dashed #6366f1;
        }

    </style>

</head>


<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen py-10 px-4">

<div class="max-w-7xl mx-auto">


    <!-- Header -->

    <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 mb-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>

                <h1 class="text-3xl font-bold text-gray-800">

                    <i class="fa-solid fa-list-check text-indigo-600 mr-2"></i>

                    Task Manager

                </h1>

                <p class="text-gray-500 mt-1">

                    Manage, filter, sort, reorder and analyze your tasks.

                </p>

            </div>


            <div class="flex flex-wrap gap-3">

                <a
                    href="{{ route('tasks.export', request()->query()) }}"
                    class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg font-medium transition"
                >

                    <i class="fa-solid fa-file-csv mr-2"></i>

                    Export CSV

                </a>


                <a
                    href="{{ route('tasks.dashboard') }}"
                    class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-lg font-medium transition"
                >

                    <i class="fa-solid fa-chart-column mr-2"></i>

                    Analytics

                </a>

            </div>

        </div>

    </div>


    <!-- Success -->

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


    <!-- Summary -->

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

        <div class="bg-white rounded-2xl shadow-xl p-5">

            <p class="text-sm text-gray-500">
                Filtered Tasks
            </p>

            <p class="text-3xl font-bold text-indigo-600 mt-2">
                {{ $filteredCount }}
            </p>

        </div>


        <div class="bg-white rounded-2xl shadow-xl p-5">

            <p class="text-sm text-gray-500">
                Completed
            </p>

            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $filteredCompleted }}
            </p>

        </div>


        <div class="bg-white rounded-2xl shadow-xl p-5">

            <p class="text-sm text-gray-500">
                Pending / In Progress
            </p>

            <p class="text-3xl font-bold text-yellow-600 mt-2">
                {{ $filteredPending + $filteredInProgress }}
            </p>

        </div>


        <div class="bg-white rounded-2xl shadow-xl p-5">

            <p class="text-sm text-gray-500">
                Completion Rate
            </p>

            <p class="text-3xl font-bold text-purple-600 mt-2">
                {{ $completionRate }}%
            </p>

        </div>

    </div>


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

                <div class="md:col-span-7">

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


                <div class="md:col-span-3">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Priority
                    </label>

                    <select
                        name="priority"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3"
                    >

                        <option value="low">
                            Low
                        </option>

                        <option value="medium" selected>
                            Medium
                        </option>

                        <option value="high">
                            High
                        </option>

                    </select>

                </div>


                <div class="md:col-span-2 flex items-end">

                    <button
                        type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-lg font-medium"
                    >

                        <i class="fa-solid fa-plus mr-2"></i>

                        Add

                    </button>

                </div>

            </div>

        </form>

    </div>


    <!-- Search & Filters -->

    <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 mb-6">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-5">

            <h2 class="text-xl font-semibold text-gray-800">

                <i class="fa-solid fa-filter text-indigo-600 mr-2"></i>

                Search, Filters & Sorting

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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">


                <!-- Search -->

                <div class="lg:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search task title..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-3"
                    >

                </div>


                <!-- Priority -->

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Priority
                    </label>

                    <select
                        name="priority"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3"
                    >

                        <option value="">
                            All
                        </option>

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

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3"
                    >

                        <option value="">
                            All
                        </option>

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


                <!-- Sort -->

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sort By
                    </label>

                    <select
                        name="sort"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3"
                    >

                        <option value="position" {{ $sort === 'position' ? 'selected' : '' }}>
                            Position
                        </option>

                        <option value="title" {{ $sort === 'title' ? 'selected' : '' }}>
                            Title
                        </option>

                        <option value="priority" {{ $sort === 'priority' ? 'selected' : '' }}>
                            Priority
                        </option>

                        <option value="status" {{ $sort === 'status' ? 'selected' : '' }}>
                            Status
                        </option>

                        <option value="created_at" {{ $sort === 'created_at' ? 'selected' : '' }}>
                            Created Date
                        </option>

                        <option value="updated_at" {{ $sort === 'updated_at' ? 'selected' : '' }}>
                            Updated Date
                        </option>

                    </select>

                </div>


                <!-- Direction -->

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Direction
                    </label>

                    <select
                        name="direction"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3"
                    >

                        <option
                            value="asc"
                            {{ $direction === 'asc' ? 'selected' : '' }}
                        >
                            Ascending
                        </option>

                        <option
                            value="desc"
                            {{ $direction === 'desc' ? 'selected' : '' }}
                        >
                            Descending
                        </option>

                    </select>

                </div>


                <div class="lg:col-span-6">

                    <button
                        type="submit"
                        class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-lg font-medium"
                    >

                        <i class="fa-solid fa-filter mr-2"></i>

                        Apply Filters

                    </button>

                </div>

            </div>

        </form>

    </div>


    <!-- Bulk Action Area -->

    <form
        id="bulk-form"
        method="POST"
        class="bg-white rounded-2xl shadow-2xl p-6 mb-6"
    >

        @csrf

        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

            <div class="flex items-center gap-3">

                <input
                    type="checkbox"
                    id="select-all"
                    class="w-5 h-5 text-indigo-600"
                >

                <label
                    for="select-all"
                    class="font-medium text-gray-700"
                >
                    Select All
                </label>

                <span
                    id="selected-count"
                    class="text-sm bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full"
                >
                    0 selected
                </span>

            </div>


            <div class="flex flex-wrap gap-3">

                <select
                    id="bulk-status"
                    class="border border-gray-300 rounded-lg px-4 py-2"
                >

                    <option value="">
                        Change Status
                    </option>

                    <option value="pending">
                        Pending
                    </option>

                    <option value="in_progress">
                        In Progress
                    </option>

                    <option value="completed">
                        Completed
                    </option>

                </select>


                <button
                    type="button"
                    onclick="submitBulkStatus()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                >

                    <i class="fa-solid fa-arrows-rotate mr-1"></i>

                    Update Status

                </button>


                <select
                    id="bulk-priority"
                    class="border border-gray-300 rounded-lg px-4 py-2"
                >

                    <option value="">
                        Change Priority
                    </option>

                    <option value="low">
                        Low
                    </option>

                    <option value="medium">
                        Medium
                    </option>

                    <option value="high">
                        High
                    </option>

                </select>


                <button
                    type="button"
                    onclick="submitBulkPriority()"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg"
                >

                    <i class="fa-solid fa-flag mr-1"></i>

                    Update Priority

                </button>


                <button
                    type="button"
                    onclick="submitBulkDelete()"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg"
                >

                    <i class="fa-solid fa-trash mr-1"></i>

                    Delete Selected

                </button>

            </div>

        </div>

    </form>


    <!-- Task List -->

    <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">

            <div>

                <h2 class="text-xl font-semibold text-gray-800">

                    <i class="fa-solid fa-list text-indigo-600 mr-2"></i>

                    Tasks

                </h2>

                <p class="text-sm text-gray-500 mt-1">

                    Drag tasks to change their position.

                </p>

            </div>


            <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-lg text-sm font-medium">

                {{ $filteredCount }} task(s) found

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

                    <div class="flex flex-col lg:flex-row lg:items-center gap-3">


                        <!-- Checkbox -->

                        <input
                            type="checkbox"
                            name="task_ids[]"
                            value="{{ $task->id }}"
                            form="bulk-form"
                            class="task-checkbox w-5 h-5 text-indigo-600"
                        >


                        <!-- Drag -->

                        <span
                            class="drag-handle cursor-grab active:cursor-grabbing text-gray-300 group-hover:text-indigo-400 p-2"
                            title="Drag to reorder"
                        >

                            <i class="fa-solid fa-grip-vertical text-lg"></i>

                        </span>


                        <!-- Position -->

                        <div class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full text-gray-500 text-sm font-semibold">

                            {{ $task->position }}

                        </div>


                        <!-- Task -->

                        <div class="flex-1 min-w-0">

                            <input
                                type="text"
                                value="{{ $task->title }}"
                                class="task-title-input w-full border border-transparent hover:border-gray-300 focus:border-indigo-400 rounded-lg px-2 py-1 font-semibold text-gray-800 focus:outline-none"
                                data-id="{{ $task->id }}"
                            >

                            <div class="text-xs text-gray-400 mt-1">

                                Created:
                                {{ $task->created_at?->format('d M Y, h:i A') }}

                            </div>

                        </div>


                        <!-- Priority -->

                        <select
                            class="priority-select text-xs font-semibold rounded-full px-3 py-2 border-0"
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


                        <!-- Status -->

                        <select
                            class="status-select text-xs font-semibold rounded-lg px-3 py-2 border"
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


                        <!-- Duplicate -->

                        <form
                            action="{{ route('tasks.duplicate', $task) }}"
                            method="POST"
                            class="inline"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-3 py-2 rounded-lg"
                                title="Duplicate"
                            >

                                <i class="fa-solid fa-copy"></i>

                            </button>

                        </form>


                        <!-- Delete -->

                        <form
                            action="{{ route('tasks.destroy', $task) }}"
                            method="POST"
                            class="inline"
                            onsubmit="return confirm('Are you sure you want to delete this task?');"
                        >

                            @csrf

                            @method('DELETE')

                            <button
                                type="submit"
                                class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg"
                                title="Delete"
                            >

                                <i class="fa-solid fa-trash"></i>

                            </button>

                        </form>

                    </div>

                </li>

            @empty

                <li class="bg-blue-50 border-l-4 border-blue-400 p-5 rounded-md">

                    <i class="fa-solid fa-circle-info text-blue-400 mr-3"></i>

                    No tasks found.

                </li>

            @endforelse

        </ul>


        <!-- Pagination -->

        @if($tasks->hasPages())

            <div class="mt-6 flex justify-center">

                <div class="flex flex-wrap gap-2">

                    @for($page = 1; $page <= $tasks->lastPage(); $page++)

                        <a
                            href="{{ $tasks->url($page) }}"
                            class="px-4 py-2 rounded-lg border
                            {{ $tasks->currentPage() == $page
                                ? 'bg-indigo-600 text-white border-indigo-600'
                                : 'bg-white text-gray-700 border-gray-300 hover:bg-indigo-50' }}"
                        >
                            {{ $page }}
                        </a>

                    @endfor

                </div>

            </div>

        @endif

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>


<script>

const csrfToken =
    document
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

            background:
                type === 'success'
                    ? '#10B981'
                    : '#EF4444',

            color: '#fff',

            borderRadius: '8px',

            fontFamily: "'Poppins', sans-serif"

        }

    }).showToast();
}


/*
|--------------------------------------------------------------------------
| Select All
|--------------------------------------------------------------------------
*/

const selectAll =
    document.getElementById('select-all');

const checkboxes =
    document.querySelectorAll('.task-checkbox');

const selectedCount =
    document.getElementById('selected-count');


function updateSelectedCount()
{
    const count =
        document.querySelectorAll(
            '.task-checkbox:checked'
        ).length;

    selectedCount.textContent =
        count + ' selected';
}


selectAll.addEventListener('change', function()
{
    checkboxes.forEach(function(checkbox)
    {
        checkbox.checked = selectAll.checked;
    });

    updateSelectedCount();
});


checkboxes.forEach(function(checkbox)
{
    checkbox.addEventListener(
        'change',
        updateSelectedCount
    );
});


function getSelectedIds()
{
    return Array.from(
        document.querySelectorAll(
            '.task-checkbox:checked'
        )
    ).map(
        checkbox => checkbox.value
    );
}


/*
|--------------------------------------------------------------------------
| Bulk Delete
|--------------------------------------------------------------------------
*/

function submitBulkDelete()
{
    const ids = getSelectedIds();

    if (ids.length === 0)
    {
        showToast(
            'Please select at least one task.',
            'error'
        );

        return;
    }


    if (!confirm(
        'Delete ' +
        ids.length +
        ' selected task(s)?'
    ))
    {
        return;
    }


    const form =
        document.getElementById('bulk-form');

    form.action =
        "{{ route('tasks.bulk-delete') }}";

    form.submit();
}


/*
|--------------------------------------------------------------------------
| Bulk Status
|--------------------------------------------------------------------------
*/

function submitBulkStatus()
{
    const ids = getSelectedIds();

    const status =
        document.getElementById(
            'bulk-status'
        ).value;


    if (ids.length === 0)
    {
        showToast(
            'Please select at least one task.',
            'error'
        );

        return;
    }


    if (!status)
    {
        showToast(
            'Please select a status.',
            'error'
        );

        return;
    }


    const form =
        document.getElementById('bulk-form');


    form.action =
        "{{ route('tasks.bulk-status') }}";


    let input =
        document.createElement('input');

    input.type = 'hidden';

    input.name = 'status';

    input.value = status;

    form.appendChild(input);


    form.submit();
}


/*
|--------------------------------------------------------------------------
| Bulk Priority
|--------------------------------------------------------------------------
*/

function submitBulkPriority()
{
    const ids = getSelectedIds();

    const priority =
        document.getElementById(
            'bulk-priority'
        ).value;


    if (ids.length === 0)
    {
        showToast(
            'Please select at least one task.',
            'error'
        );

        return;
    }


    if (!priority)
    {
        showToast(
            'Please select a priority.',
            'error'
        );

        return;
    }


    const form =
        document.getElementById('bulk-form');


    form.action =
        "{{ route('tasks.bulk-priority') }}";


    let input =
        document.createElement('input');

    input.type = 'hidden';

    input.name = 'priority';

    input.value = priority;

    form.appendChild(input);


    form.submit();
}


/*
|--------------------------------------------------------------------------
| Drag & Drop
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function()
    {
        const sortableList =
            document.getElementById(
                'sortable-list'
            );


        if (!sortableList)
        {
            return;
        }


        Sortable.create(
            sortableList,
            {

                animation: 250,

                handle: '.drag-handle',

                ghostClass:
                    'sortable-ghost',


                onEnd: function(evt)
                {
                    const itemEl =
                        evt.item;

                    const id =
                        itemEl.getAttribute(
                            'data-id'
                        );


                    let type =
                        'moveAfter';

                    let positionEntityId =
                        null;


                    if (evt.newIndex === 0)
                    {
                        type =
                            'moveBefore';

                        const nextEl =
                            itemEl.nextElementSibling;

                        positionEntityId =
                            nextEl
                                ? nextEl.getAttribute(
                                    'data-id'
                                )
                                : null;
                    }
                    else
                    {
                        const prevEl =
                            itemEl.previousElementSibling;

                        positionEntityId =
                            prevEl
                                ? prevEl.getAttribute(
                                    'data-id'
                                )
                                : null;
                    }


                    if (!positionEntityId)
                    {
                        showToast(
                            'Task position could not be updated.',
                            'error'
                        );

                        return;
                    }


                    fetch(
                        "{{ route('sort') }}",
                        {

                            method: 'POST',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrfToken,

                                'Accept':
                                    'application/json'

                            },

                            body: JSON.stringify({

                                type: type,

                                entityName:
                                    'tasks',

                                id: id,

                                positionEntityId:
                                    positionEntityId

                            })

                        }
                    )
                    .then(response =>
                    {
                        if (response.ok)
                        {
                            showToast(
                                'Task position saved successfully!'
                            );
                        }
                        else
                        {
                            showToast(
                                'Task position could not be saved.',
                                'error'
                            );
                        }
                    })
                    .catch(error =>
                    {
                        console.error(error);

                        showToast(
                            'Something went wrong.',
                            'error'
                        );
                    });
                }

            }
        );
    }
);


/*
|--------------------------------------------------------------------------
| AJAX Task Updates
|--------------------------------------------------------------------------
*/

function updateTask(
    taskId,
    title,
    priority,
    status
)
{
    fetch(
        `/tasks/${taskId}`,
        {

            method: 'PATCH',

            headers: {

                'Content-Type':
                    'application/json',

                'X-CSRF-TOKEN':
                    csrfToken,

                'Accept':
                    'application/json'

            },

            body: JSON.stringify({

                title: title,

                priority: priority,

                status: status

            })

        }
    )
    .then(response =>
    {
        if (!response.ok)
        {
            throw new Error(
                'Update failed.'
            );
        }

        return response.json();
    })
    .then(data =>
    {
        if (data.success)
        {
            showToast(
                'Task updated successfully!'
            );
        }
    })
    .catch(error =>
    {
        console.error(error);

        showToast(
            'Task update failed.',
            'error'
        );
    });
}


/*
|--------------------------------------------------------------------------
| Priority / Status / Title
|--------------------------------------------------------------------------
*/

document
    .querySelectorAll('.priority-select')
    .forEach(select =>
    {
        select.addEventListener(
            'change',
            function()
            {
                const row =
                    this.closest('li');

                const title =
                    row.querySelector(
                        '.task-title-input'
                    ).value;

                const status =
                    row.querySelector(
                        '.status-select'
                    ).value;

                updateTask(
                    this.dataset.id,
                    title,
                    this.value,
                    status
                );
            }
        );
    });


document
    .querySelectorAll('.status-select')
    .forEach(select =>
    {
        select.addEventListener(
            'change',
            function()
            {
                const row =
                    this.closest('li');

                const title =
                    row.querySelector(
                        '.task-title-input'
                    ).value;

                const priority =
                    row.querySelector(
                        '.priority-select'
                    ).value;

                updateTask(
                    this.dataset.id,
                    title,
                    priority,
                    this.value
                );
            }
        );
    });


document
    .querySelectorAll('.task-title-input')
    .forEach(input =>
    {
        input.addEventListener(
            'blur',
            function()
            {
                const row =
                    this.closest('li');

                const priority =
                    row.querySelector(
                        '.priority-select'
                    ).value;

                const status =
                    row.querySelector(
                        '.status-select'
                    ).value;

                updateTask(
                    this.dataset.id,
                    this.value,
                    priority,
                    status
                );
            }
        );
    });

</script>


</body>

</html>