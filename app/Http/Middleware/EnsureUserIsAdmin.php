<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Schedule\V_MemberSchedule;

class EnsureUserIsAdmin {

   /**
    * Check that user is "superuser" ($user->authority > 0 ) or has admin authority on the schedule
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
   public function handle(Request $request, Closure $next) {
      if (Auth::check()) {
         $user = $request->user();
         $adminForSchedule = 0;
         $url = url()->full();
         if (str_contains($url, '/admin/')) {
//            dd($url);
            $atoms = explode('/', $url);
            $scheduleId = $atoms[count($atoms) - 1];
            if (!is_numeric($scheduleId)) {
               $data = request()->all();
               $scheduleId = $data["scheduleId"];               
            }
               $adminForSchedule = V_MemberSchedule::where('schedule_id', $scheduleId)
                    ->where('user_id', Auth::user()->id)
                    ->pluck('admin')
                    ->first();
         }
         if ($user->authority > 0 || $adminForSchedule > 0) {
            return $next($request);
         }
      }
      // No authority, just go back with no message
      return redirect()->back();

   }

}
