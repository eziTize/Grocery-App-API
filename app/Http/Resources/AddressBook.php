<?php

namespace App\Http\Resources;

use DB;
use Auth;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressBook extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $country = DB::table('countries')->where('countries_id', $this->entry_country_id)->first();

        return [
                
                'address_book_id' => $this->address_book_id,
                'user_id' => $this->user_id,
                'entry_firstname' => $this->entry_firstname,
                'entry_lastname' => $this->entry_lastname,
                'entry_street_address' => $this->entry_street_address,
                'entry_postcode' => $this->entry_postcode,
                'entry_city' => $this->entry_city,
                'entry_state' => $this->entry_state,
                'entry_country' => $country->countries_name,

        ];
    }
}
