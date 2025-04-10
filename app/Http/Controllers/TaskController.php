<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index (Request $request)
    {
        $filters = $request->only('byName');
        $tasks = Task::orderBy('id', 'desc')
            ->when($request->has('byName'), function ($query, $filter) {
            $query->where('name', 'like', "%" . urldecode($filter) . "%");
        })->paginate(5)
        ->withQueryString();

        if ($request->has('byName')) {
            $filters['byName'] = urldecode($filters['byName']);
        }
        $data = [
            'tasks' => $tasks,
            'filters' => $filters
        ];
        return Inertia::render('tasks/Index', $data);
    }

    public function create ()
    {
        return Inertia::render('tasks/Create');
    }

    public function store (Request $request)
    {
        $task = $request->validate([
            'name' => 'required|string|min:3|max:255|unique:tasks,name'
        ]);

        $newTask = Task::create($task);
        return redirect()
            ->route('tasks.index')
            ->with('message', "The task $newTask->name was created successfully");
    }

    public function test ()
    {
        return Inertia::render('tasks/Test');
    }

    public function testStore (Request $request)
    {
        var_dump($request->all());
        die();
    }
}
