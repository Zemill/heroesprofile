<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//Multiple mode specific tables exist to make MMR Re-calcs per mode easier//
class MasterMMRDataTL extends Model
{
  protected $table = 'master_mmr_data_tl';
  protected $primaryKey = 'master_mmr_data_tl_id';
  public $timestamps = false;

  public function scopeFilters($query, $type_value, $game_type, $blizz_id, $region){
    $query->where('type_value', $type_value);
    $query->where('game_type', $game_type);
    $query->where('blizz_id', $blizz_id);
    $query->where('region', $region);
    return $query;
  }
}
