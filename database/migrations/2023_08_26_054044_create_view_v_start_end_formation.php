<?php

use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Connections;

return new class extends Migration {

   use Connections;

   /**
    * Run the migrations.
    */
   public function up(): void {

      $myConnection = Connections::calls_connection();

      DB::connection($myConnection)->statement('CREATE OR REPLACE VIEW `v_start_end_formation` AS select
            sef.id AS start_end_formation_id,
            sef.start_formation_id,
            sef.end_formation_id,
            f1.name AS start_formation_name,
            f2.name AS end_formation_name
            FROM start_end_formation sef
            LEFT JOIN formation f1 ON f1.id=sef.start_formation_id
            LEFT JOIN formation f2 ON f2.id=sef.end_formation_id;
     ');
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void {
      Schema::dropIfExists('view_v_start_end_formation');
   }
};
