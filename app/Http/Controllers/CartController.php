<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use stdClass;
use App\Http\Resources\CartProducts;


class CartController extends Controller
{
    public $successStatus = 200;

   // Cart List
    public function index()
    {

        $cart_total = 0.00;
        $dlv_fee = 0.00;
        $discount = 0.00;
        $count = 0;

    if(Auth::guard('api')->check()){

        $user_id = auth('api')->user()->id;
        $product = Product::get();
        $carts = Cart::where('customers_id', $user_id)->get();
        $count = Cart::where('customers_id', $user_id)->where('customers_basket_quantity', '>', '0')->sum('customers_basket_quantity');
 

        foreach( $carts as $cart){

            $prod = Product::where('products_id', $cart->products_id)->firstOrFail();
            $total = ($prod->products_price) * ($cart->customers_basket_quantity);
            $cart_total =  $cart_total + $total;

        }

        $final_total = ($cart_total + $dlv_fee) - $discount;

        if($carts){
            return response()->json([
                'success' => true,
                'message'=>'Display Cart',
                'data'=> CartProducts::collection($carts),
                'sub_total' => number_format($cart_total, 2),
                'dlv_fee' => number_format($dlv_fee, 2),
                'discount' => number_format($discount, 2),
                'final_total' => number_format($final_total, 2),
                'cart_count' => $count,

            ], $this-> successStatus);
        }

            else{ 
            return response()->json([
                'success' => false,
                'message'=>'No products in cart',
                'data' => new stdClass(),
                'sub_total' => 0.00,
                'dlv_fee' => 0.00,
                'discount' => 0.00,
                'final_total' => 0.00,
                'cart_count' => $count,
                ], 401);
            }
        }

        else {
            return response()->json([
                'success' => false,
                'message'=>'Not Authorised',
                'data' => new stdClass(),
                'sub_total' => 0.00,
                'dlv_fee' => 0.00,
                'discount' => 0.00,
                'final_total' => 0.00,
                'cart_count' => $count,
            ], 401);
        }

    }

     /*
    |------------------------------------------------------------------
    |   Add to cart
    |------------------------------------------------------------------
    */
    public function add_to_cart(Request $request, $id)
    {

    $product = Product::where('products_id', $id)->firstOrFail();
    $product_id = $product->products_id;

    if(Auth::guard('api')->check()){

        $user_id = auth('api')->user()->id;

        if(Cart::where('customers_id', $user_id)->where('products_id', $product_id)->count() > 0){

                /*$data = Cart::where('customers_id', $user_id)->where('products_id', $product_id)->firstOrFail();
                $quantity = $data->customers_basket_quantity;
                $data->customers_basket_quantity =  $quantity + 1;
                $data->save();*/
            return response()->json([
                'success' => true,
                'message'=>'Already Added',
                'data'=> new stdClass(),
            ], $this-> successStatus);
        }

        else{
                $data = new Cart;
                $data->customers_id = $user_id;
                $data->products_id = $product_id;
                $data->customers_basket_quantity = 1;
                $data->final_price = $product->products_price;
                $data->customers_basket_date_added = carbon::now()->toDateString();
                $data->session_id = 'N/A';
                if(! $request->input('wash_type') == ''){
                    $data->wash_type = $request->input('wash_type');
                }
                else{
                    $data->wash_type = 'N/A';
                }
                $data->save();

                return response()->json([
                'success' => true,
                'message'=>'Added To Cart',
                'data'=> new CartProducts($data),
            ], $this-> successStatus);
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


    /*
    |------------------------------------------------------------------
    |   Remove from cart
    |------------------------------------------------------------------
    */
    public function remove_from_cart($id)
    {

       $product = Product::where('products_id', $id)->firstOrFail();
       $product_id = $product->products_id;

    if(Auth::guard('api')->check()){

        $user_id = auth('api')->user()->id;

         if(Cart::where('products_id', $product_id)->where('customers_id', $user_id)->count() > 0 && $product_id){

            Cart::where('products_id', $product_id)->where('customers_id', $user_id)->delete();

            return response()->json([
                'success' => true,
                'message'=>'Removed from cart',
                'data'=> new stdClass(),
            ], $this-> successStatus);
        }

        else{

            return response()->json([
                'success' => true,
                'message'=>'Product not found in cart',
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


     /*
    |------------------------------------------------------------------
    |   Increase Qunatity in Cart
    |------------------------------------------------------------------
    */

    public function incr_qty(Request $request, $id)
    {

        $product = Product::where('products_id', $id)->firstOrFail();
        $product_id = $product->products_id;
        
        if(Auth::guard('api')->check()){
        $user_id = auth('api')->user()->id;

        if(Cart::where('products_id', $product_id)->where('customers_id', $user_id)->count() > 0 && $product_id){


         $data = Cart::where('products_id', $product_id)->where('customers_id', $user_id)->increment('customers_basket_quantity');
         $cart = Cart::where('products_id', $product_id)->where('customers_id', $user_id)->first();

         return response()->json([
                'success' => true,
                'message'=>'Quantity Increased',
                'data'=> new CartProducts($cart),
            ], $this-> successStatus);

        }

     else{

         return response()->json([
                'success' => false,
                'message'=>'Not Found',
                'data'=> new stdClass(),
            ], 401);

     }

    }
        else{

            return response()->json([
                'success' => false,
                'message'=>'Unauthorised',
                'data'=> new stdClass(),
            ], 401);
        }


    }

    /*
    |------------------------------------------------------------------
    |   Decrease Qunatity in Cart
    |------------------------------------------------------------------
    */

    public function decr_qty(Request $request, $id)
    {

        $product = Product::where('products_id', $id)->firstOrFail();
        $product_id = $product->products_id;
        
        if(Auth::guard('api')->check()){
        $user_id = auth('api')->user()->id;

        if(Cart::where('products_id', $product_id)->where('customers_id', $user_id)->count() > 0 && $product_id){

        $cart = Cart::where('products_id', $product_id)->where('customers_id', $user_id)->first();

        if( $cart->customers_basket_quantity > 1 ){

         $data = Cart::where('products_id', $product_id)->where('customers_id', $user_id)->decrement('customers_basket_quantity');
         $cart = Cart::where('products_id', $product_id)->where('customers_id', $user_id)->first();
        
        return response()->json([
                'success' => true,
                'message'=>'Quantity Decreased',
                'data'=>  new CartProducts($cart),
            ], $this-> successStatus);

         }

         else{

                return response()->json([
                'success' => true,
                'message'=>'Cannot Decrease',
                'data'=> Cart::where('products_id', $product_id)->where('customers_id', $user_id)->first(),
            ], $this-> successStatus);

         }
        }


         else{

                return response()->json([
                'success' => false,
                'message'=>'Not found',
                'data'=> new stdClass(),
            ], 401);

         }

        }

        else{

            return response()->json([
                'success' => false,
                'message'=>'Unauthorised',
                'data'=> new stdClass(),
            ], 401);
        }
        
    
    }

 
}
