<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Cart;
use App\Http\Requests;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\CategorySingle;

class CategoryController extends Controller
{
    public $successStatus = 200;

    // List Categories with Sub Categories
    public function index()
    {
        $categories = Category::all();
        $cart_count = 0;

        if(Auth::guard('api')->check()){
            $user_id = auth('api')->user()->id;
            $cart_count = Cart::where('customers_id', $user_id)->where('customers_basket_quantity', '>', '0')->sum('customers_basket_quantity');
        }
        if($categories){
             return response()->json([
                    'success' => true,
                    'message'=>'All Categories',
                    'data'=> CategoryResource::collection($categories),
                    'cart_count' => $cart_count,
                ], $this-> successStatus);
          }

         else{ 
            return response()->json([
                'success' => false,
                'message'=>'No Categories Found',
                'data' => array(),
                'cart_count' => $cart_count,
            ], 401);
        }
         
    }


    //List Categories/SubCategories with Products

    public function show($id)
    {
        $category = Category::where('categories_id', $id)->firstOrFail();
        $cart_count = 0;

        if(Auth::guard('api')->check()){
            $user_id = auth('api')->user()->id;
            $cart_count = Cart::where('customers_id', $user_id)->where('customers_basket_quantity', '>', '0')->sum('customers_basket_quantity');
        }
        if($category){
             return response()->json([
                    'success' => true,
                    'message'=>'Product List for categories_id: '.$id,
                    'data'=> new Categorysingle($category),
                    'cart_count' => $cart_count,
                ], $this-> successStatus);
          }
        else{ 
            return response()->json([
                'success' => false,
                'message'=>'No Category Found',
                'data' => array(),
                'cart_count' => $cart_count,
            ], 401);
        }
    }
}
