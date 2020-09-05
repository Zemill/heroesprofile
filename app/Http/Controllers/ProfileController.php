<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;

class ProfileController extends Controller
{
  public function show(){
    return view('Profile.home',
    [
      'title' => 'Profile', // Page title
      'paragraph' => 'Profile Page', // Summary paragraph
      'page' => 'profile',
      'inputUrl' => 'getProfileData'
    ]);
  }


  public function getData(Request $request){
    $blizz_id = $request["blizz_id"];
    $region = $request["region"];
    $game_type = $request["game_type"];
    $season = $request["season"];

    $profile_data = new \ProfileData($blizz_id, $region, $game_type, $season);
    $return_data = $profile_data->getPlayerProfileData();
    return $return_data;
  }
}
