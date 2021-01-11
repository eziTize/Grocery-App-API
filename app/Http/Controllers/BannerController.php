<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BannerController extends Controller
{
    public $successStatus = 200;

    public function mainBanner()
    {
    	return response()->json([
                'success' => true,
                'message'=>'Main Banners',
                'data'=> [
                	'img1' => 'https://cdn.pixabay.com/photo/2017/09/07/13/42/cheese-2725235_960_720.jpg',
                	'img2' => 'https://cdn.pixabay.com/photo/2017/09/07/13/42/cheese-2725235_960_720.jpg',
                	'img3' => 'https://cdn.pixabay.com/photo/2017/09/07/13/42/cheese-2725235_960_720.jpg',
                	'img4' => 'https://cdn.pixabay.com/photo/2017/09/07/13/42/cheese-2725235_960_720.jpg'
                ],
            ], $this-> successStatus);
    }

    public function offerBanner()
    {
    	return response()->json([
                'success' => true,
                'message'=>'Offer Banners',
                'data'=> [
                	'img1' => 'https://cdn.pixabay.com/photo/2019/11/13/12/54/background-4623655_960_720.png',
                	'img2' => 'https://cdn.pixabay.com/photo/2019/11/13/12/54/background-4623655_960_720.png',
                	'img3' => 'https://cdn.pixabay.com/photo/2019/11/13/12/54/background-4623655_960_720.png',
                	'img4' => 'https://cdn.pixabay.com/photo/2019/11/13/12/54/background-4623655_960_720.png'

                ],
            ], $this-> successStatus);
    }
}
