<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMemberScheduleDateTable extends Migration {

   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up() {
      Schema::connection('schedule')->create('member_schedule_date', function (Blueprint $table) {
         $env = env('APP_ENV', 'test');
         $table->id();
         $table->unsignedBigInteger('user_id');
         $table->unsignedBigInteger('schedule_date_id');
         $table->unsignedTinyInteger('status')->default(0);
         $table->timestamps();
         $table->unique(['user_id', 'schedule_date_id'],'member_schedule_date_UN_1');
         if ($env === 'test') {
            $table->foreign('user_id', 'member_schedule_date_FK_1')->references('id')->on('laravelTest.users')->onDelete('cascade')->onUpdate('cascade');
         } else {
            $table->foreign('user_id', 'member_schedule_date_FK_1')->references('id')->on('laravel.users')->onDelete('cascade')->onUpdate('cascade');
         }
         $table->foreign('schedule_date_id', 'member_schedule_date_FK_2')->references('id')->on('schedule_date')->onDelete('cascade')->onUpdate('cascade');
      });
      DB::connection('schedule')->statement("ALTER TABLE `member_schedule_date` comment='This table contains the status for each member on each schedule date'");
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down() {
      Schema::connection('schedule')->dropIfExists('member_schedule_date');
   }

}
