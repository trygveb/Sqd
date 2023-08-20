<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Connections;

return new class extends Migration {

   use Connections;

   /**
    * Run the migrations.
    */
   public function up(): void {
      $myConnection = Connections::calls_connection();
      Schema::connection($myConnection)->table('definition_fragments', function (Blueprint $table) {
         $table->char('fragment_separator',1)
                 ->default('.')
                 ->nullable()
                 ->after('fragment_type_id');
      });
     
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void {
      $myConnection = Connections::calls_connection();
      Schema::connection($myConnection)->table('definition_fragments', function (Blueprint $table) {
         $table->dropColumn('fragment_separator');
      });
   }
};
