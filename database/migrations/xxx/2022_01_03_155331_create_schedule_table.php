<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('schedule')->create('schedule', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique();
            $table->text('description')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
        DB::connection('schedule')->statement("ALTER TABLE `schedule` comment='This table should contain a (unique) name for each schedule/course'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('schedule')->dropIfExists('schedule');
    }
}
