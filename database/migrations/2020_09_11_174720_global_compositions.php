<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GlobalCompositions extends Migration
{

  /**
  * The database schema.
  *
  * @var Schema
  */
  protected $schema;

  /**
  * Create a new migration instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->schema = Schema::connection(config('database.default'));
  }

  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    $this->schema->create('global_compositions', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->integer('global_compositions_id')->autoIncrement();
      $table->string('game_version', 45);
      $table->tinyInteger('game_type');
      $table->tinyInteger('league_tier');
      $table->tinyInteger('hero_league_tier');
      $table->tinyInteger('role_league_tier');
      $table->tinyInteger('game_map');
      $table->integer('hero_level')->unsigned();
      $table->integer('composition_id');
      $table->tinyInteger('hero');
      $table->tinyInteger('mirror');
      $table->integer('region');
      $table->tinyInteger('win_loss');
      $table->integer('games_played');

      $table->unique(['game_version', 'game_type', 'league_tier', 'hero_league_tier', 'role_league_tier', 'game_map', 'hero_level', 'composition_id', 'hero', 'mirror', 'region', 'win_loss'], 'global_compositions_unique');
      $table->index(['game_version', 'game_type', 'league_tier', 'hero_league_tier', 'role_league_tier', 'game_map', 'hero_level', 'composition_id', 'hero', 'mirror', 'region', 'win_loss', 'games_played'], 'global_compositions_index');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    $this->schema->dropIfExists('global_compositions');
  }
}
