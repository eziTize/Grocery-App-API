<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
    use HasFactory;

     protected $table = "address_book";
     public $timestamps = false;

     protected $fillable = [
        'user_id',
        'entry_firstname',
        'entry_lastname',
        'entry_street_address',
        'entry_postcode',
        'entry_city',
        'entry_state',
        'entry_country_id',
    ];
}
