<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Classes\Utility;

class EnsureUserIsScheduleAdmin {

   /**
    * Check that user is ScheduleAdmin (=has admin authority on the schedule) or root 
    * The current scheduleId must be the last element in the current url
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
   public function handle(Request $request, Closure $next) {
      if (Auth::check()) {
         $user = Auth::user();

         if ($user->isScheduleAdministrator() || $user->isRoot()) {
            return $next($request);
         }
         $adminForSchedule = 0;
         $url = url()->full();
         if (str_contains($url, '/admin/')) {
            $atoms = explode('/', $url);
            $scheduleId = $atoms[count($atoms) - 1];
            //dd('0');
            if (!is_numeric($scheduleId)) {
               if (is_null($scheduleId)) {
                  return redirect()->back();
               }
            }

            $adminForSchedule = Utility::getAdminForSchedule($scheduleId);
         }
         if ($adminForSchedule > 0) {
            return $next($request);  // User has "schema" privileges 
         }
      }
      // No authority, just go back with no message
      return redirect()->back();
   }

}
