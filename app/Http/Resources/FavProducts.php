<?php

namespace App\Http\Resources;

use DB;
use Auth;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class FavProducts extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $products = Product::where('products_id', $this->liked_products_id)->first();

        $stock = 0;

        if(DB::table('inventory')->where('products_id', $this->liked_products_id)->count() > 0){
            $stock = DB::table('inventory')->where('products_id', $this->liked_products_id)->sum('stock');
             }
        //return parent::toArray($request);

        return [
                'products_id' => $products->products_id,
                'products_image' => 'https://cdn.pixabay.com/photo/2017/01/30/14/20/shopping-cart-2020929_960_720.png',
                'products_price' => $products->products_price,
                'old_price' => $products->old_price,
                'products_weight' => $products->products_weight,
                'products_weight_unit' => $products->products_weight_unit,
                'products_status' => $products->products_status,
                'is_feature' => $products->is_feature,
                'type' => $products->type,
                'products_slug' => $products->products_slug,
                'stock' => $stock,
                'fav' => 1,
        ];
    }
}
