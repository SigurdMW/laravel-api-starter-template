<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\CustomComponents\Responses;
use App\Http\Controllers\Controller;
use App\CustomComponents\Transformers;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthenticateController extends Controller
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
       $this->middleware('jwt.refresh', ['except' => ['authenticate','logout']]);

       // Adding the transformer class
       $this->userTransformer = $userTransformer;

       // Adding the api response class
       $this->apiResponse = $apiResponse;
   	} 
  

    /*
     * Authenticate the user
     *
     */

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {

                return $this->apiResponse->json(401, "", "");
            }
        } catch (JWTException $e) {
            // something went wrong

            return $this->apiResponse->json(500, "Internal server error, could not create token.", "");
        }

        // if no errors are encountered we can return a JWT
        return $this->apiResponse->json(200, "", compact('token'));
        /*return response()
            ->json(['hello', $token])
            ->header('Authorization', 'Bearer '.$token);*/


    }


    /*
     * Getting the authenticated user
     *
     */

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                 
                 return $this->apiResponse->json(404, "User not found.", "");
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            
            return $this->apiResponse->json(401, "Unauthorized, token expired.", "");

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            
            return $this->apiResponse->json(401, "Unauthorized, token invalid.", "");

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

             return $this->apiResponse->json(401, "Unauthorized, token absent.", "");

        }

        // the token is valid and we have found the user via the sub claim
        // first transform the user object, then return
        
        $user = $this->userTransformer->transform($user->toArray());
        return $this->apiResponse->json(200, "OK", $user);
    }

    
    /*
     * Destroying token
     * 
     */

    public function logout(Request $request)
    {
        $destroyToken = JWTAuth::invalidate(JWTAuth::getToken());
        
        if ($destroyToken){
            $statusCode = 200;
            $message = "Token successfully invalidated";
        } else {
            $statusCode = 500;
            $message = "Error when trying to invalidate token.";
        }

        return $this->apiResponse->json($statusCode, $message, "");
    }
}