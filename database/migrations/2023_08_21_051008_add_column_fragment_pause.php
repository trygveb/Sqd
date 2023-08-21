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
         $table->unsignedBigInteger('pause_id')
                 ->default(1)
                 ->after('fragment_type_id');
      });
      Schema::connection($myConnection)->table('definition_fragments', function (Blueprint $table) {
         $table->foreign(['pause_id'], 'pauses_FK')
                 ->references(['id'])
                 ->on('calls.pauses')
                 ->onUpdate('CASCADE')
                 ->onDelete('CASCADE');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void {
      $myConnection = Connections::calls_connection();
      Schema::connection($myConnection)->table('definition_fragments', function (Blueprint $table) {
         $table->dropForeign('pauses_FK');
         $table->dropColumn('pause_id');
      });
   }
};
