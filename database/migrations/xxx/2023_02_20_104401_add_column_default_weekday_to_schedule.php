<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Connections;


class AddColumnDefaultWeekdayToSchedule extends Migration
{
   use Connections;
   
   public function up()
    {
       $myConnection=Connections::schedule_connection();
       Schema::connection($myConnection)->table('schedule', function (Blueprint $table) {
            $table->unsignedTinyInteger('default_weekday')->default(0)->after('description');
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
         $table->dropColumn('default_weekday');
      });
    }
}
