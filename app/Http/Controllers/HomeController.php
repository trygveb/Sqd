<?php

namespace App\Http\Controllers;

use App\Models\Schedule\V_MemberSchedule;
use App\Http\Controllers\BaseController;

use Illuminate\Support\Facades\Auth;

//use Illuminate\Support\Facades\App;

class HomeController extends BaseController {

   /**
    * Create a new controller instance.
    *
    * @return void
    */
   public function __construct() {
      //$this->middleware('auth');
   }

   /**
    * Show sdCalls welcome view for authenticated and verified users
    * @return view
    */
   public function callsHome() {
      $names= $this->names();
      $names['application']='SdCalls';
      $names['routeRoot']='calls';
     

      return view('calls.welcome')->with('names', $names);
   }

   /**
    *  For sqd.se, NOT logged in, application not selected
    * @return view
    */
   public function home() {
//      dd('Homecontroller:home 1');
      if ($this->names()['application']==='SdSchema') {
         return $this->schemaHome();
      } elseif ($this->names()['application']==='SdCalls') {
         return $this->callsHome();
      }
//      dd('Homecontroller:home 2');
      return view('welcome')->with('names', $this->names());
//      return view('errors.403');
   }
   
   /**
    * Find subdomain indicating dev, test or production
    * The APP_NAME environment variable is supposed to be 
    *  dev.TOP_DOMAIN, test.TOP_DOMAIN or just TOP_DOMAIN (for production)
    * where TOP_DOMAIN is an environment variable (i.e. sqd.se)
    * @return string 'dev.', 'test.' or ''
    */
   private function getSub() {
      $sub='';
      $appName= config('app.name');
      
      $atoms=explode('.', $appName);
      if (count($atoms)==3) {
         $sub=$atoms[0].'.';
      }
      return $sub;
   }
   /**
    * Show schedule welcome view for guests
    * @return view
    */
   public function schemaHome() {
      $sub=$this->getSub();
  
      $urlRoot= sprintf('https://schema.%s%s', $sub,config('app.topDomain'));
      $names= $this->names();
      $names['application']='SdSchema';
      $names['routeRoot']='schedule';
//      dd('Homecontroller:schemaHome '.$urlRoot);
      //$application=$this->getApplication();
      if (Auth::check()) {
         if (Auth::user()->hasVerifiedEmail()) {

            //dd('schemaGuest, verified');
            $vMemberSchedules = V_MemberSchedule::where('user_id', Auth::user()->id)->get();
            $count = $vMemberSchedules->count();
            if (config('app.showNewrelease') || $count > 1) {
               $url= sprintf('%s/welcome', $urlRoot);
               return redirect($url);
            } else {
               $url= sprintf('%s/schedule/show/%d', $urlRoot,$vMemberSchedules[0]->schedule_id);
               return redirect($url);
            }
         } else {
            return view('auth.verify-email-notice')
               ->with('emailVerified','NO')
               ->with('names', $names);
         }
      }
      return view('schedule.welcome')
              ->with('mySchedulesCount' , 0)
              ->with('names', $names);

//      abort(403, 'Unauthorized action.');
   }

   /**
    * Show schedule welcome view for authenticated and verified users
    * @return view
    */
   public function schemaHomeOld() {
      $count = 0;
      if (Auth::check()) {
//         $myMemberSchedules = MemberSchedule::where('user_id', Auth::user()->id)->get();
//         $count = $myMemberSchedules->count();
//         if ($count == 1) {
//            return redirect(route('schedule.index', ['scheduleId' => $myMemberSchedules[0]->schedule_id]));
//         } else {
         return view('schedule.welcome', [
             'mySchedulesCount' => $count,
         ]);
      }
//      } else {
//         abort(403, 'Unauthorized action.');
//      }
   }

   public function switchLocale() {
      if (config('app.locale') == 'en') {
         config(['app.locale' => 'sv']);
      } else {
         config(['app.locale' => 'en']);
      }
      return redirect()->back()->withSuccess('You have switched to ' . config('app.locale'));
      ;
   }

   public function welcome() {
      $application=$this->getApplication();
      return view('welcome')->with('application',$application);
   }

}
