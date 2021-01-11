<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;
use DB;


class SubCategory extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {

       //$products = DB::table('products_to_categories')->where('categories_id', $this->categories_id)->get();

        return [
                
                'id' => $this->categories_id,
                'category_name' => $this->desc->categories_name,
                'categories_type' => $this->categories_type,
                'image' => 'https://cdn.pixabay.com/photo/2017/01/30/14/20/shopping-cart-2020929_960_720.png',
                'slug' => $this->categories_slug,
                'status' => $this->categories_status,
                'icon' => $this->categories_icon,
                //'sub_categories' => new CategoryCollection($sub_cat),
                //'products' => new ProductCollection($products),
        ];
    }
}
  
