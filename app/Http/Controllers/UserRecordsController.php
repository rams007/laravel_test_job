<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRecordRequest;
use App\Models\Category;
use App\Models\UserRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserRecordsController extends Controller
{


    /**
     * Get all user records
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getRecords()
    {
        $user = Auth::user();
        if ($user->role == 'manager') {
            $allEmployeeRecords = UserRecord::all();
        } else {
            $allEmployeeRecords = UserRecord::where('user_id', $user->id)->get();
        }
        $allCategories = Category::all(['id', 'name']);
        return view('employee_records', ['allEmployeeRecords' => $allEmployeeRecords, 'allCategories' => $allCategories]);
    }

    /**
     * Create new record
     * @param CreateUserRecordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createRecord(CreateUserRecordRequest $request)
    {
        $user = Auth::user();
        $userRecord = $request->only(['title', 'category_id']);
        if ($request->file('image')->isValid()) {
            $path = $request->image->store('images', 'public');
            $userRecord['image_path'] = $path;
            $userRecord['user_id'] = $user->id;
            UserRecord::create($userRecord);
            return response()->json(['error' => false, 'msg' => 'Saved']);
        } else {
            return response()->json(['error' => true, 'msg' => 'File invalid']);
        }
    }

    public function deleteRecord(UserRecord $user_record)
    {
        if ($user_record) {
            Storage::disk('public')->delete($user_record->image_path);
            $user_record->delete();
            return response()->json(['error' => false, 'msg' => 'User record deleted']);
        } else {
            return response()->json(['error' => true, 'msg' => 'User record not found']);
        }

    }
}
