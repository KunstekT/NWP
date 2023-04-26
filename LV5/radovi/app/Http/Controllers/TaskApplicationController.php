<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\TaskApplication;

class TaskApplicationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('tasks')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
            ]
        ]);

    }

    public function update(Request $request, string $id)
    {
        if ($request->accepted && TaskApplication::findOrFail($id) !== 1) {
            return redirect()->back()->withErrors(['accepted' => 'You can only accept applications for tasks with priority 1.']);
        }

    }

}