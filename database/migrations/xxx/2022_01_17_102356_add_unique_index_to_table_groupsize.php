<?php

use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;

class AddUniqueIndexToTableGroupsize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE schedule.groupsize ADD CONSTRAINT groupsize_UN UNIQUE KEY (user_id)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE schedule.groupsize DROP KEY groupsize_UN');
    }
}
