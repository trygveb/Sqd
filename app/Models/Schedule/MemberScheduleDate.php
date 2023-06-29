<?php

namespace App\Models\Schedule;

use Illuminate\Database\Eloquent\Model;

// This is a many-to-many relationship, with the status for each member on each schedule date
class MemberScheduleDate extends Model
{
     protected $connection = 'schedule';
     protected $table = 'member_schedule_date';
        protected $fillable = [
       'user_id',
       'schedule_date_id',
   ];

}
