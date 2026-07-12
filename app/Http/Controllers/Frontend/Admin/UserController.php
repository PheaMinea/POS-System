<?php

namespace App\Http\Controllers\Frontend\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();

        return view(
            'admin.users.index',
            compact('users')
        );
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in([
                'admin',
                'cashier',
                'customer'
            ])],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {

            $validated['image'] = $request->file('image')
                ->store('users', 'public');

        }

        $validated['password'] = Hash::make(
            $validated['password']
        );

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User created successfully.'
            );
    }

    public function edit(User $user)
    {
        return view(
            'admin.users.edit',
            compact('user')
        );
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed'
            ],

            'role' => ['required', Rule::in([
                'admin',
                'cashier',
                'customer'
            ])],

            'image' => [
                'nullable',
                'image',
                'max:2048'
            ],
        ]);

        if ($request->hasFile('image')) {

            if ($user->image) {

                Storage::disk('public')
                    ->delete($user->image);

            }

            $validated['image'] = $request->file('image')
                ->store('users', 'public');
        }

        if (!empty($validated['password'])) {

            $validated['password'] = Hash::make(
                $validated['password']
            );

        } else {

            unset($validated['password']);

        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User updated successfully.'
            );
    }

   public function destroy(User $user)
{
    if ($user->image) {

        Storage::disk('public')
            ->delete($user->image);

    }

    $user->delete();

    return redirect()
        ->route('admin.users.index')
        ->with(
            'success',
            'User deleted successfully.'
        );
}

}