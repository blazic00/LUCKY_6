<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(){
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user){
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User has been deleted');
    }

    public function create(){
        return view('admin.users.create');
    }

    public function store(Request $request){
        $validated=$request->validate([
            'username'=>'required|string|unique:users,username',
            'password'=>'required|string',
        ]);

        User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User has been created');
    }
}
