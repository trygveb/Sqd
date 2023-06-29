<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameMemberColumnOnMemberScheduleTable extends Migration {

   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up() {
      DB::statement('ALTER TABLE schedule.member_schedule CHANGE member_id user_id bigint unsigned NOT NULL');
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down() {
      DB::statement('ALTER TABLE schedule.member_schedule CHANGE user_id member_id bigint unsigned NOT NULL');
   }

}
