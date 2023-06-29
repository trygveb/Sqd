<?php

namespace App\Models\Schedule;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class V_MemberScheduleDate extends Model {

   protected $connection = 'schedule';
   public $timestamps = false;
   protected $table = 'v_member_schedule_date';

}
