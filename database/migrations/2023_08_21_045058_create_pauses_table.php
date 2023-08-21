<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Connections;

return new class extends Migration {

   /**
    * Run the migrations.
    */
   public function up(): void {
      $myConnection = Connections::calls_connection();
      Schema::connection($myConnection)->create('pauses', function (Blueprint $table) {
         $table->id();
         $table->string('name', 12);
         $table->unsignedSmallInteger('time');
         $table->char('symbol', 1);
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void {
      $myConnection = Connections::calls_connection();
      Schema::connection($myConnection)->dropIfExists('pauses');
   }
};
