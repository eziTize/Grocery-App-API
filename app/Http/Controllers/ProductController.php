<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests;


class ProductController extends Controller
{
    public $successStatus = 200;

    // List Products
    public function indexProduct()
    {
        
      $products = Product::where('type', 'Product')->get();
      if($products) {
            return response()->json([
            'success' => true,
            'message'=>'All Products',
            'data'=> $products,
        ],  $this-> successStatus);
      }

    else{ 
            return response()->json([
                'success' => false,
                'message'=>'Product Not Found',
                'data' => array(),
            ], 401);
        } 
     

    }


    // List Categories
    public function indexCategories()
    {
    $categories = Category::all();
    if($categories){
         return response()->json([
                'success' => true,
                'message'=>'All Categories',
                'data'=> $categories,
            ], $this-> successStatus);
      }
         
    }
}
