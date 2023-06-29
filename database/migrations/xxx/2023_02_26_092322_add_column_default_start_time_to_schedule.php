<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Connections;

class AddColumnDefaultStartTimeToSchedule extends Migration
{
      use Connections;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $myConnection=Connections::schedule_connection();
        Schema::connection($myConnection)->table('schedule', function (Blueprint $table) {
            $table->time('default_start_time', $precision = 0)->default('19:00')->after('default_weekday');//
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      $myConnection=Connections::schedule_connection();
      Schema::connection($myConnection)->table('schedule', function (Blueprint $table) {
         $table->dropColumn('default_start_time');
      });
    }
}
