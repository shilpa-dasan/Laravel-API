<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\API\ResponseController as ResponseController;

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
}
