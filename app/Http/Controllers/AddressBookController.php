<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressBook as AddressBookResource;
use Illuminate\Support\Facades\Auth;
use App\Models\AddressBook;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use stdClass;


class AddressBookController extends Controller
{
    public $successStatus = 200;
   /* Address List API */
    public function index()
    {
        if(Auth::guard('api')->check()){

            $user_id = auth('api')->user()->id;
            $address = AddressBook::where('user_id', $user_id)->get();

            return response()->json([
                'success' => true,
                'message' => 'Address List',
                'data' => AddressBookResource::collection($address),
            ], $this-> successStatus);
        }
        else{
            return response()->json([
                'success' => false,
                'message'=>'Not Authorised',
                'data' => new stdClass(),
            ], 401);
        }
    }

    /* Address Store API */
    public function store(Request $request)
    {
        if(Auth::guard('api')->check()){
            $user_id = auth('api')->user()->id;

            $address = new AddressBook;
            $address->entry_firstname = $request->input('entry_firstname');
            $address->entry_lastname = $request->input('entry_lastname');
            $address->entry_street_address = $request->input('entry_street_address');
            $address->entry_postcode = $request->input('entry_postcode');
            $address->entry_city = $request->input('entry_city');
            $address->entry_state = $request->input('entry_state');
            $address->entry_country_id = '99';
            $address->user_id = $user_id;
            $address->save();

            return response()->json([
                'success' => true,
                'message' => 'Address Added',
                'data' => new AddressBookResource($address),
            ],  $this-> successStatus); 
        }

        else{
            return response()->json([
                'success' => false,
                'message'=>'Not Authorised',
                'data' => new stdClass(),
            ], 401);
        }
    }

    /* Update Address API */
    public function update(Request $request, $id)
    {
         if(Auth::guard('api')->check()){
            $user_id = auth('api')->user()->id;
            if(AddressBook::where('address_book_id', $id)->where('user_id', $user_id)->count() > 0){
                $address = AddressBook::where('address_book_id', $id)->where('user_id', $user_id)->firstOrFail();
                $address->entry_firstname = $request->input('entry_firstname');
                $address->entry_lastname = $request->input('entry_lastname');
                $address->entry_street_address = $request->input('entry_street_address');
                $address->entry_postcode = $request->input('entry_postcode');
                $address->entry_city = $request->input('entry_city');
                $address->entry_state = $request->input('entry_state');
                $address->entry_country_id = '99';
                $address->user_id = $user_id;
                $address->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Address Updated',
                    'data' => new AddressBookResource($address),
                ],  $this-> successStatus);
              }

            else{
                return response()->json([
                    'success' => false,
                    'message'=>'Address not Found',
                    'data' => new stdClass(),
                ], 401);
            }

        }

        else{
            return response()->json([
                'success' => false,
                'message'=>'Not Authorised',
                'data' => new stdClass(),
            ], 401);
        }
    }

    /* Delete Address API */
    public function destroy($id)
    {
        if(Auth::guard('api')->check()){
        $user_id = auth('api')->user()->id;
           if(AddressBook::where('address_book_id', $id)->where('user_id', $user_id)->count() > 0){
                AddressBook::where('address_book_id', $id)->where('user_id', $user_id)->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Address Deleted',
                    'data' => new stdClass()
                ], $this-> successStatus);
            }

            else{
                return response()->json([
                    'success' => false,
                    'message'=>'Address not Found',
                    'data' => new stdClass(),
                ], 401);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
            'data' => new stdClass()
        ], 401);
    }
}
