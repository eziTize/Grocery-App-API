<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category as CategoryMain;
use App\Http\Resources\Product;
use DB;

class CategorySingle extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */


    public function toArray($request)
    {
        $products = array();
        $sub_cat = array();

        if(DB::table('categories_description')->where('categories_id', $this->categories_id)->count() > 0){
            $desc = DB::table('categories_description')->where('categories_id', $this->categories_id)->first();

        }


        if(DB::table('products_to_categories')->where('categories_id', $this->categories_id)->count() > 0){
            $products = DB::table('products_to_categories')->where('categories_id', $this->categories_id)->get();

        }

        if(CategoryMain::where('parent_id', $this->categories_id)->count() > 0){
            $sub_cats = CategoryMain::where('parent_id', $this->categories_id)->get();

            foreach ($sub_cats as $sub_c) {


            $s_desc = DB::table('categories_description')->where('categories_id', $sub_c->categories_id)->first();
            
            $sub_cat = [
                    'categories_id' => $sub_c->categories_id,
                    'category_name' => $s_desc->categories_name,
                    'categories_type' => $sub_c->categories_type,
                    'image' => 'https://cdn.pixabay.com/photo/2017/01/30/14/20/shopping-cart-2020929_960_720.png',
                    'slug' => $sub_c->categories_slug,
                    'status' => $sub_c->categories_status,
                    'icon' => $sub_c->categories_icon,
            ];

           }
        }

            if($products){

                if($sub_cat){

                    return [
                    'categories_id' => $this->categories_id,
                    'category_name' => $desc->categories_name,
                    'categories_type' => $this->categories_type,
                    'image' => 'https://cdn.pixabay.com/photo/2017/01/30/14/20/shopping-cart-2020929_960_720.png',
                    'slug' => $this->categories_slug,
                    'status' => $this->categories_status,
                    'icon' => $this->categories_icon,
                    'sub_status' => 1,
                    'products' => Product::collection($products),
                 ];

                }

                else {

                     return [
                    'categories_id' => $this->categories_id,
                    'category_name' => $desc->categories_name,
                    'categories_type' => $this->categories_type,
                    'image' => 'https://cdn.pixabay.com/photo/2017/01/30/14/20/shopping-cart-2020929_960_720.png',
                    'slug' => $this->categories_slug,
                    'status' => $this->categories_status,
                    'icon' => $this->categories_icon,
                    'sub_status' => 0,
                    'products' => Product::collection($products),
                 ];

                }

                 
                }
            else {

                if($sub_cat){

                    return [
                    'categories_id' => $this->categories_id,
                    'category_name' => $desc->categories_name,
                    'categories_type' => $this->categories_type,
                    'image' => 'https://cdn.pixabay.com/photo/2017/01/30/14/20/shopping-cart-2020929_960_720.png',
                    'slug' => $this->categories_slug,
                    'status' => $this->categories_status,
                    'icon' => $this->categories_icon,
                    'sub_status' => 1,
                    'products' => $products,
                 ];

                }

                else {

                     return [
                    'categories_id' => $this->categories_id,
                    'category_name' => $desc->categories_name,
                    'categories_type' => $this->categories_type,
                    'image' => 'https://cdn.pixabay.com/photo/2017/01/30/14/20/shopping-cart-2020929_960_720.png',
                    'slug' => $this->categories_slug,
                    'status' => $this->categories_status,
                    'icon' => $this->categories_icon,
                    'sub_status' => 0,
                    'products' => $products,
                 ];

                }
            }
            
    }
}
