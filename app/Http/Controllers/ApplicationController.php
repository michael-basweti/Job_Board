<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Application;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
/**
     * @OA\GET(
     *     path="/api/v1/applications",
     *     operationId="/sample/category/things",
     *     tags={"Get All Applications"},
     *security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response="200",
     *         description="Returns all applications",
     *         @OA\JsonContent()
     *     ),
     *
     * )
     */
    public function showAllApplications(Request $request)
    {
        $applications = Application::all();
        return response()->json($applications, 200);
    }

    /**
     * @OA\GET(
     *     path="/api/v1/applications/{id}",
     *     operationId="/sample/category/things",
     *     tags={"Get One application"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="application id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns one application",
     *         @OA\JsonContent()
     *     ),
     *
     * )
     */
    public function showOneApplication($id)
    {
        try {
            $applications = Application::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json("application not available", 404);
        }
        return response()->json($applications, 200);
    }
   /**
     * @OA\POST(
     *     path="/api/v1/applications",
     *     operationId="/sample/category/things",
     *     tags={"Create an application"},
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
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Parameter(
     *         name="job_id",
     *         in="query",
     *         description="job id",
     *         required=false,
     *         @OA\Schema(type="string")
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
   /**
     * @OA\PUT(
     *     path="/api/v1/applications",
     *     operationId="/sample/category/things",
     *     tags={"Edit an application"},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="application id",
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
     *         name="job_id",
     *         in="query",
     *         description="job id",
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
            $applications = Application::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json("application not available", 404);
        }
        if(Auth::user()->role=="applicant"){
            if(Auth::id()==$applications->user_id){
                $applications->update($request->all());
            }else{
                // @codeCoverageIgnoreStart
                return response()->json("Only the owner can update", 403);
                // @codeCoverageIgnoreEnd
            }
        }else {
            return response()->json("Only applicants can perform this action", 403);
        }
        return response()->json($applications, 200);
    }
/**
     * @OA\DELETE(
     *     path="/api/v1/applications/{id}",
     *     operationId="/sample/category/things",
     *     tags={"delete an application "},
     *security={{"bearerAuth":{}}},
     *@OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="application id",
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
            $application = Application::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json("application not available", 404);
        }

        if(Auth::user()->role=="applicant"){
            if(Auth::id()==$application->user_id){
                $application->delete();
                return response('Deleted Successfully', 204);
            }else{
                // @codeCoverageIgnoreStart
                return response()->json(["status" => "error", "message" => "Only the owner can delete this job"], 403);
                // @codeCoverageIgnoreEnd
            }
        }else {
            return response()->json("Only applicants can perform this action", 403);
        }

    }
}
