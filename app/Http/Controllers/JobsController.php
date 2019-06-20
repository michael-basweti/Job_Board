<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Job;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class JobsController extends Controller
{

    public function showAllJobs(Request $request)
    {
        $jobs = Job::with('applications');
        if ($request->has('sort_asc')) {
            $books->orderBy('title', 'asc')->get();
        }
        if ($request->has('sort_asc')) {
            $books->orderBy('title', 'desc')->get();
        }
        if ($request->has('offset') && $request->has('limit')) {
            $jobs->offset($request->offset)->limit($request->limit);
        }
        if ($request->has('search')) {
            $description = strtolower($request->search);
            $jobs->whereRaw('LOWER(description) like (?)', "%$description%");
        }
        $jobs=$jobs->get();
        return response()->json($jobs, 200);
    }

    public function showOneJob($id)
    {
        try {
            $jobs = Job::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json("job not available", 404);
        }
        return response()->json($jobs, 200);
    }

    public function showJobWithApplications($id)
    {
        try {
            $jobs = Job::with('applications')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json("job not available", 404);
        }
        return response()->json($jobs, 200);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'expected_income'=>'required|string',
            'start_date'=>'required|date_format:d/m/Y',
            'delivery_date'=>'required|date_format:d/m/Y',
            'description'=>'required',
        ]);

        if(Auth::user()->role=="employer"){
            $jobs = new Job;
            $jobs->title= $request->title;
            $jobs->expected_income = $request->expected_income;
            $jobs->start_date = $request->start_date;
            $jobs->delivery_date = $request->delivery_date;
            $jobs->description = $request->description;
            $jobs->user_id = $request->user_id=Auth::id();

            $jobs->save();
        }else {
            return response()->json("Not allowed, Only Employers can create jobs", 403);
        }

        return response()->json($jobs, 201);
    }

    public function update($id, Request $request)
    {
        try {
            $jobs = Job::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json("Job Not Found", 404);
        }
        if(Auth::user()->role=="employer"){
            if(Auth::id()==$jobs->user_id){
                $jobs->update($request->all());
            }else{
                return response()->json("Only the owner can update", 403);
            }
        }else {
            return response()->json("Only employers can perform this action", 403);
        }
        return response()->json($jobs, 200);
    }

    public function delete($id)
    {
        try {
            $job = Job::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json("job not available", 404);
        }

        if(Auth::user()->role=="employer"){
            if(Auth::id()==$job->user_id){
                $job->delete();
                return response('Deleted Successfully', 204);
            }else{
                return response()->json("Only the owner can delete this job", 403);
            }
        }else {
            return response()->json("Only employers can perform this action", 403);
        }

    }
}
