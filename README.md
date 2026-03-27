# 🚀 PHP Laravel 12 - Rutorika Sortable (Drag & Drop Sorting)

This project demonstrates how to implement **Drag & Drop sorting functionality** in **Laravel 12** using the `rutorika/sortable` package.
The UI is designed with **Tailwind CSS**, **SortableJS**, and **Toastify** for a smooth and interactive experience.

Users can easily **reorder tasks by dragging and dropping them**, and the updated order will be stored automatically in the database.

---

# 📌 Features

* Laravel 12 project setup
* `rutorika/sortable` package integration
* Drag & Drop task ordering
* Database position-based sorting
* Tailwind CSS modern UI
* Toast notifications using Toastify
* SortableJS for frontend drag-and-drop

---

# ⚙️ Installation Guide

## Step 1: Create Laravel Project & Install Package

Open your terminal and run the following commands:

```bash
composer create-project laravel/laravel PHP_Laravel12_Rutorika_Sortable
cd PHP_Laravel12_Rutorika_Sortable
composer require rutorika/sortable
```

---

# 🗄️ Step 2: Database & Migration Setup

Update your `.env` file with database credentials:

```
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```

Create Model, Migration, and Controller:

```bash
php artisan make:model Task -mrc
```

Open the migration file inside:

```
database/migrations/xxxx_xx_xx_create_tasks_table.php
```

Replace it with:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('position');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
```

Run migration:

```bash
php artisan migrate
```

---

# 🧩 Step 3: Model Setup

Open:

```
app/Models/Task.php
```

Add the `SortableTrait`.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

class Task extends Model
{
    use SortableTrait;

    protected $fillable = ['title','position'];
}
```

---

# 🔧 Step 4: Package Configuration Fix

Sometimes Laravel 12 cannot publish the package configuration automatically.

Create a new file:

```
config/sortable.php
```

Add the following code:

```php
<?php

return [
    'entities' => [
        'tasks' => \App\Models\Task::class,
    ],
];
```

---

# 🛣️ Step 5: Routes Setup

Open:

```
routes/web.php
```

Add these routes:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use Rutorika\Sortable\SortableController;

Route::get('/', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

Route::post('/sort', [SortableController::class, 'sort'])->name('sort');
```

---

# 🧠 Step 6: Controller Logic

Open:

```
app/Http/Controllers/TaskController.php
```

Add this code:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::sorted()->get();
        return view('tasks', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        Task::create([
            'title' => $request->title
        ]);

        return back();
    }
}
```

---

# 🎨 Step 7: Frontend View (Drag & Drop UI)

Create the view file:

```
resources/views/tasks.blade.php
```

Add the following UI with **TailwindCSS + SortableJS + Toastify**.

```html
<!-- Full UI code goes here -->
<!-- (Paste your Blade file code here exactly as in your project) -->
```

This UI allows users to:

* Add new tasks
* Drag tasks using a handle
* Automatically save the new order using AJAX

---

# ▶️ Step 8: Run the Project

Start the Laravel development server:

```bash
php artisan serve
```

Now open the browser:

```
http://localhost:8000
```

Your **Drag & Drop Task Manager** is now ready! 🎉

---

# 📦 Technologies Used

* Laravel 12
* PHP
* Rutorika Sortable
* Tailwind CSS
* SortableJS
* Toastify

---

 # Outpot
<img width="713" height="518" alt="image" src="https://github.com/user-attachments/assets/07136a6b-875c-4386-b98f-22b563f7e716" />

---

⭐ If you found this project useful, consider giving it a star on GitHub!
