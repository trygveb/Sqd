<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;
use App\Traits\Connections;

class RefactorUserNames extends Migration
{
   use Connections;
    public function up()
    {
        Schema::connection(Connections::laravel_connection())->table('users', function (Blueprint $table) {
            $table->string('first_name', 24)->after('name')->default('first_name');
            $table->string('middle_name', 24)->nullable()->after('first_name');
            $table->string('family_name', 24)->after('middle_name')->default('family_name');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::connection(Connections::laravel_connection())->table('users', function (Blueprint $table) {
          $table->dropColumn(['first_name', 'middle_name', 'family_name']);
      });
    }
}
