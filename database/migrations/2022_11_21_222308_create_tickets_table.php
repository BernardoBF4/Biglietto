<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('tickets', function (Blueprint $table) {
      $table->id();
      $table->string('name', 255);
      $table->integer('price');
      $table->boolean('status');
      $table->foreignId('fk_events_id')->constrained('events', 'id');
      $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
      $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('table_tickets');
  }
};
