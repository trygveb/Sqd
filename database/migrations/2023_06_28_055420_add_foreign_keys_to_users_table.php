<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Connections;

return new class extends Migration {

   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up() {
      $myConnection = Connections::laravel_connection();
      Schema::connection($myConnection)->table('users', function (Blueprint $table) {
         $table->foreign(['definition_id'], 'users_FK')->references(['id'])->on('calls.definition')->onUpdate('CASCADE')->onDelete('CASCADE');
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down() {
      Schema::table('users', function (Blueprint $table) {
         $table->dropForeign('users_FK');
      });
   }
};
