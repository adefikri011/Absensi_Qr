<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('employee.profile');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $request->name;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui ');
    }
}