<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRecordRequest;
use App\Models\Category;
use App\Models\User;
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
            $allEmployeeRecords = UserRecord::whereNotNull('image_path')->paginate(10);
        } else {
            $allEmployeeRecords = UserRecord::where('user_id', $user->id)->paginate(10);
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

    /**
     * Delete single record and delete saved image
     * @param UserRecord $user_record
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Show single record details
     * @param UserRecord $user_record
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showRecord(UserRecord $user_record)
    {
        $user_record->author = User::find($user_record->user_id);
        return view('employee_record_details', ['user_record' => $user_record]);
    }

    /**
     * Get records filtered by category
     * @param $categoryId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @todo we can merge this method with getRecords
     */
    public function getRecordsByCategory($categoryId)
    {
        $user = Auth::user();
        if ($user->role == 'manager') {
            $allEmployeeRecords = UserRecord::whereNotNull('image_path')->where('category_id', $categoryId)->paginate(10);
        } else {
            $allEmployeeRecords = UserRecord::where('user_id', $user->id)->where('category_id', $categoryId)->paginate(10);
        }

        $allCategories = Category::all(['id', 'name']);
        return view('categories', ['allEmployeeRecords' => $allEmployeeRecords, 'allCategories' => $allCategories]);
    }
}
