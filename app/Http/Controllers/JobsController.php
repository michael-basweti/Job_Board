<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Job;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class JobsController extends Controller
{

    /**
     * @OA\GET(
     *     path="/api/v1/jobs",
     *     operationId="/sample/category/things",
     *     tags={"Get All Jobs"},
     *security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *
     * )
     */
    public function showAllJobs(Request $request)
    {
        $jobs = Job::with('applications');

        if ($request->has('sort_asc')) {
            $jobs->orderBy('title', 'asc')->get();
        }
        if ($request->has('sort_desc')) {
            $jobs->orderBy('title', 'desc')->get();
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
/**
     * @OA\GET(
     *     path="/api/v1/jobs/{id}",
     *     operationId="/sample/category/things",
     *     tags={"Get One Job"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="job id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *
     * )
     */
    public function showOneJob($id)
    {
        try {
            $jobs = Job::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json("job not available", 404);
        }
        return response()->json($jobs, 200);
    }

    /**
     * @OA\GET(
     *     path="/api/v1/jobs/{id}/application",
     *     operationId="/sample/category/things",
     *     tags={"Get One Job with all applications"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="job id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *
     * )
     */
    public function showJobWithApplications($id)
    {
        try {
            $jobs = Job::with('applications')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json("job not available", 404);
        }
        return response()->json($jobs, 200);
    }

      /**
     * @OA\POST(
     *     path="/api/v1/jobs",
     *     operationId="/sample/category/things",
     *     tags={"Create a job"},
     *security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="expected_income",
     *         in="query",
     *         description="expected income",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="start_date'",
     *         in="query",
     *         description="start date'",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="delivery_date",
     *         in="query",
     *         description="submission date",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
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

          /**
     * @OA\PUT(
     *     path="/api/v1/jobs/{id}",
     *     operationId="/sample/category/things",
     *     tags={"Edit a Job"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="job id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="expected_income",
     *         in="query",
     *         description="expected income",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="start_date'",
     *         in="query",
     *         description="start date'",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="delivery_date",
     *         in="query",
     *         description="submission date",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
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
                // @codeCoverageIgnoreStart
                return response()->json("Only the owner can update", 403);
                // @codeCoverageIgnoreEnd
            }
        }else {
            return response()->json("Only employers can perform this action", 403);
        }
        return response()->json($jobs, 200);
    }

    /**
     * @OA\DELETE(
     *     path="/api/v1/jobs/{id}",
     *     operationId="/sample/category/things",
     *     tags={"delete a job "},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="job id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *
     * )
     */
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
                // @codeCoverageIgnoreStart
                return response()->json("Only the owner can delete this job", 403);
                // @codeCoverageIgnoreEnd
            }
        }else {
            return response()->json("Only employers can perform this action", 403);
        }

    }
}
