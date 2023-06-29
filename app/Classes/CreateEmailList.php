<?php

namespace App\Classes;

use App\Models\Schedule\V_MemberSchedule;


class CreateEmailList {

   public function execute($scheduleId) {
     // $schedule= Schedule::find($scheduleId);
      $vMemberSchedules= V_MemberSchedule::where('schedule_id', $scheduleId)->get();
      $emails= array();
      foreach ($vMemberSchedules as $vMemberSchedule) {
         array_push($emails, $vMemberSchedule->email);
      }
      return $emails;
   }

}
