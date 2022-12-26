<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Http\Resources\User as UserResource;

class UserAuthController extends ResponseController
{
    public function register(Request $request)
    {
        
        //First validating a user
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }


        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('REST_API')->accessToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User is registered successfully.');
    
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('REST_API')->accessToken; 
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User logged in successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    //CRUD API's
    public function userInfo()
    {
        $user = User::all();
       
        return $this->sendResponse(UserResource::collection($user), 'Users Retrieved Successfully.');
        
    }
}
