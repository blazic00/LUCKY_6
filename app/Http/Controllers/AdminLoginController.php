<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request){

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);


        $admin = Admin::where('username', $request->username)->first();

        if($admin && Hash::check($request->password, $admin->password)){
            Auth::guard('admin')->login($admin);

            return redirect()->route('admin.index');
        }
        else{
            return back()->withErrors([
                'login'=>'Invalid username or password'
            ]);
        }

    }

    /**
     * Handle the logout attempt.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        // Log the user out
        Auth::guard('admin')->logout();

        // Redirect the user to the login page after logout
        return redirect()->route('admin.login');
    }
}
