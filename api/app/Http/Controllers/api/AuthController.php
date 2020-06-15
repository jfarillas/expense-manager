<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\ResponseController as ResponseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Hash;
use App\User;
use Validator;

class AuthController extends ResponseController
{
    /* //create user
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'roles' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $user->assignRole($request->input('roles'));

        if($user) {
            $success['token'] =  $user->createToken('token')->accessToken;
            $success['message'] = "User Registration successful.";
            return $this->sendResponse($success);
        }
        else{
            $error = "Sorry! User Registration is not successful.";
            return $this->sendError($error, 401); 
        }
        
    } */
    
    //login
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {

            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('token')->accessToken;
                $response = ['token' => $token];
                return response($response, 200);
            } else {
                $response = ['message' => 'Password mismatch', 'status_code' => 422];
                return response($response, 422);
            }

        } else {
            $response = ['message' => 'User does not exist', 'status_code' => 422];
            return response($response, 422);
        }
    }

    //logout
    public function logout(Request $request)
    {
        
        $token = $request->user()->token();
        $token->revoke();

        $response = ['message' => 'You have been succesfully logged out!', 'status_code' => 200];
        return response($response, 200);
           
    }

    //getuser
    public function getUser(Request $request)
    {
        //$id = $request->user()->id;
        $user = User::all();
        if ($user) {
            return $this->sendResponseData($user, $request);
        }
        else{
            $error = "User not found";
            return $this->sendResponse($error);
        }
    }
}
