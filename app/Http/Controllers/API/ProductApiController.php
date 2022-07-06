<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\CommonMethods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductApiController extends Controller
{
    use CommonMethods;

    public function list(){
        $products = Product::get();
        if($products->count() > 0){
            return $this->apiJsonResponse(1,'Products are available', $products);
        }

        return $this->apiJsonResponse(0,'Products are not available', []);
    }

    public function singleDetails(Request $request){

        $validator = Validator::make( $request->all( ), [
            'product_id'             =>  'required|integer',
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



        $product = Product::find($request->get('product_id'));

        if($product->count() >0 ){
            return $this->apiJsonResponse(1,'Product details found successfully', $product);
        }

        return $this->apiJsonResponse(0,'Product details are not found, Please try again', []);

    }

    public function create(Request $request){
        $validator = Validator::make( $request->all( ), [
            'name'             =>  'required|string|min:3|max:50',
            'category'          =>  'required|string|min:3|max:32',
            'description'          =>  'required|string|min:3|max:1000',
#            'avatar'          =>  'sometimes',
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


        $data = $request->all();

        $create = Product::create($data);
        if($create){
            return $this->apiJsonResponse(1,'Product created successfully', $create);
        }

        return $this->apiJsonResponse(0,'Product not created, Please try again', []);

    }

    public function destroy(Request $request){

        $validator = Validator::make( $request->all( ), [
            'product_id'             =>  'required|integer',
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



        $delete = Product::find($request->get('product_id'));

        if($delete->count() >0 ){
            $delete->delete();
            return $this->apiJsonResponse(1,'Product deleted successfully', $delete);
        }

        return $this->apiJsonResponse(0,'Product not deleted, Please try again', []);

    }


}
