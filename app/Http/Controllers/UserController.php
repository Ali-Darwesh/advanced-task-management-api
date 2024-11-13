<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function assignRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $role = Role::where('name', $request->input('role'))->firstOrFail();

        // Check if the user already has the role
        if ($user->roles->contains($role->id)) {
            return response()->json(['message' => 'User already has this role'], 400);
        }

        $user->roles()->attach($role->id);

        return response()->json(['message' => 'Role assigned successfully']);
    }
}
