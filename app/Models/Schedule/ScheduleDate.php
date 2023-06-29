<?php

namespace App\Models\Schedule;

use Illuminate\Database\Eloquent\Model;

// This table contains all dates for all schedules, with start-time and comment
class ScheduleDate extends Model {

   protected $connection = 'schedule';
   protected $table = 'schedule_date';
   protected $fillable = [
       'schedule_date',
   ];

   public function schedule() {
      return $this->belongsTo(Schedule::class);
   }

}
