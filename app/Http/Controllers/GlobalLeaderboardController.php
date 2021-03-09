<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class GlobalLeaderboardController extends Controller
{
  private $columns = array(
    [
      "key" => "rank",
      "label" => "Rank",
      "stickyColumn" => true
    ],
    [
      "key" => "split_battletag",
      "label" => "Battletag"
    ],
    [
      "key" => "region",
      "label" => "Region"
    ],
    [
      "key" => "win_rate",
      "label" => "Win Rate",
      "sortable" => true
    ],
    [
      "key" => "rating",
      "label" => "Heroes Profile Rating",
      "sortable" => true
    ],
    [
      "key" => "mmr",
      "label" => "MMR",
      "sortable" => true
    ],
    [
      "key" => "tier",
      "label" => "Tier"
    ],
    [
      "key" => "games_played",
      "label" => "Games Played",
      "sortable" => true
    ],
    [
      "key" => "most_played_hero",
      "label" => "Most Played Hero"
    ],
    [
      "key" => "most_played_build",
      "label" => "Most Played Build"
    ],
    [
      "key" => "hero_build_games_played",
      "label" => "Games Played With Hero"
    ]
  );

  private function splitColumn($column){
    $keys = Arr::pluck($column, 'key');
    return $keys;
  }

  public function show(){
    return view('Global.leaderboard',
    [
      'tableid' => 'leaderboard-table',
      'title' => 'Global Leaderboard', // Page title
      'paragraph' => 'Heroes Profile Leaderboard - View profiles of each player.', // Summary paragraph
      'tableheading' => 'Leaderboard', // Table heading
      'filtertype' => 'leaderboard',
      'columns' => $this->columns,
      'inputUrl' => "/getGlobalLeaderboardData",
      'columndata' => $this->splitColumn($this->columns),
      'page' => 'leaderboard',


      'primaryfields' => array(
        [
            "key" => "timeframe_type",
            "name" => "Timeframe Type",
            "type" => "radio",
            "description" => "Choose a timeframe",
            "options" =>  array(
                [
                  "key" => "major",
                  "value" => "major"
                ],
                [
                  "key" => "minor",
                  "value" => "minor",
                ]
              ),
        ],
        [
            "key" => "major_patch",
            "name" => "Timeframe",
            "type" => "multiselect",
            "description" => "Major patches",
            "conditional_field" => "timeframe_type",
            "conditional_value" => "major",
            "options" => array(),
        ],
        [
            "key" => "minor_patch",
            "name" => "Timeframe",
            "type" => "multiselect",
            "description" => "Minor patches",
            "conditional_field" => "timeframe_type",
            "conditional_value" => "minor",
            "options" => array()
        ],
        [
            "key" => "game_type",
            "name" => "Game Type",
            "type" => "checkbox",
            "description" => "",
            "options" => array(
                [
                  "key" => "Quick Match",
                  "value" => "Quick Match",
                  "text" => "Quick Match",
                  "icon" => "/images/role-icons.png"
                ],
                [
                  "key" => "Unranked Draft",
                  "value" => "Unranked Draft",
                  "text" => "Unranked Draft",
                  "icon" => "/images/role-icons.png"
                ],
                [
                  "key" => "Storm League",
                  "value" => "Storm League",
                  "text" => "Storm League",
                  "icon" => "/images/role-icons.png"
                ],
                [
                  "key" => "ARAM",
                  "value" => "ARAM",
                  "text" => "ARAM",
                  "icon" => "/images/role-icons.png"
                ]
              ),
        ]
      ),
      'secondaryfields' => array(
        [
            "key" => "game_map",
            "name" => "Map",
            "type" => "multiselect",
            "description" => "",
            "options" => getFilterMaps(),
        ],
        [
            "key" => "player_league_tier",
            "name" => "Player League Tier",
            "type" => "checkbox",
            "description" => "",
            "options" => array(),
        ],
        [
            "key" => "hero_level",
            "name" => "Hero Level",
            "type" => "checkbox",
            "description" => "The player's hero level when playing the game.",
            "options" => array(),
        ],
        [
            "key" => "role",
            "name" => "Role",
            "type" => "checkbox",
            "description" => "Role",
          //  "options" => $global->convertToFilter(Session::get('role_names')),
          "options" => array(
            [ "key"=> "Bruiser", "value"=> "Bruiser", "text"=> "Bruiser", "icon"=> "/images/roles/bruiser.PNG" ],
            [ "key"=> "Healer", "value"=> "Healer", "text"=> "Healer", "icon" => "/images/roles/healer.PNG" ],
            [ "key"=> "Melee Assassin", "value"=> "Melee Assassin", "text"=> "Melee Assassin" , "icon" => "/images/roles/melee assassin.PNG"],
            [ "key"=> "Ranged Assassin", "value"=> "Ranged Assassin", "text"=> "Ranged Assassin", "icon" => "/images/roles/ranged assassin.PNG" ],
            ["key"=> "Support", "value"=> "Support", "text"=> "Support", "icon" => "/images/roles/support.PNG" ],
            [ "key"=> "Tank", "value"=> "Tank", "text"=> "Tank", "icon" => "/images/roles/tank.PNG" ]

          )

        ],
      ),
      'rawfields' => [
        "timeframe_type" => array(
            [
              "key" => "major",
              "value" => "major"
            ],
            [
              "key" => "minor",
              "value" => "minor",
            ]
          ),
        "major_patch" =>  array(),  //conditional on whether timeframe type is equal to major
        "minor_patch" =>  array(),  //conditional on whether timeframe type is equal to minor

        "game_map" => array(),
        "player_league_tier" => array(), //Change to pull once and pass to all 3
        "role_league_tier" => array(), //Change to pull once and pass to all 3
        "hero_league_tier" => array(), //Change to pull once and pass to all 3
        "type" => array(),
        "hero_level" => array(),
        "role" => array([ "key"=> "Bruiser", "value"=> "Bruiser", "text"=> "Bruiser", "icon"=> "/images/roles/bruiser.PNG" ],
        [ "key"=> "Healer", "value"=> "Healer", "text"=> "Healer", "icon" => "/images/roles/healer.PNG" ],
        [ "key"=> "Melee Assassin", "value"=> "Melee Assassin", "text"=> "Melee Assassin" , "icon" => "/images/roles/melee assassin.PNG"],
        [ "key"=> "Ranged Assassin", "value"=> "Ranged Assassin", "text"=> "Ranged Assassin", "icon" => "/images/roles/ranged assassin.PNG" ],
        ["key"=> "Support", "value"=> "Support", "text"=> "Support", "icon" => "/images/roles/support.PNG" ],
        [ "key"=> "Tank", "value"=> "Tank", "text"=> "Tank", "icon" => "/images/roles/tank.PNG" ]),
        "hero" => getHeroesIDMap("name", "short_name") // I need to get the hero's short name in this array, too
        //  "key" = hero_name
        //  "value" = short_name
      ],


    ]);
  }

  public function getData(Request $request){

    /*
    $filters_instance = \Filters::instance();
    $filters = $filters_instance->formatFilterData($request["data"], 0, 0);
    $leaderboard_type = $filters_instance->leaderboard_type;
    $hero = $filters_instance->single_hero;
    $role = $filters_instance->single_role;
    $region = $filters_instance->single_region;
    $season = $filters_instance->season;
    $game_type = $filters_instance->single_game_type;
    $tier = $filters_instance->tier;

    */
    $leaderboard_type = "player";
    $hero = "";
    $role = "";
    $game_type = 5;
    $season = 18;
    $region = "";
    $tier = "";
    $page = 0;

    $leaderboardData = new \LeaderboardData($leaderboard_type, $hero, $role, $game_type, $season, $region, $tier, $page);
    $return_data = $leaderboardData->getLeaderboardData();

    return $return_data;

  }
}
