<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserLoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request){

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('username', $request->username)->first();
        //Log::info('User ID: ' . $user->id);

        if($user && Hash::check($request->password, $user->password)){
            Auth::guard('user')->login($user);
           // Log::info('User ID: ' . Auth::guard('user')->id());
            return redirect()->route('index');
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
        Auth::guard('user')->logout();

        // Redirect the user to the login page after logout
        return redirect()->route('login');
    }
}
