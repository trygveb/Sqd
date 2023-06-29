<?php

namespace App\Models\Schedule;

use Illuminate\Database\Eloquent\Model;

class V_MemberSchedule extends Model {

   protected $connection = 'schedule';
   protected $table = 'v_member_schedule';
   public $timestamps = false;

}
