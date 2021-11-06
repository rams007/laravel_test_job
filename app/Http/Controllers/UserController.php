<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
     * Get list of all existed employees
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getEmployees()
    {
        $user = Auth::user();
        $allEmployee = User::where('role', 'employee')->where('manager_id', $user->id)->get(['id', 'email']);
        return view('employees', ['allEmployee' => $allEmployee]);
    }


    /**
     * Create new employee record
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createEmployee(CreateUserRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $user = Auth::user();
        Gate::authorize('create',$user);
        try {
            $credentials['password'] = Hash::make($credentials['password']);
            $credentials['role'] = 'employee';
            $credentials['manager_id'] = $user->id;
            User::create($credentials);

            return response()->json(['error' => false, 'msg' => 'User created']);
        } catch (\Throwable $e) {
            Log::error('We got error when user attempt to register ' . $e->getMessage());
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Delete one employee
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteEmployee(User $user)
    {
        if ($user) {
            Gate::authorize('delete', $user);
            if ($user->role == 'employee') {
                $user->delete();
                return response()->json(['error' => false, 'msg' => 'User deleted']);
            } else {
                return response()->json(['error' => true, 'msg' => 'We cant delete manager']);
            }
        } else {
            return response()->json(['error' => true, 'msg' => 'User not found']);
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
