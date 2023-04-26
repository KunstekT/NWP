<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (Gate::allows('update-role', $user)) {
            $user->role = $request->input('role');
            $user->save();
            return redirect()->route('users.index')->with('success', 'Role updated successfully.');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
