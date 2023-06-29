<?php

namespace App\Models\Schedule;

use Illuminate\Database\Eloquent\Model;

// This is a many-to-many relationship connecting members and schedules
// One member can attend many schedules
// One schedule has many members
class MemberSchedule extends Model {

   protected $connection = 'schedule';
   protected $table = 'member_schedule';
   protected $fillable = [
       'user_id',
       'schedule_id',
       'admin'
   ];

}
