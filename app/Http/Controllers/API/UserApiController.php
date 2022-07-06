<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\UserApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class UserApiController extends Controller
{
    public function login( Request $request )
    {
        $validator = Validator::make( $request->all( ), [
            'email'             =>  'required|string|email',
            'password'          =>  'required|string|min:8|max:32',
        ]);

        if ($validator->fails()){
            $errorString = "";
            foreach ( $validator->errors( )->getMessages( ) as $key => $errorBag){
                foreach ( $errorBag as $key => $error ) {
                    $errorString .= $error . " ";
                }
            }

            return response()->json([
                'error'         =>  true,
                'message'       =>  rtrim($errorString, " "),
                'data'          =>  null
            ]);
        }

        $email      = $request->get( 'email' );
        $password   = $request->get( 'password' );
        //attempt for login
        $user_jwt_token = auth('apiuser')->attempt(['email'=>$email,'password'=>$password]);

        if($user_jwt_token){
            $user_data          =   auth('apiuser')->user(); //user information

            return response()->json([
                'error'         =>  false,
                'message'       =>  "Login Successful",
                'data'          =>  $user_data
            ]);
        }else{ //if login fails then send back error

            return response()->json([
                'error'         =>  true,
                'message'       =>  "Incorrect Email or Password",
                'data'          =>  null
            ]);
        }



    }

    public function register( Request $request ) {

        $validator = Validator::make( $request->all( ), [
            'name'              =>  'required|string|min:3|max:32',
            'email'             =>  'sometimes|email|unique:users',
            'password'          =>  'required|string|min:8|max:32|confirmed'
        ] );

        //if validation fail return errors
        if ($validator->fails()){
            $errorString = "";
            foreach ($validator->errors( )->getMessages( ) as $key => $errorBag ){
                foreach ( $errorBag as $key => $error ) {
                    $errorString .= $error . " ";
                }
            }
            return response()->json([
                'error'         =>  true,
                'message'       =>  rtrim($errorString, " "),
                'data'          =>  null
            ]);
        }

        $data = $request->all();
        $data['password'] = Hash::make($request->get('password'));
        $user = UserApi::create( $data); //create user

        if($user){ //if created then create token and return success response
            $token = JWTAuth::fromUser($user);
            $user ->update(['jwt_auth_token'=>$token] );
            return response()->json([
                'error'         =>  false,
                'message'       =>  "User Registered Successfully",
                'data'          =>  $user
            ]);
        }
    }

}
