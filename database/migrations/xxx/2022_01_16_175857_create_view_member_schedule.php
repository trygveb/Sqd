<?php

use Illuminate\Database\Migrations\Migration;

class CreateViewMemberSchedule extends Migration
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
      DB::connection('schedule')->statement('CREATE OR REPLACE VIEW `v_member_schedule` AS
         select
             `u`.`id` AS `user_id`,
             `u`.`name` AS `user_name`,
             `u`.`email` AS `email`,
             `t`.`id` AS `schedule_id`,
             `t`.`name` AS `schedule_name`,
             `t`.`description` AS `schedule_description`,
             `t`.`password` AS `password`,
             `mt`.`group_size` AS `group_size`,
             `mt`.`admin` AS `admin`
         from
             ((`member_schedule` `mt`
         left join `schedule` `t` on
             ((`t`.`id` = `mt`.`schedule_id`)))
         left join `'.$laravelDatabase.'`.`users` `u` on
             ((`u`.`id` = `mt`.`user_id`)))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection('schedule')->statement('DROP VIEW IF EXISTS `v_member_schedule`');
    }
}
