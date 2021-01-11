<?php
namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Cookie;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\User;
use Validator;


class UserController extends Controller 
{
public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user();
            $user->save();

            return response()->json([
            'success' => true,
            'message'=>'Login Successful',
            'data'=> $user,
            'token' => $user->createToken('hh_user')-> accessToken, 
            ], $this-> successStatus); 
        } 
        else{ 
            return response()->json([
                'success' => false,
                'message'=>'Unauthorised',
                'data' => array(),
                'token' => '',
            ], 401);
        } 
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        
        $user = Validator::make($request->all(), [ 
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'reg_type' => 'required',
            'phone' => 'unique:users|string|max:13',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'string|min:6',
        ]);
        

        if ($user->fails()) { 
            return response()->json([
                'success' => false,
                'message'=>$user->errors(),
                'data' => array(),
                'token' => ''
            ], 401);            
        }


        $user = new User;
        $user->role_id = '2';
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->gender = $request->input('gender');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->reg_type = $request->input('reg_type');
        $user->password = $request->input('password');
        $user->phone_verified = $request->input('phone_verified');
        $user->save();

        
    return response()->json([
        'success' => true,
        'message' => 'Registration Completed',
        'data' => $user,
        'token' => $user->createToken('hh_user')-> accessToken,
      ], $this-> successStatus);
    }
/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 

            return response()->json([
            'success' => true,
            'message' => 'Profile Details',
            'data' => $user,
          ], $this-> successStatus);
        
    } 

public function update(Request $request)
{

    $user = Auth::user();

    $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'string|email|max:255|unique:users,email,'.auth()->id(),
            'phone' => 'string|max:13|unique:users,phone,'.auth()->id(),
        ]);
    
    $user->first_name = $request->input('first_name');
    $user->last_name = $request->input('last_name');
    $user->gender = $request->input('gender');
    $user->email = $request->input('email');
    $user->phone = $request->input('phone');
    
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Profile Updated',
        'data' => $user,
      ], $this-> successStatus);
 }



    public function passUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => 'string|min:6',

        ]);

        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response()->json([
        'success' => true,
        'message' => 'Password Updated',
        'data' => $user,
      ], $this-> successStatus);

    }


    public function imageUpload(Request $request)
    {
        $data = request()->validate([
            'image' => ''
        ]);

        $image = $data['image']->store('user-images', 'public');

        Image::make($data['image'])
            //->fit($data['width'], $data['height'])
            ->save(public_path('/user-images/'.$data['image']->hashName()));
            //->save(storage_path('app/public/user-images/'.$data['image']->hashName()));

        $userImage = auth()->user()->images()->create([
            'path' => $image,

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Image Uploaded',
            'data' => $userImage,
          ], $this-> successStatus);
    }


    public function logout(Request $request)
    {
            $a_token = Auth::user()->token();
            $user = Auth::user();
            $a_token->revoke();
            $user->save();


            return response()->json([
            'success' => true,
            'message' => 'Logged Out',
            'data' => $user,
          ], $this-> successStatus);

    }
   
}