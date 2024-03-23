<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Timesheet;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            $query = User::query();

            foreach ($request->query() as $field => $value) 
                if (\Schema::hasColumn('users', $field)) 
                    $query->where($field, $value);
            
            $users = $query->get();
            return response()->json(ResponseUtils::build(200, "Success", false, $users));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }        
    }

    public function get($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(ResponseUtils::build(200, "Success", false, $user));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {        
        try {
            $user = User::findOrFail($id);                 
            $user->fill($request->validated());
            
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            
            $user->save();

            return response()->json(ResponseUtils::build(200, "User updated successfully", false, $user));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }
    }

    public function delete($id)
    {
        try {
            $user = User::with("timesheets")->findOrFail($id);            
            
            if ($user) {
                $user->timesheets()->delete();
                $user->delete();
                return response()->json(ResponseUtils::build(200, "User deleted successfully", false));
            }
            
            response()->json(ResponseUtils::build(500, "Failed to delete user. May user does not exist", true));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }
    }            
}
