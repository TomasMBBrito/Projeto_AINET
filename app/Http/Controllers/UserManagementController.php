<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->withTrashed()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'gender' => 'required|in:M,F',
            'password' => 'required|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('profile_photos', 'public');
        }

        $validated['type'] = 'employee';
        $validated['email_verified_at'] = now();
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Employee added successfully!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'gender' => 'required|in:M,F',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'min:8';
        }

        $data = $request->validate($rules);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('profile_photos', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route(route: 'users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        if (auth()->id === $user->id) {
            return redirect()->back()->withErrors(['error' => 'You can`t cancel your own account.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Subscription canceled (soft delete).');
    }

    public function toggleBlock(User $user)
    {
        if (auth()->id === $user->id) {
            return redirect()->back()->withErrors(['error' => 'You can`t block your own account.']);
        }
        $user->blocked = !$user->blocked;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Updated lock status.');
    }

    public function toggleBoard(User $user)
    {
        if($user->blocked){
            return redirect()->back()->withErrors(['error' => 'You can`t promote a blocked account.']);
        }
        if ($user->type === 'member') {
            $user->type = 'board';
        } elseif ($user->type === 'board') {
            $user->type = 'member';
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Updated user type.');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.index')->with('success', 'Account restored.');
    }

}
