<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeagueTier extends Model
{
  protected $table = 'league_tiers';
  public $timestamps = false;
  protected $primaryKey = 'tier_id';
}
