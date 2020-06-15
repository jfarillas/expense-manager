<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\ResponseController as ResponseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Validator;
use DB;

class UserController extends ResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::with('roles')->get();
        return $this->sendResponseData($users, $request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Int $id)
    {
        $users = User::with('roles')
        ->where('id', $id)
        ->get();
        return $this->sendResponseData($users, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Int $id)
    {
        $user = User::findOrFail($id); //Get role specified by id

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        //Retreive the name, email and password fields
        // Encrypt password
        $input = $request->only(['name', 'email', Hash::make('password')]); 

        // Update user
        $user->fill($input)->save();

        // Synching role to a current user
        if (isset($request['role'])) {
             // If one or more role is selected associate user to roles       
            $user->syncRoles([$request['role']]);           
        }

        if ($user) {
            $message = "User updated successfully.";
            return $this->sendResponse($message, $request, $id);
        } else {
            $error = "Sorry! Updating user is not successful.";
            return $this->sendError($error, 401);
        }
    }

    /**
     * Update the specified password in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, Int $id)
    {
        $user = User::findOrFail($id); //Get role specified by id

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $user = Auth::user();
        $user->password = Hash::make($request['password']);
        $user->save();

        if ($user) {
            $message = "User's password updated successfully.";
            return $this->sendResponse($message, $request, $id);
        } else {
            $error = "Sorry! Updating user's password is not successful.";
            return $this->sendError($error, 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Int $id)
    {
        $user = User::where('id', $id)
        ->delete();

        if ($user) {
            $message = "User deleted successfully.";
            return $this->sendResponse($message, $request, $id);
        } else {
            $error = "Sorry! Deleting user is not successful.";
            return $this->sendError($error, 401);
        }
    }
}
