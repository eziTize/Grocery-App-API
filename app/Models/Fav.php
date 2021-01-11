<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fav extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "liked_products";


    protected $fillable = [
        'liked_products_id',
        'liked_customers_id',
        //'status'
    ];

    protected $dates = ['date_liked'];

}
