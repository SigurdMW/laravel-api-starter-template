<?php

namespace App\Http\Requests;

use App\User;
use App\Http\Requests\Request;
use App\CustomComponents\Responses;

class CreateUserRequest extends Request
{   

    // Generating JSON response
    protected $apiResponse;


    public function __construct(\CustomComponents\Responses\ApiResponse $apiResponse)
    {
       // Adding the api response class
       $this->apiResponse = $apiResponse;
    } 

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Source:
        // https://laracasts.com/discuss/channels/requests/laravel-5-validation-request-how-to-handle-validation-on-update
        
        $user = User::find($this->users);

        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'name'      => 'required|min:3',
                    'email'     => 'required|email|unique:users',
                    'password'  => 'required|min:5',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user.name'     => 'required',
                    'user.email'    => 'required|email|unique:users,email,'.$user->id,
                    'user.password' => 'required|confirmed',
                ];
            }
            default:break;
        }
    }

    public function response(array $errors)
    {
        return $this->apiResponse->json(400, "", $errors);
    }
}
