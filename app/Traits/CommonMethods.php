<?php

namespace App\Traits;

trait CommonMethods{

    /**
     * return unique name for files
     * @return string
     */
    public function uniqueName(){

        $uniqueness =   uniqid(microtime(),true);
        $replacement=  str_replace(' ','_',$uniqueness);
        return uniqid($replacement,true);
    }

    public function apiJsonResponse($success = 1,  $message=null, $data= [], $exception_error_code=400){

        $response['success']         =   $success;
        $response['message']        =    $message;
        $response['data']           =    $data;

        return response()->json($response,200,[],JSON_NUMERIC_CHECK);
    }


}

