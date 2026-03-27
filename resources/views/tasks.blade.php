<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP_Laravel12_Rutorika_Sortable</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
        }
        /* Custom ghost class for drag effect */
        .sortable-ghost {
            opacity: 0.4;
            background-color: #f3f4f6; /* Tailwind gray-100 */
            border: 2px dashed #a8a29e; /* Tailwind stone-400 */
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen pt-12 pb-12 px-4 sm:px-6 lg:px-8">

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-10">
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-semibold text-gray-800 mb-2">
                <i class="fa-solid fa-list-check text-indigo-600 mr-2"></i> Task Manager
            </h2>
            <p class="text-gray-500 text-sm">You can set the order by dragging and dropping the items.</p>
        </div>

        <form action="{{ route('tasks.store') }}" method="POST" class="mb-8">
            @csrf
            <div class="flex items-center bg-gray-50 rounded-lg p-1 border border-gray-200 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 transition-all shadow-sm">
                <input 
                    type="text" 
                    name="title" 
                    required 
                    placeholder="Add a new task..." 
                    class="w-full bg-transparent border-none text-gray-700 px-4 py-3 focus:outline-none placeholder-gray-400 font-medium"
                >
                <button 
                    type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md font-medium transition-colors duration-200 flex items-center shadow-md"
                >
                    <i class="fa-solid fa-plus mr-2"></i> Add
                </button>
            </div>
        </form>

        <ul id="sortable-list" class="space-y-3">
            @forelse($tasks as $task)
                <li class="group flex items-center bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200" data-id="{{ $task->id }}">
                    <span class="drag-handle cursor-grab active:cursor-grabbing text-gray-300 group-hover:text-indigo-400 transition-colors mr-4 p-1">
                        <i class="fa-solid fa-grip-vertical text-lg"></i>
                    </span>
                    
                    <span class="text-gray-700 font-medium text-lg">{{ $task->title }}</span>
                    
                    <span class="ml-auto text-gray-300">
                        <i class="fa-regular fa-circle-check"></i>
                    </span>
                </li>
            @empty
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-circle-info text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 font-medium">Koi task nathi. Nvu task add karo!</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </ul>
        
    </div>
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('sortable-list');
        
        if(el) {
            var sortable = Sortable.create(el, {
                animation: 250, // Thodu smooth animation (150 -> 250)
                handle: '.drag-handle', 
                ghostClass: 'sortable-ghost', // Custom CSS class above
                dragClass: "cursor-grabbing",
                
                onEnd: function (evt) {
                    let itemEl = evt.item; 
                    let id = itemEl.getAttribute('data-id');
                    
                    let type = 'moveAfter';
                    let positionEntityId = null;

                    if (evt.newIndex === 0) {
                        type = 'moveBefore';
                        let nextEl = itemEl.nextElementSibling;
                        positionEntityId = nextEl ? nextEl.getAttribute('data-id') : null;
                    } else {
                        let prevEl = itemEl.previousElementSibling;
                        positionEntityId = prevEl ? prevEl.getAttribute('data-id') : null;
                    }

                    if (positionEntityId) {
                        fetch("{{ route('sort') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                type: type,
                                entityName: 'tasks',
                                id: id,
                                positionEntityId: positionEntityId
                            })
                        }).then(res => {
                            if(res.ok) {
                                // Tailwind Style Success Toast
                                Toastify({
                                    text: "✅ Task position saved successfully!",
                                    duration: 3000,
                                    gravity: "bottom", 
                                    position: "right", 
                                    style: {
                                        background: "#10B981", // Tailwind emerald-500
                                        color: "#fff",
                                        borderRadius: "8px",
                                        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                                        fontFamily: "'Poppins', sans-serif"
                                    },
                                    stopOnFocus: true
                                }).showToast();
                            } else {
                                Toastify({
                                    text: "❌ Error! Position save nathi thai.",
                                    duration: 3000,
                                    gravity: "bottom", 
                                    position: "right", 
                                    style: {
                                        background: "#EF4444", // Tailwind red-500
                                        color: "#fff",
                                        borderRadius: "8px",
                                        fontFamily: "'Poppins', sans-serif"
                                    }
                                }).showToast();
                            }
                        }).catch(error => {
                            console.error("Error:", error);
                        });
                    }
                }
            });
        }
    });
</script>
</body>
</html>