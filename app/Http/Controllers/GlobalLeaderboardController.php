<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GlobalLeaderboardController extends Controller
{
  public function getData(){
    $type = "player";
    $region = "";
    $season = getLatestSeason();
    $game_type = 1;

    $leaderboardData = \LeaderboardData::instance($game_type, $season, $region, $type);
    $return_data = $leaderboardData->getLeaderboardData();

    $return_array["data"] = $return_data;
    return $return_array;
  }
}