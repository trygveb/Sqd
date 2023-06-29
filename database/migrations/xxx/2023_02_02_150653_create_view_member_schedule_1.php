<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Connections;

class CreateViewMemberSchedule1 extends Migration
{
   use Connections;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        $env = env('APP_ENV', 'test');
//        $laravelDatabase='laravel';
//        if ($env==='test') {
//            $laravelDatabase='laravelTest';
//        }

        DB::connection(Connections::schedule_connection())->statement('CREATE OR REPLACE VIEW v_member_schedule AS
            select
            `u`.`id` AS `user_id`,
            `u`.`complete_name` AS `user_name`,
            `mt`.`name_in_schema` AS `name_in_schema`,
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
        left join `'.Connections::laravel_connection().'`.`users` `u` on
            ((`u`.`id` = `mt`.`user_id`)))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $createViewMemberSchedule= new createViewMemberSchedule();
        $createViewMemberSchedule->up();
    }
}
