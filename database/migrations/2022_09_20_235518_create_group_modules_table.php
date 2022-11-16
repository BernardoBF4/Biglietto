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
    Schema::create('group_modules', function (Blueprint $table) {
      $table->id();
      $table->foreignId('fk_group_id')->constrained('groups', 'id')->cascadeOnDelete();
      $table->foreignId('fk_module_id')->constrained('modules', 'id');
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
    Schema::dropIfExists('group_modules');
  }
};
