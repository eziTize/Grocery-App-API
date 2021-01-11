<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = "customers_basket";
    public $timestamps = false;

     protected $fillable = [
        'customers_id',
        'products_id',
        'customers_basket_quantity',
        'final_price',
        'wash_type',
    ];

}
