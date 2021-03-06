<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Awards extends Migration
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
    $this->schema->create('awards', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->integer('award_table_id')->autoIncrement();
      $table->integer('award_id');
      $table->string('title', 45);
      $table->string('icon', 45);

      $table->unique(['award_id', 'title', 'icon'], 'award_base_unique');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    $this->schema->dropIfExists('awards');
  }
}
