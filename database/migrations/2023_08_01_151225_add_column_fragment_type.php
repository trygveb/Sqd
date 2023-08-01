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
         $table->unsignedBigInteger('fragment_type_id')->nullable()
                 ->after('fragment_id');
         $table->foreign(['fragment_type_id'], 'fragment_type__FK')
                 ->references(['id'])
                 ->on('calls.fragment_type')
                 ->onUpdate('CASCADE')
                 ->onDelete('CASCADE');
      });
      DB::connection($myConnection)->statement(
              "UPDATE definition_fragments a SET fragment_type_id=(SELECT type_id FROM fragment b WHERE b.id=a.fragment_id LIMIT 1)");
      
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void {
      $myConnection = Connections::calls_connection();
      DB::connection($myConnection)->statement('ALTER TABLE calls.definition_fragments DROP FOREIGN KEY fragment_type__FK');

      Schema::connection($myConnection)->table('definition_fragments', function (Blueprint $table) {
         $table->dropColumn('fragment_type_id');
      });
   }
};
