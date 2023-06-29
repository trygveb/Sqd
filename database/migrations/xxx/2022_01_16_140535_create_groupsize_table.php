<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsizeTable extends Migration {

   private $FK_NAME = 'groupsize_FK_1';

   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up() {
       Schema::connection('schedule')->create('groupsize', function (Blueprint $table) {
         $env = env('APP_ENV', 'test');
         $table->id();
         $table->unsignedBigInteger('user_id');
         $table->unsignedBigInteger('schedule_id');
         $table->unsignedTinyInteger('size')->default(1);
         $table->timestamps();
         if ($env === 'test') {
            $table->foreign('user_id', 'groupsize_FK_1')->references('id')->on('laravelTest.users')->onUpdate('CASCADE')->onDelete('CASCADE');
         } else {
            $table->foreign('user_id', 'groupsize_FK_1')->references('id')->on('laravel.users')->onUpdate('CASCADE')->onDelete('CASCADE');
         }
         $table->foreign('schedule_id', 'groupsize_FK_2')->references('id')->on('schedule')->onUpdate('CASCADE')->onDelete('CASCADE');
         $table->index(['user_id', 'schedule_id']);
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down() {
      Schema::dropIfExists('groupsize');
   }

}
