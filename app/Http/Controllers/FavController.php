<?php

namespace App\Http\Controllers;

use Auth;
use stdClass;
use App\Models\Fav;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\FavProducts;

class FavController extends Controller
{

      public $successStatus = 200;

    /**
     Customer Wishlist
     **/
    public function index()
    {
        $fav = array();
        if(Auth::guard('api')->check()){
            $user_id = auth('api')->user()->id;
            $fav = Fav::where('liked_customers_id', $user_id)->get();
            return response()->json([
                'success' => true,
                'message'=>'Customer Wishlist',
                'data' => FavProducts::collection($fav),
            ], 200);
        }

        else{

            return response()->json([
                'success' => false,
                'message'=>'Not Authorised',
                'data' => new stdClass(),
            ], 401);

        }         
    }



    public function add(Request $request, $id){

          $product = Product::where('products_id', $id)->firstOrFail();
          $product_id = $product->products_id;

          if(Auth::guard('api')->check()){
          $user_id = auth('api')->user()->id;

          if(Fav::where('liked_products_id', $id)->where('liked_customers_id', $user_id)->count() == 0 && $product_id){

                $fav = new Fav;
                $fav->liked_products_id = $product_id;
                $fav->liked_customers_id = $user_id;
               // $fav->status = 1;
                $fav->save();

                return response()->json([
                'success' => true,
                'message'=>'Added to Wishlist',
                'data' => new FavProducts($fav),
            ], 200);

          }

          else{

                return response()->json([
                'success' => true,
                'message'=>'Already Added',
                'data' => new stdClass(),
            ], 200);

          }
        }

        else {

             return response()->json([
                'success' => false,
                'message'=>'Not Authorised',
                'data' => new stdClass(),
            ], 401);

          }

    }


    public function remove(Request $request, $id){

          $product = Product::where('products_id', $id)->firstOrFail();
          $product_id = $product->products_id;

        if(Auth::guard('api')->check()){
          $user_id = auth('api')->user()->id;

          if(Fav::where('liked_products_id', $product_id)->where('liked_customers_id', $user_id)->count() > 0 && $product_id){

                Fav::where('liked_customers_id', $user_id)->where('liked_products_id', $product_id)->delete();

                return response()->json([
                'success' => true,
                'message'=>'Removed from Wishlist',
                'data' => new stdClass(),
            ], 200);

          }

          else{

                return response()->json([
                'success' => true,
                'message'=>'Already Removed',
                'data' => new stdClass(),
            ], 200);

          }
        }


          else {

             return response()->json([
                'success' => false,
                'message'=>'Not Authorised',
                'data' => new stdClass(),
            ], 401);

          }


    }


     /**
    Wishlist Add/Remove
    
    public function toggle(Request $request, $id){

        $product = Product::where('products_id', $id)->firstOrFail();
        $product_id = $product->products_id;
        if(Auth::guard('api')->check()){
            $user_id = auth('api')->user()->id;

            if(Fav::where('liked_products_id', $id)->where('liked_customers_id', $user_id)->where('status', 1)->count() > 0){

                $fav = Fav::where('liked_customers_id', $user_id)->where('liked_products_id', $id)->first();
                $fav->status = 0;
                $fav->save();

                return response()->json([
                'success' => true,
                'message'=>'Removed from Wishlist',
                'data' => FavProducts::collection($fav),
            ], 200);

            }

            elseif(Fav::where('liked_products_id', $id)->where('liked_customers_id', $user_id)->where('status', 0)->count() > 0) {

                $fav = Fav::where('liked_customers_id', $user_id)->where('liked_products_id', $id)->first();
                $fav->status = 1;
                $fav->save();

                return response()->json([
                'success' => true,
                'message'=>'Added to Wishlist',
                'data' => FavProducts::collection($fav),
            ], 200);
            }

            else{
                $fav = new Fav;
                $fav->liked_products_id = $id;
                $fav->liked_customers_id = $user_id;
                $fav->status = 1;
                $fav->save();

                return response()->json([
                'success' => true,
                'message'=>'Added to Wishlist',
                'data' => FavProducts::collection($fav),
            ], 200);
            }
            
            
        }
    }
     **/
}
