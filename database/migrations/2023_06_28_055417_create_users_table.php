<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Connections;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $myConnection=Connections::laravel_connection();
        Schema::connection($myConnection)->table('users', function (Blueprint $table) {
            $table->string('name', 100)->nullable();
            $table->string('language', 6)->default('en-US');
            $table->string('voice_type', 8)->default('WaveNet');
            $table->unsignedTinyInteger('voice_gender')->default(1);
            $table->string('voice_name', 15)->default('en-US-Wavenet-B');
            $table->decimal('voice_pitch', 4, 1)->default(0);
            $table->decimal('speaking_rate', 4)->default(1);
            $table->decimal('volume_gain_db', 4, 1)->default(0);
            $table->unsignedBigInteger('program_id')->default(3);
            $table->boolean('include_start_formation')->default(true);
            $table->boolean('include_end_formation')->default(true);
            $table->boolean('include_formations_in_repeats')->default(false);
            $table->unsignedBigInteger('definition_id')->default(1)->index('users_FK');
            $table->unsignedTinyInteger('repeats')->default(2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
