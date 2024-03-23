<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Timesheet;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimesheetCreateRequest;
use App\Http\Requests\TimesheetUpdateRequest;

class TimesheetController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            $query = Timesheet::query();

            foreach ($request->query() as $field => $value) 
                if (\Schema::hasColumn('timesheets', $field)) 
                    $query->where($field, $value);
            
            $timesheets = $query->get();
            return response()->json(ResponseUtils::build(200, "Success", false, $timesheets));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }        
    }

    public function get($id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            return response()->json(ResponseUtils::build(200, "Success", false, $timesheet));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }
    }    

    public function create(TimesheetCreateRequest $request) {
        try {
            $timesheet = Timesheet::create($request->all());
            return response()->json(ResponseUtils::build(200, "Timesheet created successfully", false, $timesheet));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }
    }

    public function update(TimesheetUpdateRequest $request, $id)
    {        
        try {
            $timesheet = Timesheet::findOrFail($id);                 
            $timesheet->fill($request->validated());            
            $timesheet->save();

            return response()->json(ResponseUtils::build(200, "Timesheet updated successfully", false, $timesheet));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }
    }

    public function delete($id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            $timesheet->delete();
            return response()->json(ResponseUtils::build(200, "Timesheet deleted successfully", false, $timesheet));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }
    }
}
