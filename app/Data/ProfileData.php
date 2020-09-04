<?php
namespace App\Data;

//use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfileData
{
  private $blizz_id;
  private $region;
  private $game_type;
  private $season;

  public function __construct($blizz_id, $region, $game_type, $season) {
    $this->blizz_id = $blizz_id;
    $this->region = $region;
    $this->game_type = $game_type;
    $this->season = $season;
  }

  public function getPlayerProfileData(){
    $profile_cache = \App\Models\ProfilePageCache::Filters($this->blizz_id, $this->region, $this->game_type, $this->season)->get();
    $max_replayID = \App\Models\Replay::join('player', 'player.replayID', '=', 'replay.replayID')
      ->where('replay.region', $this->region)
      ->where('player.blizz_id', $this->blizz_id)
      ->max('replay.replayID');
    if(count($profile_cache) > 0){
      if($profile_cache[0]["latest_replayID"] < $max_replayID){
        echo "Update Cache";
        return $this->updateCache();
      }else{
        echo "Return Cache";
        return $profile_cache;
      }
    }else{
      echo "Calculate Cache";
      return $this->calculateCache();
    }

    /*
    $max_replayID = \App\Models\Replay::select(
      'replay.replayID'
      )
      ->join('player', 'player.replayID', '=', 'replay.replayID')
      ->join('scores', function($join)
        {
          $join->on('scores.replayID', '=', 'replay.replayID');
          $join->on('scores.battletag', '=', 'player.battletag');
        }
      )
      ->join('talents', function($join)
        {
          $join->on('talents.replayID', '=', 'replay.replayID');
          $join->on('talents.battletag', '=', 'player.battletag');
        }
      )
      ->where('replay.region', $this->region)
      ->where('player.blizz_id', $this->blizz_id)
      ->orderBy('replay.game_date', 'ASC')
      ->get();
      */
    //return $max_replayID;
  }

  private function calculateCache(){
    $roles = getHeroRoles();
    $player_data = $this->getPlayerReplayData($roles);
    $data_formatted = $this->getPlayerOverallStats($player_data[1], $roles);

    $profile_cache = new \App\Models\ProfilePageCache;
    $profile_cache->blizz_id = $this->blizz_id;
    $profile_cache->region = $this->region;
    $profile_cache->game_type = $this->game_type;
    $profile_cache->season = $this->season;
    $profile_cache->wins = $data_formatted['wins'];
    $profile_cache->losses = $data_formatted['losses'];
    $profile_cache->first_to_ten_wins = $data_formatted['first_to_ten_wins'];
    $profile_cache->first_to_ten_losses = $data_formatted['first_to_ten_losses'];
    $profile_cache->second_to_ten_wins = $data_formatted['second_to_ten_wins'];
    $profile_cache->second_to_ten_losses = $data_formatted['second_to_ten_losses'];
    $profile_cache->bruiser_wins = $data_formatted['bruiser_wins'];
    $profile_cache->bruiser_losses = $data_formatted['bruiser_losses'];
    $profile_cache->support_wins = $data_formatted['support_wins'];
    $profile_cache->support_losses = $data_formatted['support_losses'];
    $profile_cache->ranged_assassin_wins = $data_formatted['ranged_assassin_wins'];
    $profile_cache->ranged_assassin_losses = $data_formatted['ranged_assassin_losses'];
    $profile_cache->melee_assassin_wins = $data_formatted['melee_assassin_wins'];
    $profile_cache->melee_assassin_losses = $data_formatted['melee_assassin_losses'];
    $profile_cache->healer_wins = $data_formatted['healer_wins'];
    $profile_cache->healer_losses = $data_formatted['healer_losses'];
    $profile_cache->tank_wins = $data_formatted['tank_wins'];
    $profile_cache->tank_losses = $data_formatted['game_length_total'];
    $profile_cache->total_time_played = $data_formatted['game_length_total'];
    $profile_cache->account_level = $this->getAccountLevel();
    $profile_cache->kills = $data_formatted['kills'];
    $profile_cache->deaths = $data_formatted['deaths'];
    $profile_cache->deaths = $data_formatted['takedowns'];
    $profile_cache->hero_data = serialize($data_formatted['hero_data']);
    $profile_cache->map_data = serialize($data_formatted['map_data']);
    $profile_cache->matches = serialize($data_formatted['matches']);
    $profile_cache->latest_replayID = $player_data[0];
    $profile_cache->save();

    $this->updateExtraPlayerData($profile_cache);
  }

  private function updateCache(){
  }



  private function getPlayerReplayData($roles){
    $talent_data = getTalentMetaData();
    $game_type = $this->game_type;
    $season = $this->season;
    $replay_data = \App\Models\Replay::select(
      "replay.replayID",
      "replay.game_type",
      "replay.game_date",
      "replay.game_length",
      "replay.game_map",
      "replay.region",
      "player.hero",
      "player.team",
      "player.winner",
      "player.player_conservative_rating",
      "player.player_change",
      "player.hero_conservative_rating",
      "player.hero_change",
      "player.role_conservative_rating",
      "player.role_change",
      "player.mmr_date_parsed",
      "scores.kills",
      "scores.takedowns",
      "scores.deaths",
      "scores.first_to_ten",
      "talents.level_one",
      "talents.level_four",
      "talents.level_seven",
      "talents.level_ten",
      "talents.level_thirteen",
      "talents.level_sixteen",
      "talents.level_twenty"
      )
    ->join('player', 'player.replayID', '=', 'replay.replayID')
    ->join('scores', function($join)
      {
        $join->on('scores.replayID', '=', 'replay.replayID');
        $join->on('scores.battletag', '=', 'player.battletag');
      }
    )
    ->join('talents', function($join)
      {
        $join->on('talents.replayID', '=', 'replay.replayID');
        $join->on('talents.battletag', '=', 'player.battletag');
      }
    )
    ->where('replay.region', $this->region)
    ->where('player.blizz_id', $this->blizz_id)
    ->when($game_type != "", function($query) use ($game_type) {
        return $query->where('replay.game_type', $game_type);
      })
    ->when($season != "", function($query) use ($season) {
        return $query->where('replay.game_type', $season);
      })
    ->get();

    $playerStats = array();
    $max_replayID = 0;


    for($i = 0; $i < count($replay_data); $i++){
      $data = array();
      $data["game_type"] = $replay_data[$i]["game_type"];
      $data["game_date"] = $replay_data[$i]["game_date"];
      $data["mmr_date_parsed"] = Carbon::parse($replay_data[$i]["mmr_date_parsed"]);
      $data["game_length"] = $replay_data[$i]["game_length"];
      $data["game_map"] = $replay_data[$i]["game_map"];
      $data["region"] = $replay_data[$i]["region"];
      $data["replayID"] = $replay_data[$i]["replayID"];
      $data["hero"] = $replay_data[$i]["hero"];
      $data["role"] = $roles[$data["hero"]];
      $data["winner"] = $replay_data[$i]["winner"];

      $data["player_conservative_rating"] = $replay_data[$i]["player_conservative_rating"];
      $data["mmr"] = round(1800 + 40 * $replay_data[$i]["player_conservative_rating"]);
      $data["player_change"] = $replay_data[$i]["player_change"];

      $data["hero_conservative_rating"] = $replay_data[$i]["hero_conservative_rating"];
      $data["hero_mmr"] = round(1800 + 40 * $replay_data[$i]["hero_conservative_rating"]);
      $data["hero_change"] = $replay_data[$i]["hero_change"];

      $data["role_conservative_rating"] = $replay_data[$i]["role_conservative_rating"];
      $data["role_mmr"] = round(1800 + 40 * $replay_data[$i]["role_conservative_rating"]);
      $data["role_change"] = $replay_data[$i]["role_change"];

      $data["kills"] = $replay_data[$i]["kills"];
      $data["takedowns"] = $replay_data[$i]["takedowns"];
      if($replay_data[$i]["first_to_ten"] == ""){
        $data["first_to_ten"] = -1;
      }else{
        $data["first_to_ten"] = $replay_data[$i]["first_to_ten"];
      }

      $data["deaths"] = $replay_data[$i]["deaths"];
      $data["level_one"] = $replay_data[$i]["level_one"];
      $data["level_one_title"] = "";
      $data["level_one_description"] = "";
      $data["level_one_hotkey"] = "";
      $data["level_one_icon"] = "talent_removed";


      $data["level_four"] = $replay_data[$i]["level_four"];
      $data["level_four_title"] = "";
      $data["level_four_description"] = "";
      $data["level_four_hotkey"] = "";
      $data["level_four_icon"] = "talent_removed";

      $data["level_seven"] = $replay_data[$i]["level_seven"];
      $data["level_seven_title"] = "";
      $data["level_seven_description"] = "";
      $data["level_seven_hotkey"] = "";
      $data["level_seven_icon"] = "talent_removed";

      $data["level_ten"] = $replay_data[$i]["level_ten"];
      $data["level_ten_title"] = "";
      $data["level_ten_description"] = "";
      $data["level_ten_hotkey"] = "";
      $data["level_ten_icon"] = "talent_removed";

      $data["level_thirteen"] = $replay_data[$i]["level_thirteen"];
      $data["level_thirteen_title"] = "";
      $data["level_thirteen_description"] = "";
      $data["level_thirteen_hotkey"] = "";
      $data["level_thirteen_icon"] = "talent_removed";

      $data["level_sixteen"] = $replay_data[$i]["level_sixteen"];
      $data["level_sixteen_title"] = "";
      $data["level_sixteen_description"] = "";
      $data["level_sixteen_hotkey"] = "";
      $data["level_sixteen_icon"] = "talent_removed";

      $data["level_twenty"] = $replay_data[$i]["level_twenty"];
      $data["level_twenty_title"] = "";
      $data["level_twenty_description"] = "";
      $data["level_twenty_hotkey"] = "";
      $data["level_twenty_icon"] = "talent_removed";


      if($data["level_one"] == ""){
        $data["level_one"] = 0;
        $data["level_one_icon"] = "";
      }

      if(array_key_exists($data["level_one"], $talent_data)){
        $data["level_one_title"] = $talent_data[$data["level_one"]]["title"];
        $data["level_one_description"] = $talent_data[$data["level_one"]]["description"];
        $data["level_one_hotkey"] = $talent_data[$data["level_one"]]["hotkey"];
        $data["level_one_icon"] = $talent_data[$data["level_one"]]["icon"];
      }


      if($data["level_four"] == ""){
        $data["level_four"] = 0;
        $data["level_four_icon"] = "";
      }

      if(array_key_exists($data["level_four"], $talent_data)){
        $data["level_four_title"] = $talent_data[$data["level_four"]]["title"];
        $data["level_four_description"] = $talent_data[$data["level_four"]]["description"];
        $data["level_four_hotkey"] = $talent_data[$data["level_four"]]["hotkey"];
        $data["level_four_icon"] = $talent_data[$data["level_four"]]["icon"];
      }

      if($data["level_seven"] == ""){
        $data["level_seven"] = 0;
        $data["level_seven_icon"] = "";
      }

      if(array_key_exists($data["level_seven"], $talent_data)){
        $data["level_seven_title"] = $talent_data[$data["level_seven"]]["title"];
        $data["level_seven_description"] = $talent_data[$data["level_seven"]]["description"];
        $data["level_seven_hotkey"] = $talent_data[$data["level_seven"]]["hotkey"];
        $data["level_seven_icon"] = $talent_data[$data["level_seven"]]["icon"];
      }

      if($data["level_ten"] == ""){
        $data["level_ten"] = 0;
        $data["level_ten_icon"] = "";
      }

      if(array_key_exists($data["level_ten"], $talent_data)){
        $data["level_ten_title"] = $talent_data[$data["level_ten"]]["title"];
        $data["level_ten_description"] = $talent_data[$data["level_ten"]]["description"];
        $data["level_ten_hotkey"] = $talent_data[$data["level_ten"]]["hotkey"];
        $data["level_ten_icon"] = $talent_data[$data["level_ten"]]["icon"];
      }


      if($data["level_thirteen"] == ""){
        $data["level_thirteen"] = 0;
        $data["level_thirteen_icon"] = "";
      }

      if(array_key_exists($data["level_thirteen"], $talent_data)){
        $data["level_thirteen_title"] = $talent_data[$data["level_thirteen"]]["title"];
        $data["level_thirteen_description"] = $talent_data[$data["level_thirteen"]]["description"];
        $data["level_thirteen_hotkey"] = $talent_data[$data["level_thirteen"]]["hotkey"];
        $data["level_thirteen_icon"] = $talent_data[$data["level_thirteen"]]["icon"];
      }


      if($data["level_sixteen"] == ""){
        $data["level_sixteen"] = 0;
        $data["level_sixteen_icon"] = "";
      }

      if(array_key_exists($data["level_sixteen"], $talent_data)){
        $data["level_sixteen_title"] = $talent_data[$data["level_sixteen"]]["title"];
        $data["level_sixteen_description"] = $talent_data[$data["level_sixteen"]]["description"];
        $data["level_sixteen_hotkey"] = $talent_data[$data["level_sixteen"]]["hotkey"];
        $data["level_sixteen_icon"] = $talent_data[$data["level_sixteen"]]["icon"];
      }


      if($data["level_twenty"] == ""){
        $data["level_twenty"] = 0;
        $data["level_twenty_icon"] = "";
      }

      if(array_key_exists($data["level_twenty"], $talent_data)){
        $data["level_twenty_title"] = $talent_data[$data["level_twenty"]]["title"];
        $data["level_twenty_description"] = $talent_data[$data["level_twenty"]]["description"];
        $data["level_twenty_hotkey"] = $talent_data[$data["level_twenty"]]["hotkey"];
        $data["level_twenty_icon"] = $talent_data[$data["level_twenty"]]["icon"];
      }

      if($max_replayID < $replay_data[$i]["replayID"]){
        $max_replayID = $replay_data[$i]["replayID"];
      }
      $playerStats[$replay_data[$i]["replayID"]] = $data;

    }
    return array($max_replayID, $playerStats);
  }

  private function getPlayerOverallStats($player_data, $roles){
    $overall_stats = array();
    $overall_stats["games_played"] = 0;
    $overall_stats["kills"] = 0;
    $overall_stats["assists"] = 0;
    $overall_stats["deaths"] = 0;
    $overall_stats["takedowns"] = 0;
    $overall_stats["wins"] = 0;
    $overall_stats["losses"] = 0;
    $overall_stats["win_rate"] = 0;

    $overall_stats["first_to_ten_wins"] = 0;
    $overall_stats["second_to_ten_wins"] = 0;

    $overall_stats["first_to_ten_losses"] = 0;
    $overall_stats["second_to_ten_losses"] = 0;

    $overall_stats["game_length_total"] = 0;

    $overall_stats['bruiser_wins'] = 0;
    $overall_stats['bruiser_losses'] = 0;
    $overall_stats['support_wins'] = 0;
    $overall_stats['support_losses'] = 0;
    $overall_stats['ranged_assassin_wins'] = 0;
    $overall_stats['ranged_assassin_losses'] = 0;
    $overall_stats['melee_assassin_wins'] = 0;
    $overall_stats['melee_assassin_losses'] = 0;
    $overall_stats['healer_wins'] = 0;
    $overall_stats['healer_losses'] = 0;
    $overall_stats['tank_wins'] = 0;
    $overall_stats['tank_losses'] = 0;

    $overall_stats["hero_data"] = array();
    $overall_stats["map_data"] = array();
    $overall_stats["matches"] = array();
    $matches_counter = 0;
    foreach ($player_data as $replayID => $data){
      if(!array_key_exists($data["hero"], $overall_stats["hero_data"])){
        $overall_stats["hero_data"][$data["hero"]]["wins"] = 0;
        $overall_stats["hero_data"][$data["hero"]]["losses"] = 0;
        $overall_stats["hero_data"][$data["hero"]]["game_date"] = '2000-11-22 13:48:59';
      }




      if(Carbon::parse($data["game_date"]) > Carbon::parse($overall_stats["hero_data"][$data["hero"]]["game_date"])){
        $overall_stats["hero_data"][$data["hero"]]["game_date"] = $data["game_date"];
      }

      if(!array_key_exists($data["game_map"], $overall_stats["map_data"])){
        $overall_stats["map_data"][$data["game_map"]]["wins"] = 0;
        $overall_stats["map_data"][$data["game_map"]]["losses"] = 0;
        $overall_stats["map_data"][$data["game_map"]]["game_date"] = '2000-11-22 13:48:59';
      }



      if(Carbon::parse($data["game_date"]) > Carbon::parse($overall_stats["map_data"][$data["game_map"]]["game_date"])){
        $overall_stats["map_data"][$data["game_map"]]["game_date"] = $data["game_date"];
      }

      $overall_stats["games_played"] += 1;
      $overall_stats["kills"] += $data["kills"];
      $overall_stats["deaths"] += $data["deaths"];
      $overall_stats["takedowns"] += $data["takedowns"];
      $overall_stats["game_length_total"] += $data["game_length"];

      if($data["winner"] == 1){
        $overall_stats["wins"] += 1;
        $overall_stats["hero_data"][$data["hero"]]["wins"]++;
        $overall_stats["map_data"][$data["game_map"]]["wins"]++;

        $overall_stats[str_replace(' ', '_', strtolower($data["role"])) . "_wins"] += 1;

        if($data["first_to_ten"] == 1){
          $overall_stats["first_to_ten_wins"] += 1;
        }else if($data["first_to_ten"] == 0){
          $overall_stats["second_to_ten_wins"] += 1;
        }

      }else{
        $overall_stats["losses"] += 1;
        $overall_stats["hero_data"][$data["hero"]]["losses"]++;
        $overall_stats["map_data"][$data["game_map"]]["losses"]++;

        $overall_stats[str_replace(' ', '_', strtolower($roles[$data["hero"]])) . "_losses"] += 1;

        if($data["first_to_ten"] == 1){
          $overall_stats["first_to_ten_losses"] += 1;
        }else if($data["first_to_ten"] == 0){
          $overall_stats["second_to_ten_losses"] += 1;
        }

      }
    }

    $overall_stats["matches"] = $player_data;
    uasort($overall_stats["matches"], [$this, 'cmp_latest_played']);
    $overall_stats["matches"] = array_slice($overall_stats["matches"], 0, 5, true);
    return $overall_stats;
  }

  private function getAccountLevel(){
    $account_level = \App\Models\Battletag::where('blizz_id', $this->blizz_id)
      ->where('region', $this->region)
      ->max('account_level');
    return $account_level;
  }

  private function updateExtraPlayerData($player_data){
    print_r($player_data);
    /*
    if($player_data["deaths"] > 0){
      $player_data["kdr"] = $player_data["kills"] / $player_data["deaths"];
      $player_data["kda"] = $player_data["takedowns"] / $player_data["deaths"];
    }else{
      $player_data["kdr"] = $player_data["kills"];
      $player_data["kda"] = $player_data["takedowns"];
    }

    if(($player_data["wins"] + $player_data["losses"]) > 0){
      $player_data["win_rate"] = ($player_data["wins"] / ($player_data["wins"] + $player_data["losses"])) * 100;
    }else{
      $player_data["win_rate"] = 0;
    }


    if(($player_data["first_to_ten_wins"] + $player_data["first_to_ten_losses"]) > 0){
      $player_data["first_to_ten_win_rate"] = ($player_data["first_to_ten_wins"] / ($player_data["first_to_ten_wins"] + $player_data["first_to_ten_losses"])) * 100;
    }else{
      $player_data["first_to_ten_win_rate"] = 0;
    }

    if(($player_data["second_to_ten_wins"] + $player_data["second_to_ten_losses"]) > 0){
      $player_data["second_to_ten_win_rate"] = ($player_data["second_to_ten_wins"] / ($player_data["second_to_ten_wins"] + $player_data["second_to_ten_losses"])) * 100;
    }else{
      $player_data["second_to_ten_win_rate"] = 0;
    }

    $roles = array();
    $roles["Bruiser"] = "bruiser";
    $roles["Support"] = "support";
    $roles["Ranged Assassin"] = "ranged_assassin";
    $roles["Melee Assassin"] = "melee_assassin";
    $roles["Healer"] = "healer";
    $roles["Tank"] = "tank";

    foreach ($roles as $role => $role_data){
      if($player_data[$role_data . "_wins"] + $player_data[$role_data . "_losses"] > 0){
        $player_data[$role_data . "_win_rate"] = $player_data[$role_data . "_wins"] / ($player_data[$role_data . "_wins"] + $player_data[$role_data . "_losses"]) * 100;
      }else{
        $player_data[$role_data . "_win_rate"] = 0;
      }
    }
    $player_data["game_length_total"] = $this->secondsToTime($player_data["total_time_played"]);
    $player_data["matches"] = unserialize($player_data["matches"]);

    $player_data["matches"] = $this->checkFor1800MMR($player_data["matches"]);
    //Heroes Most Games Played
    $player_data["three_highest_games_played_heroes"] = unserialize($player_data["hero_data"]);
    foreach ($player_data["three_highest_games_played_heroes"] as $key => $value){
      $player_data["three_highest_games_played_heroes"][$key]["games_played"] = $value["wins"] + $value["losses"];
    }

    uasort($player_data["three_highest_games_played_heroes"], [$this, 'cmp_games_played']);
    $player_data["three_highest_games_played_heroes"] = array_slice($player_data["three_highest_games_played_heroes"], 0, 3, true);


    //Maps Most Games Played
    $player_data["three_highest_games_played_maps"] = unserialize($player_data["map_data"]);
    foreach ($player_data["three_highest_games_played_maps"] as $key => $value){
      $player_data["three_highest_games_played_maps"][$key]["games_played"] = $value["wins"] + $value["losses"];
    }
    uasort($player_data["three_highest_games_played_maps"], [$this, 'cmp_games_played']);
    $player_data["three_highest_games_played_maps"] = array_slice($player_data["three_highest_games_played_maps"], 0, 3, true);

    $player_data["three_highest_win_rate_heroes"] = unserialize($player_data["hero_data"]);
    foreach ($player_data["three_highest_win_rate_heroes"] as $key => $value){
      if($value["wins"] + $value["losses"] > 20){
        $player_data["three_highest_win_rate_heroes"][$key]["win_rate"] = $value["wins"] / ($value["wins"] + $value["losses"]) * 100;
      }else{
        $player_data["three_highest_win_rate_heroes"][$key]["win_rate"] = 0;
      }
    }

    if(count($player_data["three_highest_win_rate_heroes"]) < 3){
      foreach ($player_data["three_highest_win_rate_heroes"] as $key => $value){
        if($value["wins"] + $value["losses"] > 15){
          $player_data["three_highest_win_rate_heroes"][$key]["win_rate"] = $value["wins"] / ($value["wins"] + $value["losses"]) * 100;
        }else{
          $player_data["three_highest_win_rate_heroes"][$key]["win_rate"] = 0;
        }
      }
    }

    if(count($player_data["three_highest_win_rate_heroes"]) < 3){
      foreach ($player_data["three_highest_win_rate_heroes"] as $key => $value){
        if($value["wins"] + $value["losses"] > 10){
          $player_data["three_highest_win_rate_heroes"][$key]["win_rate"] = $value["wins"] / ($value["wins"] + $value["losses"]) * 100;
        }else{
          $player_data["three_highest_win_rate_heroes"][$key]["win_rate"] = 0;
        }
      }
    }

    if(count($player_data["three_highest_win_rate_heroes"]) < 3){
      foreach ($player_data["three_highest_win_rate_heroes"] as $key => $value){
        if($value["wins"] + $value["losses"] > 5){
          $player_data["three_highest_win_rate_heroes"][$key]["win_rate"] = $value["wins"] / ($value["wins"] + $value["losses"]) * 100;
        }else{
          $player_data["three_highest_win_rate_heroes"][$key]["win_rate"] = 0;
        }
      }
    }

    if(count($player_data["three_highest_win_rate_heroes"]) < 3){
      foreach ($player_data["three_highest_win_rate_heroes"] as $key => $value){
        if($value["wins"] + $value["losses"] > 1){
          $player_data["three_highest_win_rate_heroes"][$key]["win_rate"] = $value["wins"] / ($value["wins"] + $value["losses"]) * 100;
        }else{
          $player_data["three_highest_win_rate_heroes"][$key]["win_rate"] = 0;
        }
      }
    }
    uasort($player_data["three_highest_win_rate_heroes"], [$this, 'cmp_win_rate']);
    $player_data["three_highest_win_rate_heroes"] = array_slice($player_data["three_highest_win_rate_heroes"], 0, 3, true);

    $player_data["three_highest_win_rate_maps"] = unserialize($player_data["map_data"]);
    foreach ($player_data["three_highest_win_rate_maps"] as $key => $value){
      if($value["wins"] + $value["losses"] > 20){
        $player_data["three_highest_win_rate_maps"][$key]["win_rate"] = $value["wins"] / ($value["wins"] + $value["losses"]) * 100;
      }else{
        $player_data["three_highest_win_rate_maps"][$key]["win_rate"] = 0;
      }
    }

    if(count($player_data["three_highest_win_rate_maps"]) < 3){
      foreach ($player_data["three_highest_win_rate_maps"] as $key => $value){
        if($value["wins"] + $value["losses"] > 15){
          $player_data["three_highest_win_rate_maps"][$key]["win_rate"] = $value["wins"] / ($value["wins"] + $value["losses"]) * 100;
        }else{
          $player_data["three_highest_win_rate_maps"][$key]["win_rate"] = 0;
        }
      }
    }

    if(count($player_data["three_highest_win_rate_maps"]) < 3){
      foreach ($player_data["three_highest_win_rate_maps"] as $key => $value){
        if($value["wins"] + $value["losses"] > 10){
          $player_data["three_highest_win_rate_maps"][$key]["win_rate"] = $value["wins"] / ($value["wins"] + $value["losses"]) * 100;
        }else{
          $player_data["three_highest_win_rate_maps"][$key]["win_rate"] = 0;
        }
      }
    }

    if(count($player_data["three_highest_win_rate_maps"]) < 3){
      foreach ($player_data["three_highest_win_rate_maps"] as $key => $value){
        if($value["wins"] + $value["losses"] > 5){
          $player_data["three_highest_win_rate_maps"][$key]["win_rate"] = $value["wins"] / ($value["wins"] + $value["losses"]) * 100;
        }else{
          $player_data["three_highest_win_rate_maps"][$key]["win_rate"] = 0;
        }
      }
    }

    if(count($player_data["three_highest_win_rate_maps"]) < 3){
      foreach ($player_data["three_highest_win_rate_maps"] as $key => $value){
        if($value["wins"] + $value["losses"] > 1){
          $player_data["three_highest_win_rate_maps"][$key]["win_rate"] = $value["wins"] / ($value["wins"] + $value["losses"]) * 100;
        }else{
          $player_data["three_highest_win_rate_maps"][$key]["win_rate"] = 0;
        }
      }
    }
    uasort($player_data["three_highest_win_rate_maps"], [$this, 'cmp_win_rate']);
    $player_data["three_highest_win_rate_maps"] = array_slice($player_data["three_highest_win_rate_maps"], 0, 3, true);

    $player_data["three_latest_heroes"] = unserialize($player_data["hero_data"]);
    uasort($player_data["three_latest_heroes"], [$this, 'cmp_latest_played']);
    $player_data["three_latest_heroes"] = array_slice($player_data["three_latest_heroes"], 0, 3, true);


    $player_data["three_latest_played_maps"] = unserialize($player_data["map_data"]);
    uasort($player_data["three_latest_played_maps"], [$this, 'cmp_latest_played']);
    $player_data["three_latest_played_maps"] = array_slice($player_data["three_latest_played_maps"], 0, 3, true);

    $league_type_data = $this->getLeagueData();
    $player_data["league_data"] = array();

    for($i = 0; $i < count($league_type_data); $i++){
      $player_data["league_data"][$league_type_data[$i]["game_type"]]["wins"] = $league_type_data[$i]["win"];
      $player_data["league_data"][$league_type_data[$i]["game_type"]]["losses"] = $league_type_data[$i]["loss"];
      $player_data["league_data"][$league_type_data[$i]["game_type"]]["mmr"] = round(1800 + 40 * $league_type_data[$i]["conservative_rating"]);
      $player_data["league_data"][$league_type_data[$i]["game_type"]]["rank"] = $this->getRankName($player_data["league_data"][$league_type_data[$i]["game_type"]]["mmr"], $league_type_data[$i]["game_type"]);

      if($league_type_data[$i]["win"] + $league_type_data[$i]["loss"] > 0){
        $player_data["league_data"][$league_type_data[$i]["game_type"]]["win_rate"] = $league_type_data[$i]["win"] / ($league_type_data[$i]["win"] + $league_type_data[$i]["loss"]) * 100;
      }else{
        $player_data["league_data"][$league_type_data[$i]["game_type"]]["win_rate"] = 0;
      }
    }

    return $player_data;
    */
  }

  private function cmp_latest_played( $a, $b ) {
    $ad = Carbon::parse($a['game_date']);
    $bd = Carbon::parse($b['game_date']);

    if($ad ==  $bd){
      return 0 ;
    }
    return ($ad > $bd) ? -1 : 1;
  }

  private function secondsToTime($inputSeconds) {
    $secondsInAMinute = 60;
    $secondsInAnHour = 60 * $secondsInAMinute;
    $secondsInADay = 24 * $secondsInAnHour;

    // Extract days
    $days = floor($inputSeconds / $secondsInADay);

    // Extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // Extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);


    // Extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // Format and return
    $timeParts = [];
    $sections = [
        'day' => (int)$days,
        'hour' => (int)$hours,
        'minute' => (int)$minutes,
        'second' => (int)$seconds,
    ];

    foreach ($sections as $name => $value){
        if ($value > 0){
            $timeParts[] = $value. ' '.$name.($value == 1 ? '' : 's');
        }
    }
    return implode(', ', $timeParts);
  }

  private function checkFor1800MMR($player_data){
    foreach ($player_data as $replayID => $replay_data){
      if(is_null($replay_data["player_conservative_rating"])){
        $player_replay_data = \App\Models\Player::select('player_conservative_rating', 'player_change', 'hero_conservative_rating', 'hero_change', 'role_conservative_rating', 'role_change')
        ->where('replayID', $replayID)
        ->where('blizz_id', $this->blizz_id)
        ->get();

        $player_data[$replayID]["player_conservative_rating"] = $player_replay_data[0]["player_conservative_rating"];
        $player_data[$replayID]["mmr"] = round(1800 + 40 * $player_replay_data[0]["player_conservative_rating"]);
        $player_data[$replayID]["player_change"] = $player_replay_data[0]["player_change"];
        $player_data[$replayID]["hero_conservative_rating"] = $player_replay_data[0]["hero_conservative_rating"];
        $player_data[$replayID]["hero_mmr"] = round(1800 + 40 * $player_replay_data[0]["hero_conservative_rating"]);
        $player_data[$replayID]["hero_change"] = $player_replay_data[0]["hero_change"];
        $player_data[$replayID]["role_conservative_rating"] = $player_replay_data[0]["role_conservative_rating"];
        $player_data[$replayID]["role_mmr"] = round(1800 + 40 * $player_replay_data[0]["hero_conservative_rating"]);
        $player_data[$replayID]["role_change"] = $player_replay_data[0]["role_change"];
      }
    }
    return $player_data;
  }
}
