<?php

namespace App\Traits;

//use Exception;
//use Illuminate\Http\Request;

trait Connections {

   public static function laravel_connection() {
      return env('DB_DATABASE', 'laravel');
   }

   public static function schedule_connection() {
      return env('DB_DATABASE_SCHEDULE', 'schedule');
   }
   public static function calls_connection() {
      return env('DB_DATABASE_SD_CALLS', 'calls');
   }

}
