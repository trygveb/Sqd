<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateScheduleDateTable extends Migration {

   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up() {
      Schema::connection('schedule')->create('schedule_date', function (Blueprint $table) {
         $table->id();
         $table->unsignedBigInteger('schedule_id');
         $table->date('schedule_date');
         $table->time('start_time')->default('19:00:00');
         $table->string('comment', 250)->nullable();
         $table->timestamps();
         $table->foreign('schedule_id', 'schedule_date_FK')->references('id')->on('schedule')->onDelete('cascade')->onUpdate('cascade');
      });
      DB::connection('schedule')->statement("ALTER TABLE `schedule_date` comment='This table contains all dates for all schedules, with start-time and comment'");
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down() {
      Schema::connection('schedule')->dropIfExists('schedule_date');
   }

}
