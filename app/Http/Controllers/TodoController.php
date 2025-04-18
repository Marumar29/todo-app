<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', Auth::id())->get();
        return view('todos.index', compact('todos'));
    }

    public function create()
    {
        return view('todos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500', // Optional description
        ]);

        Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('todos.index');
    }

    public function edit(Todo $todo)
    {
        // Only allow editing if the todo belongs to the current user
        if ($todo->user_id !== Auth::id()) {
            abort(403);
        }

        return view('todos.edit', compact('todo'));
    }

    public function update(Request $request, Todo $todo)
    {
        // Validate the input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        // Ensure the todo belongs to the current user
        if ($todo->user_id !== Auth::id()) {
            abort(403);
        }

        // Update the todo
        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('todos.index');
    }

    public function destroy(Todo $todo)
    {
        // Ensure the todo belongs to the current user
        if ($todo->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete the todo
        $todo->delete();

        return redirect()->route('todos.index');
    }
}
