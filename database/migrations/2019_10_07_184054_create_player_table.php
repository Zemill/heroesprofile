<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerTable extends Migration
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
        $this->schema->create('player', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->integer('replayID');
          $table->integer('blizz_id');
          $table->string('battletag', 50);
          $table->tinyInteger('hero');
          $table->smallInteger('hero_level');
          $table->smallInteger('mastery_taunt')->nullable();
          $table->tinyInteger('team');
          $table->tinyInteger('winner');
          $table->string('party', 45);
          $table->double('player_conservative_rating');
          $table->double('player_mean');
          $table->double('player_standard_deviation');
          $table->double('player_change');
          $table->double('hero_conservative_rating');
          $table->double('hero_mean');
          $table->double('hero_standard_deviation');
          $table->double('hero_change');
          $table->double('role_conservative_rating');
          $table->double('role_mean');
          $table->double('role_standard_deviation');
          $table->double('role_change');
          $table->dateTime('mmr_date_parsed');

          $table->primary(['replayID', 'battletag', 'hero'], 'player_Primary_Index');
          $table->index(['replayID', 'blizz_id', 'hero'], 'player_Index_1');
          $table->index(['blizz_id', 'hero'], 'player_Index_2');
          $table->index(['replayID', 'hero', 'player_conservative_rating'], 'player_Index_3');
          $table->index(['hero', 'player_conservative_rating'], 'player_Index_4');
          $table->index(['replayID', 'blizz_id', 'mmr_date_parsed'], 'player_Index_5');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists('player');
    }
}
