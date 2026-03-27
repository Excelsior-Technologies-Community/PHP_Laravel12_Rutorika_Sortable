<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        // sorted() method Rutorika trait mathi aave chhe
        $tasks = Task::sorted()->get(); 
        return view('tasks', compact('tasks'));
    }

    public function store(Request $request)
    {
        Task::create(['title' => $request->title]);
        return back();
    }
}