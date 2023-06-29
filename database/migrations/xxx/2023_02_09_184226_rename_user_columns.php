<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\Connections;

class RenameUserColumns extends Migration
{
   use Connections;
   
    public function up()
    {
       $myConnection = Connections::laravel_connection();
      DB::connection($myConnection)->statement("ALTER TABLE users RENAME COLUMN name TO old_name");
       
      Schema::connection($myConnection)->table('users', function (Blueprint $table) {
            $table->dropColumn('old_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $myConnection=Connections::laravel_connection();
      Schema::connection($myConnection)->table('users', function (Blueprint $table) {
            $table->string('name', 24)->after('id');
        });


    }
}
