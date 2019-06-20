<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Application;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{

    public function showAllApplications(Request $request)
    {
        $applications = Application::all();
        return response()->json($applications, 200);
    }

    public function showOneApplication($id)
    {
        try {
            $applications = Application::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json("application not available", 404);
        }
        return response()->json($applications, 200);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'job_id'=>'required|integer',
            'description'=>'required',
        ]);

        if(Auth::user()->role=="applicant"){
            $applications = new Application;
            $applications->title= $request->title;
            $applications->job_id = $request->job_id;
            $applications->user_name = $request->user_name=Auth::user()->name;
            $applications->description = $request->description;
            $applications->user_id = $request->user_id=Auth::id();

            $applications->save();
        }else {
            return response()->json("Not allowed, Only Applicants can apply for jobs", 403);
        }

        return response()->json($applications, 201);
    }

    public function update($id, Request $request)
    {
        try {
            $applications = Application::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json("application not available", 404);
        }
        if(Auth::user()->role=="applicant"){
            if(Auth::id()==$applications->user_id){
                $applications->update($request->all());
            }else{
                return response()->json("Only the owner can update", 403);
            }
        }else {
            return response()->json("Only applicants can perform this action", 403);
        }
        return response()->json($applications, 200);
    }

    public function delete($id)
    {
        try {
            $application = Application::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json("application not available", 404);
        }

        if(Auth::user()->role=="applicant"){
            if(Auth::id()==$application->user_id){
                $application->delete();
                return response('Deleted Successfully', 204);
            }else{
                return response()->json(["status" => "error", "message" => "Only the owner can delete this job"], 403);
            }
        }else {
            return response()->json("Only applicants can perform this action", 403);
        }

    }
}
