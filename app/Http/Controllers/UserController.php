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

}
