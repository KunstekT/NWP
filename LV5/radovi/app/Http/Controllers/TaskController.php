<?php

namespace App\Http\Controllers;

use App\Models\TaskApplication;
use App;
use App\Models\Task;
use Illuminate\Http\Request;
use View;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $locale = App::getLocale();
        $data = [
            'naziv' => __('tasks.name'),
            'naziv_na_engleskom' => __('tasks.name_en'),
            'zadatak_rada' => __('tasks.description'),
            'tip_studija' => __('tasks.study_type'),
        ];
        return View::make('tasks.create', compact('locale', 'data'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'naziv' => 'required|max:255',
            'naziv_na_engleskom' => 'required|max:255',
            'zadatak_rada' => 'required',
            'tip_studija' => 'required|in:stručni,preddiplomski,diplomski',
        ]);

        if (auth()->user()->hasRole('nastavnik')) {
            $task = new Task;
            $task->naziv = $validatedData['naziv'];
            $task->naziv_na_engleskom = $validatedData['naziv_na_engleskom'];
            $task->zadatak_rada = $validatedData['zadatak_rada'];
            $task->tip_studija = $validatedData['tip_studija'];
            $task->user_id = auth()->id();
            $task->save();

            return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'naziv' => 'required|max:255',
            'naziv_na_engleskom' => 'required|max:255',
            'zadatak_rada' => 'required',
            'tip_studija' => 'required|in:stručni,preddiplomski,diplomski',
        ]);

        $task->name = $validatedData['naziv'];
        $task->name_en = $validatedData['naziv_na_engleskom'];
        $task->description = $validatedData['zadatak_rada'];
        $task->study_type = $validatedData['tip_studija'];
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function showSignupPage()
    {
        $tasks = Task::where('status', 'open')->get();
        return view('tasks.signup', ['tasks' => $tasks]);
    }

    public function showApplications(Task $task)
    {
        $applications = $task->applications;

        return view('tasks.applications', compact('applications', 'task'));
    }

    public function approveApplication(Task $task, TaskApplication $application)
    {
        $application->update(['status' => 'approved']);

        $task->assigned_to = $application->user_id;
        $task->save();

        return redirect()->back();
    }

}