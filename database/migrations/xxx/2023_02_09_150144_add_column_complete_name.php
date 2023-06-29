<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Connections;

class AddColumnCompleteName extends Migration {

   use Connections;

   public function up() {
      DB::connection(Connections::laravel_connection())->statement("ALTER TABLE users ADD complete_name varchar(72)  AS"
              . " (CONCAT(first_name,' ', COALESCE(middle_name,''), ' ', family_name)) AFTER name");
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down() {
      Schema::connection(Connections::laravel_connection())->table('users', function (Blueprint $table) {
         $table->dropColumn('complete_name');
      });
   }

}
