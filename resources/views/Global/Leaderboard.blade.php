<?php
namespace App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//var_dump(openssl_get_cert_locations());
//die;
//$data = \PlayerData::instance()->getPlayerData();
//print_r(json_encode($data,true));


//Auth::loginUsingId(1, true);

/*
if (Auth::check()) {
    $user = Auth::user();
    print_r($user);
}
*/
/*
$data = \PlayerData::instance()->getPlayerData();
$data = json_encode($data,true);
print_r(json_encode($data));
*/
//$league_tiers = \GlobalFunctions::instance()->getLeagueTiers();
//print_r(json_encode($league_tiers, true));

  $player_instance = \ProfileData::instance();
  $data = $player_instance->getPlayerData();
  print_r(json_encode($data, true));
  //$latest_played = $player_instance->getLatestPlayed(5);
  //print_r(json_encode(array_slice($data, 0, 5), true));


 ?>
