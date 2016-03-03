<?php

namespace App\Http\Controllers;

use Gate;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\CustomComponents\Responses;
use App\Http\Controllers\Controller;
use App\CustomComponents\Transformers;
use App\Http\Requests\CreateUserRequest;

class UserController extends ApiController
{

    // Transforming output
    protected $userTransformer;

    // Generating JSON response
    protected $apiResponse;


    public function __construct(\CustomComponents\Transformers\UserTransformer $userTransformer, \CustomComponents\Responses\ApiResponse $apiResponse)
    {
       // Apply the jwt.auth middleware to all methods in this controller
       // except for the authenticate method. We don't want to prevent
       // the user from retrieving their token if they don't already have it
       $this->middleware('jwt.refresh', ['except' => ['store']]);

       // Adding the transformer class
       $this->userTransformer = $userTransformer;

       // Adding the api response class
       $this->apiResponse = $apiResponse;
    } 


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $users = User::all();
        
        $data = $this->userTransformer->transformCollection($users->toArray());
        return $this->apiResponse->json(200, "", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {

        $user = User::create($request->all());
        $user = $this->userTransformer->transform($user->toArray());

        return $this->apiResponse->json(201, "", $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        // Authorization
        if (Gate::denies('isowner', $user)){
            return $this->apiResponse->json(401, "Not authorized to view this user.", "");
        }

        // Check if user exist
        if (! $user){
            return $this->apiResponse->json(404, "", "");
        }

        // Transform user
        $user = $this->userTransformer->transform($user->toArray());
        return $this->apiResponse->json(200, "", $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (Gate::denies('isowner', $user)){
            return $this->apiResponse->json(401, "Not authorized to update this user.", "");
        }

        // Check if user exist
        if (! $user){
            return $this->apiResponse->json(404, "Unable to update, user not found.", "");
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        return $this->apiResponse->json(200,"",$user);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        // Authorization
        if (Gate::denies('isowner', $user)){
            return $this->apiResponse->json(401, "Not authorized to delete this user.", "");
        }

        // Check if user exist
        if (! $user){
            return $this->apiResponse->json(404, "Unable to delete, user not found.", "");
        }

        // Deleting the user
        $destroyUser = User::destroy($id);

        //returning reponse
        return $this->apiResponse->json(200,"Succesfully deleted user.", $destroyUser);
    }
}