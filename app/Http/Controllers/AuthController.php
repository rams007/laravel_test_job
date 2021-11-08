<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Create  user manager instance
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createUser(CreateUserRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        try {
            $credentials['password'] = Hash::make($credentials['password']);
            $credentials['role'] = 'manager';
            $user = User::create($credentials);
            Auth::login($user);
            return redirect('/');
        } catch (\Throwable $e) {
            Log::error('We got error when user attempt to register ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
        }

    }

    /**
     * Login users
     * @param LoginUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginUser(LoginUserRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log the user out of the application.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logoutUser(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
