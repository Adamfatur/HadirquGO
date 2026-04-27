<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    /**
     * Display a paginated list of users.
     */
    public function index(Request $request)
    {
        $roles = Role::all(); // Retrieve all roles for role assignment in views

        // Get sorting parameters or set defaults
        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'asc');

        // Search query if provided
        $search = $request->get('search');

        // Query users with optional search and sorting
        $query = User::with('roles');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->orderBy($sortColumn, $sortDirection)->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'roles', 'sortColumn', 'sortDirection', 'search'));
    }


    /**
     * Show the form for editing a specific user.
     */
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all(); // Retrieve all roles to allow assignment

        // Prevent the logged-in admin from editing their own roles
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot edit your own roles.');
        }

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update a specific user's details and roles.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Allow updating name for self or other admins, but not roles
        if ($user->hasRole('Admin') && Auth::user()->hasRole('Admin') && $user->id !== Auth::id()) {
            // Only update name and email
            $user->update($request->only('name', 'email'));
        } else {
            // Update name, email, and roles for non-admins or self
            $user->update($request->only('name', 'email'));

            if ($request->has('roles') && !$user->hasRole('Admin')) {
                $user->roles()->sync($request->roles);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }



    /**
     * Remove a specific user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent the logged-in admin from deleting their own account
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->roles()->detach(); // Detach roles before deletion
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}
