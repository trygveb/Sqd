<?php

namespace App\Http\Controllers;

use App\Classes\CreateEmailList;
use App\Http\Controllers\BaseController;
use App\Models\Schedule\V_MemberSchedule;
use App\Models\Schedule\V_MemberScheduleDate;
use App\Models\Schedule\MemberSchedule;
use App\Models\Schedule\MemberScheduleDate;
use App\Models\Schedule\Schedule;
use App\Models\Schedule\ScheduleDate;
use App\Models\User;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * class SchemaController 
 *
 * @author Trygve Botnen, 2021
 */
class SchemaController extends BaseController {

   public function __construct() {
      $this->middleware('auth');
   }

//Show a schedule
   public function index($scheduleId, $showHistory=0) {
      $mytime = Carbon::now();
      $today = $mytime->toDateString();

//Fetch data from the database
      $schedule = Schedule::find($scheduleId);
      $memberSchedules = MemberSchedule::where('schedule_id', $scheduleId)->get();
      // Check if user is member, if not redirect back
      
      if ($memberSchedules->where('user_id', Auth::id())->count() ==0) {
         $vMemberSchedules= V_MemberSchedule::where('user_id', Auth::id())->get();
            return view('schedule.welcome', [
                  'mySchedulesCount' => $vMemberSchedules->count(),
                  'vMemberSchedules' => $vMemberSchedules,
                  'names' => $this->names()
               ]);         
      }
      if ($showHistory==0) {
         $vMemberScheduleDates = V_MemberScheduleDate::where('schedule_date', '>=', $today)
              ->where('schedule_id', $scheduleId)
              ->get();
         $scheduleDates = ScheduleDate::where('schedule_id', $scheduleId)
              ->where('schedule_date', '>=', $today)
              ->get();
      } else {
         $vMemberScheduleDates = V_MemberScheduleDate::where('schedule_id', $scheduleId)
              ->get();
         $scheduleDates = ScheduleDate::where('schedule_id', $scheduleId)
              ->get();
      }
// Initialize the arrays to use in the view
      $statusSums = array();
      $statuses = array();
      $memberNames = array();
      $groups = array();

// Create the arrays
      $this->calaculateStatusSums($scheduleDates, $vMemberScheduleDates, $statuses, $statusSums);
      $this->createNamesAndGroupsArrays($memberSchedules, $memberNames, $groups);

      return view('schedule.schema', [
          'schedule' => $schedule,
          'numberOfDates' => count($scheduleDates),
          'currentUser' => Auth::user(),
          'scheduleDates' => $scheduleDates,
          'statuses' => $statuses,
          'memberNames' => $memberNames,
          'groups' => $groups,
          'statusSums' => $statusSums,
          'admin' => $this->isAdmin($scheduleId),
          'names' => $this->names(),
          'showHistory' => $showHistory
      ]);
   }

//Called from the index function
   private function createNamesAndGroupsArrays($memberSchedules, &$names, &$groups) {
      foreach ($memberSchedules as $memberSchedule) {
         $userId = $memberSchedule->user_id;
         $user = User::find($userId);
         $names[$userId] = $user->name;
         $groups[$userId] = $memberSchedule->group_size;
      }
   }
   
   /**Calcualte status sums. NOTE! Will only work for group size 1 or 2.
    * 
    * @param collection ScheduleDates          all (future) ScheduleDates for the given schedule
    * @param collection $vMemberScheduleDates  all (future) V_MemberScheduleDates for the given  schedule
    * @param out $statusSums
    */
   private function calaculateStatusSums($scheduleDates, $vMemberScheduleDates, &$statuses, &$statusSums) {
      foreach ($scheduleDates as $scheduleDate) {
         $memberSchedulesForDate = $vMemberScheduleDates
                 ->where('schedule_date', $scheduleDate->schedule_date)
                 ->all();
         $sum = array(
             'Y' => 0,
             'N' => 0,
             'M' => 0, // M= Maybe
             'NA' => 0, // NA=No Answer
         );
         foreach ($memberSchedulesForDate as $memberScheduleForDate) {
            $statuses[$memberScheduleForDate->user_id][$scheduleDate->id] = $memberScheduleForDate->status;
            switch ($memberScheduleForDate->status) {
               case 0:  $sum['NA'] += $memberScheduleForDate->group_size;
                        break;
               case 1:  $sum['Y']++; // If one member says yes, the others says no
                        $sum['N'] += ($memberScheduleForDate->group_size-1);
                        break;
               case 2:  $sum['Y'] += 2;
                        break;
               case 3:  $sum['N'] += $memberScheduleForDate->group_size;
                  break;
               default: $sum['M'] += $memberScheduleForDate->group_size;
                  break;
            }
         }
         array_push($statusSums, $sum);
      }
   }

// Add dates to a schedule 
// Returns to Add/Remove dates menu
   public function addDates(Request $request) {
      $data = request()->all();
      $numberOfDays = 0;
      $startDate = '';
      $scheduleId = $data["scheduleId"];
      foreach ($data as $key => $value) {
         if (substr($key, 0, 8) === 'quantity') {
            $numberOfDays = $value;
         } else if (substr($key, 0, 9) === 'startDate') {
            $startDate = $value;
         }
      }
      $date0 = Carbon::createFromFormat('Y-m-d', $startDate);
      DB::beginTransaction();
      try {
         for ($d1 = 0; $d1 < $numberOfDays; $d1++) {
            $scheduleDate = new ScheduleDate();
            $scheduleDate->schedule_date = $date0->addDays(7 * $d1)->toDateString();
            $scheduleDate->schedule_id = $scheduleId;
            $scheduleDate->save();
            $this->addMembersToScheduleDate($scheduleId, $scheduleDate->id);
         }
      } catch (\Exception $e) {
         DB::rollBack();
      }


      DB::commit();
//      $schedule = Schedule::find($scheduleId);
      return redirect(route('schedule.showAddRemoveDates', ['scheduleId' => $scheduleId]));
   }

// Remove dates from a schedule
   public function removeDates(Request $request) {
      $data = request()->all();
      $scheduleId = $data["scheduleId"];
      foreach ($data as $key => $value) {
         if (substr($key, 0, 6) === 'delete') {
            $atoms = explode('_', $key);
            $scheduleDateId = $atoms[1];
            $scheduleDate = ScheduleDate::find($scheduleDateId);
            $scheduleDate->delete();
         } else if (substr($key, 0, 10) === 'scheduleId') {
//            $scheduleId = $value;
         }
      }
//      $schedule = Schedule::find($scheduleId);
      return redirect(route('schedule.showAddRemoveDates', ['scheduleId' => $scheduleId]));
   }

// Private function called from addDates
   private function addMembersToScheduleDate($scheduleId, $scheduleDateId) {
      $memberSchedules = MemberSchedule::where('schedule_id', $scheduleId)->get();
      foreach ($memberSchedules as $memberSchedule) {
         $memberScheduleDate = new MemberScheduleDate();
         $memberScheduleDate->user_id = $memberSchedule->user_id;
         $memberScheduleDate->schedule_date_id = $scheduleDateId;
         $memberScheduleDate->save();
      }
   }


// Update the admin flags or remove members from a schedule
// Called from the adminMembers view
// Returns Members view
   public function updateMember(Request $request) {
      $data = request()->all();
      $scheduleId = $data["scheduleId"];
//      $schedule = Schedule::find($scheduleId);


// Update admin flags. Loop over all users in the schedule
      $userIds = MemberSchedule::where('schedule_id', $scheduleId)->get()->pluck('user_id');
      foreach ($userIds as $userId) {
         $adminName = 'admin_' . $userId;
         $memberSchedule = MemberSchedule::where('user_id', $userId)
                 ->where('schedule_id', $scheduleId)
                 ->first();
         if ($request->has($adminName)) {
            $memberSchedule->admin = 1;
         } else {
            $memberSchedule->admin = 0;
         }
         $memberSchedule->save();
      }
      // Remove member(s) from all scheduledates in the schedule
      $scheduleDates=ScheduleDate::where('schedule_id', $scheduleId)->get();
      foreach ($data as $key => $value) {
         if (substr($key, 0, 6) === 'delete') {
            $atoms = explode('_', $key);
            $userId = $atoms[1];
            foreach($scheduleDates as $scheduleDate) {
                $memberScheduleDate= MemberScheduleDate::where('user_id',$userId)->where('schedule_date_id',$scheduleDate->id)->first();
                $memberScheduleDate->delete();
            }
            $memberSchedule = MemberSchedule::where('user_id', $userId)
                    ->where('schedule_id', $scheduleId)
                    ->first();
            $memberSchedule->delete();
            
            
         }
      }

      return $this->ShowViewMembers($scheduleId);
   }

// Show the Register New User Form
   public function showRegisterUser($scheduleId) {
      return view('RegisterUser', [
          'scheduleId' => $scheduleId,
          'names' => $this->names(),
      ]);
   }

   public function showAddRemoveDates($scheduleId) {

      $schedule = Schedule::find($scheduleId);
      $lastScheduleDate = $this->getLastScheduleDate($schedule);

      $danceTime = '19:00';   // TODO: Remve this hardcoded time
// Create a Carbopnd date in order to calculate next date a week ahead, and the day of the week
      $dt = Carbon::parse($lastScheduleDate->schedule_date);
      $nextDate = substr($dt->addWeeks(1), 0, 10);
      $currentLocale = LaravelLocalization::getCurrentLocale();
//      if ($currentLocale ==='se') {
//         $currentLocale= 'sv';
//      }
      $weekDays = $dt->locale($currentLocale)->dayName;

// Get day of week (0=Sunday, e= Monday etc).
// This non-ISO format is used to be compatible with Javascript
      $weekDaysNumber = $dt->dayOfWeek;

      $mytime = Carbon::now();
      $today = $mytime->toDateString();

      $scheduleDates = ScheduleDate::where('schedule_id', $schedule->id)
              ->where('schedule_date', '>=', $today)
              ->get();

      return view('schedule.addRemoveDates', [
          'schedule' => $schedule,
          'scheduleDates' => $scheduleDates,
          'currentUser' => Auth::user(),
          'weekdays' => $weekDays,
          'weekDaysNumber' => $weekDaysNumber,
          'lastScheduleDate' => $lastScheduleDate,
          'danceTime' => $danceTime,
          'nextDate' => $nextDate,
          'names' => $this->names(),
          'admin' => $this->isAdmin($scheduleId)
      ]);
   }

// Called from showViewAddRemoveDates
// Return the last schedule date for a schedule. If no date exist, return today's date
   private function getLastScheduleDate($schedule) {
      $lastScheduleDate = ScheduleDate::where('schedule_id', $schedule->id)
              ->orderByDesc('schedule_date')
              ->first();

      if (is_null($lastScheduleDate)) {
         $lastScheduleDate = new ScheduleDate();
         $lastScheduleDate->schedule_date = substr(Carbon::now()->toISOString(), 0, 10);
      }
      return $lastScheduleDate;
   }

   /**
    * Register or unregister from a schedule. Called from view mySchemas.
    * @param Request $request
    * @return type
    */
   public function registerForSchemas(Request $request) {
      $dataFields = request()->all();
      $userId = request()->userId;
      foreach ($dataFields as $key => $value) {
         if (str_starts_with($key, 'mySchedule_') && $value == 0) {
            // User wants to unregister
            $scheduleId = substr($key, 11);
            $memberSchedule = MemberSchedule::where('user_id', $userId)
                            ->where('schedule_id', $scheduleId)->first();
            if (!is_null($memberSchedule)) {      
                    $memberSchedule->delete();
            }
         } else if (str_starts_with($key, 'otherSchedule_') && $value == 1) {
            // User wants register
            $scheduleId = substr($key, 14);
            $pwKey = 'pwInput_' . $scheduleId;
            $password = $dataFields[$pwKey];
            $groupsSizeKey='numberInput_'.$scheduleId;
            $groupSize=$dataFields[$groupsSizeKey];
            $schedule = Schedule::find($scheduleId);
            if (strlen($schedule->password) == 0 || Hash::check($password, $schedule->password)) {
               $schedule->addMember($userId,$groupSize);
            } else {
               return $this->showMySchemas();  // Bad passord
               
            }
         } else {
            
         }
      }
      return $this->showMySchemas();
   }

// SHow the Members view
   public function ShowViewMembers($scheduleId) {

      $schedule = Schedule::find($scheduleId);
      $admin = V_MemberSchedule::where('schedule_id', $scheduleId)
              ->where('user_id', Auth::user()->id)
              ->pluck('admin')
              ->first();
      if ($admin === 0 && Auth::user()->authority === 0) {
         return view('errors.403')->with('names', $this->names());
      }
      $createEmailListAction = new CreateEmailList();
      $emailArray = $createEmailListAction->execute($schedule->id);
      $emails = '';
      foreach ($emailArray as $email) {
         $emails .= $email . ', ';
      }
      $emails = substr($emails, 0, -2);
      $vMemberSchedules = V_MemberSchedule::where('schedule_id', $schedule->id)->get();
      $memberUserIds = [];
      foreach ($vMemberSchedules as $member) {
         array_push($memberUserIds, $member->user_id);
      }
      $allUsers = User::all();

      $nonMembers = collect();
      foreach ($allUsers as $user) {
         if (!in_array($user->id, $memberUserIds)) {
            $nonMember = new V_MemberSchedule();
            $nonMember->schedule_id = $schedule->id;
            $nonMember->user_id = $user->id;
            $nonMember->user_name = $user->name;
            $nonMember->schedule_name = $schedule->name;
            $nonMember->email = $user->email;
            $nonMember->admin = 0;
            $nonMember->group = 1;          // TOD: FIX
            $nonMembers->push($nonMember);
         }
      }

      return view('schedule.adminMembers', [
          'schedule' => $schedule,
          'vMemberSchedules' => $vMemberSchedules,
          'nonMembers' => $nonMembers,
          'currentUser' => Auth::user(),
          'emails' => $emails,
          'names' => $this->names(),
          'admin' => $this->isAdmin($scheduleId)
      ]);
   }

   // Show my schemas
   public function showMySchemas() {
      $myVMemberSchedules = V_MemberSchedule::where('user_id', Auth::id())->get();
      $myScheduleIds = $myVMemberSchedules->pluck('schedule_id');
      foreach ($myVMemberSchedules as $myVMemberSchedule) {
         $myVMemberSchedule->admins = V_MemberSchedule::where('schedule_id', $myVMemberSchedule->schedule_id)
                         ->where('admin', 1)
                         ->get()->implode('user_name', ',');
      }
      $otherSchedules = Schedule::all()->except($myScheduleIds->toArray());
      foreach ($otherSchedules as $otherSchedule) {
         $otherSchedule->admins = V_MemberSchedule::where('schedule_id', $otherSchedule->id)
                         ->where('admin', 1)
                         ->get()->implode('user_name', ',');
      }

      return view('schedule.mySchemas', [
          'myVMemberSchedules' => $myVMemberSchedules,
          'otherSchedules' => $otherSchedules,
          'admin' => Auth::user()->authority,
          'names' => $this->names()
      ]);
   }

// Return 1 if user is superAdmin or admin for  a given schedule
   private function isAdmin($scheduleId) {
      $vMemberSchedules = V_MemberSchedule::where('schedule_id', $scheduleId)->get();
      if (is_null($vMemberSchedules->where('user_id', Auth::user()->id)->first())) {
         return 0;
      } else {
         return $vMemberSchedules->where('user_id', Auth::user()->id)->first()->admin | Auth::user()->authority;
      }
   }

// Show view AdminComments
   public function showViewAdminComments($scheduleId) {

// $schedule = Schedule::find($scheduleId);
      $mytime = Carbon::now();
      $today = $mytime->toDateString();
//      $vMemberScheduleDates = V_MemberScheduleDate::where('schedule_date', '>=', $today)->get();
      $schedule = Schedule::find($scheduleId);
      $scheduleDates = ScheduleDate::where('schedule_id', $scheduleId)
              ->where('schedule_date', '>=', $today)
              ->get();
      $vMemberSchedules = V_MemberSchedule::where('schedule_id', $scheduleId)->get();
      $admin = $vMemberSchedules->where('user_id', Auth::user()->id)->first()->admin | Auth::user()->authority;

      return view('schedule.adminComments', [
          'schedule' => $schedule,
          'currentUser' => Auth::user(),
          'scheduleDates' => $scheduleDates,
          'names' => $this->names(),
          'admin' => $this->isAdmin($scheduleId)
      ]);
   }

// Show view SchemaEdit for updating the member's attendance status
   public function showViewEdit(Schedule $schedule) {

      $mytime = Carbon::now();
      $today = $mytime->toDateString();
      $vMemberScheduleDates = V_MemberScheduleDate::where('schedule_date', '>=', $today)->get();
      $scheduleDates = ScheduleDate::where('schedule_id', $schedule->id)
              ->where('schedule_date', '>=', $today)
              ->get();
      $statuses = array();
      foreach ($scheduleDates as $scheduleDate) {
         $memberSchedulesForDate = $vMemberScheduleDates
                 ->where('schedule_date', $scheduleDate->schedule_date)
                 ->where('user_id', Auth::user()->id)
                 ->all();
         foreach ($memberSchedulesForDate as $memberScheduleForDate) {
            $statuses[$scheduleDate->id] = $memberScheduleForDate->status;
         }
      }
      $groupSize = MemberSchedule::where('schedule_id', $schedule->id)
                      ->where('user_id', Auth::user()->id)
                      ->first()
              ->group_size;
      ;
      return view('schedule.schemaEdit', [
          'schedule' => $schedule,
          'currentUser' => Auth::user(),
          'scheduleDates' => $scheduleDates,
          'groupSize' => $groupSize,
          'names' => $this->names(),
          'statuses' => $statuses, // TODO: Only the statuses of the current user is needed
      ]);
   }

//   // Show the Admin Menu. TODO: Cretae a real menu instead of a set of buttons
//   public function showAdminMenu(Schedule $schedule) {
//
////      $schedule = Schedule::find($scheduleId);
//      return view('AdminMenu', [
//          'schedule' => $schedule,
//          'currentUser' => Auth::user(),
//      ]);
//   }
//Updating the member's attendance status
   public function updateAttendance(Request $request) {
      $data = request()->all();
//      dd($data);
      $scheduleId = 0;
      foreach ($data as $key => $value) {
         if (substr($key, 0, 6) === 'status') {
            $atoms = explode('_', $key);
            $userId = $atoms[1];
            $scheduleDateId = $atoms[2];
            $memberScheduleDate = MemberScheduleDate::where('user_id', $userId)
                    ->where('schedule_date_id', $scheduleDateId)
                    ->first();
            $memberScheduleDate->status = $value;
            $memberScheduleDate->save();
         } else if (substr($key, 0, 10) === 'scheduleId') {
            $scheduleId = $value;
         }
      }
      return redirect(route('schedule.index', ['scheduleId' => $scheduleId]));
   }

// Update comemnts for one or more dates
   public function updateComments(Request $request) {
      $data = request()->all();
      $scheduleId = 0;
      foreach ($data as $key => $value) {
         if (substr($key, 0, 7) === 'comment') {
            $atoms = explode('_', $key);
            $scheduleDateId = $atoms[1];
            $scheduleDate = ScheduleDate::find($scheduleDateId);
            $scheduleDate->comment = $value;
            $scheduleDate->save();
         } else if (substr($key, 0, 10) === 'scheduleId') {
            $scheduleId = $value;
         }
      }
      $schedule = Schedule::find($scheduleId);
// return redirect(route('admin.showMenu',['schedule' =>$schedule]));
      return redirect(route('schedule.index', ['scheduleId' => $schedule->id]));
   }
   
   public function welcome() {
      $vMemberSchedules = V_MemberSchedule::where('user_id', Auth::user()->id)->get();
      $count = $vMemberSchedules->count();

      return view('schedule.welcome', [
          'mySchedulesCount' => $count,
          'names' => $this->names(),
          'vMemberSchedules' => $vMemberSchedules
      ]);
      
   }

}
