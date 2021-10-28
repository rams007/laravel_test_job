<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Create  user manager instance
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createUser(CreateUserRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        print_r($credentials);
        try {
            $credentials['password'] = Hash::make($credentials['password']);
            $credentials['role'] = 'manager';
            $credentials['name'] = 'John';
            $user = User::create($credentials);
            Auth::login($user);
            return redirect('/');
        } catch (\Throwable $e) {
            Log::error('We got error when user attemp to register ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
        }

    }
}
