<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Project;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;

class ProjectController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            $query = Project::query();

            foreach ($request->query() as $field => $value) 
                if (\Schema::hasColumn('projects', $field)) 
                    $query->where($field, $value);
            
            $projects = $query->get();
            return response()->json(ResponseUtils::build(200, "Success", false, $projects));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }        
    }

    public function get($id)
    {
        try {
            $project = Project::findOrFail($id);
            return response()->json(ResponseUtils::build(200, "Success", false, $project));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }
    }    

    public function create(ProjectCreateRequest $request) {
        try {
            $project = Project::create($request->all());
            return response()->json(ResponseUtils::build(200, "Project created successfully", false, $project));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }
    }

    public function update(ProjectUpdateRequest $request, $id)
    {        
        try {
            $project = Project::findOrFail($id);                 
            $project->fill($request->validated());            
            $project->save();

            return response()->json(ResponseUtils::build(200, "Project updated successfully", false, $project));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }
    }

    public function delete($id)
    {
        try {
            $project = Project::with("timesheets")->findOrFail($id);            
            
            if ($project) {
                $project->timesheets()->delete();
                $project->delete();
                return response()->json(ResponseUtils::build(200, "Project deleted successfully", false));
            }
            
            response()->json(ResponseUtils::build(500, "Failed to delete project. May project does not exist", true));
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true));
        }
    }
}
