<?php

use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;

class CreateViewMemberScheduleDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
    {
      $env = env('APP_ENV', 'test');
      $laravelDatabase='laravel';
      if ($env==='test') {
         $laravelDatabase='laravelTest';
      }
      DB::connection('schedule')->statement('CREATE OR REPLACE VIEW `v_member_schedule_date` AS select
            msd.user_id AS user_id,
            msd.schedule_date_id AS schedule_date_id,
            msd.status AS status,
            vms.user_name AS user_name,
            vms.schedule_id AS schedule_id,
            vms.group_size AS group_size,
            sd.schedule_date AS schedule_date
        from
           schedule.member_schedule_date msd,schedule.v_member_schedule vms,schedule.schedule_date sd, laravel.users u 
        WHERE vms.user_id = msd.user_id
        AND sd.id = msd.schedule_date_id
        AND sd.schedule_id= vms.schedule_id
        AND u.id = msd.user_id;
     ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection('schedule')->statement('DROP VIEW IF EXISTS `v_member_schedule_date`');
    }
}
