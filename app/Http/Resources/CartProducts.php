<?php

namespace App\Http\Resources;

use DB;
use Auth;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProducts extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $products = Product::where('products_id', $this->products_id)->first();
        $stock = 0;
        $fav = 0;
        $desc = '';
        $name = '';
        $unit_total = ($products->products_price) * ($this->customers_basket_quantity);

        if(DB::table('inventory')->where('products_id', $this->products_id)->count() > 0){
            $stock = DB::table('inventory')->where('products_id', $this->products_id)->sum('stock');
             }

        if(DB::table('products_description')->where('products_id', $this->products_id)->where('language_id', 1)->count() > 0){

            $p_desc = DB::table('products_description')->where('products_id', $this->products_id)->where('language_id', 1)->first();
            $desc = $p_desc->products_description;
            $name = $p_desc->products_name;
        }

        if(Auth::guard('api')->check()){
            $user_id = auth('api')->user()->id;
            if(DB::table('liked_products')->where('liked_products_id', $this->products_id)->where('liked_customers_id', $user_id)->count() > 0){ 
                $fav = 1;
             }
        }

        return [
                'products_id' => $products->products_id,
                'products_name' => $name,
                'products_description' => $desc,
                'wash_type' => $this->wash_type,
                'products_image' => 'https://cdn.pixabay.com/photo/2017/01/30/14/20/shopping-cart-2020929_960_720.png',
                'products_price' =>  number_format($products->products_price, 2),
                'old_price' => number_format($products->old_price, 2),
                'products_weight' => $products->products_weight,
                'products_weight_unit' => $products->products_weight_unit,
                'products_status' => $products->products_status,
                'is_feature' => $products->is_feature,
                'type' => $products->type,
                'products_slug' => $products->products_slug,
                'stock' => $stock,
                'fav' => $fav,
                'quantity' => $this->customers_basket_quantity,
                'unit_total' => number_format($unit_total, 2),
        ];
    }
}
