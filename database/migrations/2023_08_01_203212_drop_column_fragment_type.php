<?php

use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
use App\Traits\Connections;

return new class extends Migration
{
      use Connections;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
      $myConnection = Connections::calls_connection();
      DB::connection($myConnection)->statement('ALTER TABLE fragment DROP FOREIGN KEY fragment_FK');
      DB::connection($myConnection)->statement('ALTER TABLE fragment DROP INDEX fragment_uidx');
      DB::connection($myConnection)->statement('ALTER TABLE fragment DROP COLUMN type_id');
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
