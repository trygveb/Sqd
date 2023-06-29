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
//use Illuminate\Support\Facades\Validator;
//use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Classes\Utility;

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
   public function index($scheduleId, $showHistory = 0) {
      $mytime = Carbon::now();
      $today = $mytime->toDateString();

//Fetch data from the database
      $schedule = Schedule::find($scheduleId);
      $memberSchedules = MemberSchedule::where('schedule_id', $scheduleId)->get();

      // Check if user is member or root, if not redirect back

      $editAllowed = true;
      $user = Auth::user();
      if ($memberSchedules->where('user_id', $user->id)->count() == 0) {
         // User is not member
         if ($user->isRoot()) {
            // User is root, and may view but not edit
            $editAllowed = false;
         } else {
            // User is not root nor member. Redirect back
            $vMemberSchedules = V_MemberSchedule::where('user_id', $user->id)->get();
            return view('schedule.welcome', [
                'mySchedulesCount' => $vMemberSchedules->count(),
                'vMemberSchedules' => $vMemberSchedules,
                'names' => $this->names()
            ]);
         }
      }
      if ($showHistory == 0) {
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
          'currentUser' => $user,
          'scheduleDates' => $scheduleDates,
          'statuses' => $statuses,
          'memberNames' => $memberNames,
          'groups' => $groups,
          'statusSums' => $statusSums,
          // 'admin' => Utility::getAdminForSchedule($scheduleId),
          'names' => $this->names(),
          'showHistory' => $showHistory,
          'editAllowed' => $editAllowed,
          'manageMembers' => ($user->isScheduleOwner($scheduleId) || $user->isRoot()),
          'scheduleAdmin' => ($user->isScheduleOwner($schedule->id) || $user->hasLimitedAuthority($schedule->id) || $user->isRoot())
      ]);
   }

   public function showReleaseNotes_2_2() {
       $user = Auth::user();
      return view('schedule.releaseNotes_2_2', [
          'names' => $this->names(),
          'isRoot' => $user->isRoot()
      ]);
   }

//Called from the index function
   private function createNamesAndGroupsArrays($memberSchedules, &$names, &$groups) {
      foreach ($memberSchedules as $memberSchedule) {
         $userId = $memberSchedule->user_id;
         // $user = User::find($userId);
         $names[$userId] = $memberSchedule->name_in_schema;
         $groups[$userId] = $memberSchedule->group_size;
      }
   }

   /*    * Calcualte status sums. NOTE! Will only work for group size 1 or 2.
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
               case 0: $sum['NA'] += $memberScheduleForDate->group_size;
                  break;
               case 1: $sum['Y']++; // If one member says yes, the others says no
                  $sum['N'] += ($memberScheduleForDate->group_size - 1);
                  break;
               case 2: $sum['Y'] += 2;
                  break;
               case 3: $sum['N'] += $memberScheduleForDate->group_size;
                  break;
               default: $sum['M'] += $memberScheduleForDate->group_size;
                  break;
            }
         }
         array_push($statusSums, $sum);
      }
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
      $memberSchedule = MemberSchedule::where('schedule_id', $schedule->id)
              ->where('user_id', Auth::user()->id)
              ->first();

      ;
      return view('schedule.schemaEdit', [
          'schedule' => $schedule,
          'memberSchedule' => $memberSchedule,
          'currentUser' => Auth::user(),
          'scheduleDates' => $scheduleDates,
          'names' => $this->names(),
          'statuses' => $statuses, // TODO: Only the statuses of the current user is needed
      ]);
   }

//Updating the member's attendance status
   public function updateAttendance(Request $request) {
      $data = request()->all();
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

////////////////////////////////////////////////////////////// Date handling
   
   public function showAddRemoveDates($scheduleId) {
      $user = Auth::user();
      if ($user->isScheduleOwner($scheduleId) || $user->hasLimitedAuthority($scheduleId) || $user->isRoot()) {

         $schedule = Schedule::find($scheduleId);

         $danceDay = $schedule->default_weekday;
         $danceTime = $schedule->default_start_time;

         $minScheduleDate = $this->getMinScheduleDate($schedule);
         $nextScheduleDate = $this->getNextScheduleDate($schedule);
         $currentLocale = LaravelLocalization::getCurrentLocale();
         $weekDayName = $nextScheduleDate->locale($currentLocale)->dayName;
         $today = Carbon::now()->toDateString();
         $numberOfFutureScheduleDates = ScheduleDate::where('schedule_id', $schedule->id)
                         ->where('schedule_date', '>=', $today)
                         ->get()->count();

            $scheduleDates = ScheduleDate::where('schedule_id', $schedule->id)
                    ->where('schedule_date', '>=', $today)
                    ->get();

         return view('schedule.admin.addRemoveDates', [
             'schedule' => $schedule,
             'scheduleDates' => $scheduleDates,
             'currentUser' => $user,
             'weekdays' => $weekDayName,
             'weekDaysNumber' => $danceDay,
             'danceTime' => substr($danceTime, 0, 5),
             'nextDate' => $nextScheduleDate->toDateString(),
             'minDate' => $minScheduleDate->toDateString(),
             'maxDate' => $this->getMaxScheduleDate($numberOfFutureScheduleDates)->toDateString(),
             'maxNumberOfFutureDates' => config('app.maxNumberOfFutureDates')-$numberOfFutureScheduleDates ,
             'names' => $this->names(),
             'isRoot' => $user->isRoot(),
             'manageMembers' => ($user->isScheduleOwner($scheduleId) || $user->isRoot()),
             'isScheduleAdmin' => $user->hasLimitedAuthority($schedule->id) || $user->isScheduleOwner($scheduleId) || $user->isRoot(),
             'isScheduleOwner' => $user->isScheduleOwner($schedule->id)
         ]);
      } else {
         return Redirect::back();
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
            // As date0 is modified by addDays, we must only add 7 days each loop
            // (but zero days on the first loop)
            $scheduleDate->schedule_date = $date0->addDays(7 * min(1, $d1))->toDateString();
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

   /**
    * Return the last schedule date for a schedule, plus one day.
    * If no date exist, or the last schedule date is in the past, return today's date
    * @param Schedule      $schedule
    * @return Carbon date 
    */
   private function getMinScheduleDate($schedule) {
      $now = Carbon::now();
      $lastScheduleDate = ScheduleDate::where('schedule_id', $schedule->id)
              ->orderByDesc('schedule_date')
              ->first();

      if (is_null($lastScheduleDate)) {
         $minScheduleDate = $now;
      } else {
         $minScheduleDate = Carbon::createFromFormat('Y-m-d', $lastScheduleDate->schedule_date)->addDays(1);
         if ($now->greaterThan($minScheduleDate)) {
            $minScheduleDate = $now;
         }
      }
      return $minScheduleDate;
   }

   /**
    * Return the maximum allowed schedule date for a schedule.
    * It is one year from now, unless the maximum number of dates is too large
    * @param  int    $numberOfFutureScheduleDates
    * @return Carbon date 
    */
   private function getMaxScheduleDate($numberOfFutureScheduleDates) {
      if ($numberOfFutureScheduleDates >= config('app.maxNumberOfFutureDates')) {
         $maxScheduleDate = Carbon::now()->subDay();
      } else {
         $maxScheduleDate = Carbon::now()->addYear();
      }
      return $maxScheduleDate;
   }

   /**
    * Return a suggestion for the next schedule date for a schedule, .
    * If no date exist, or if the last existing schedule date is too far in the past,
    * return nearest coming default weekday
    * @param Schedule      $schedule
    * @return Carbon date 
    */
   private function getNextScheduleDate($schedule) {
      $now = Carbon::now();
      $lastScheduleDate = ScheduleDate::where('schedule_id', $schedule->id)
              ->orderByDesc('schedule_date')
              ->first();

      if (is_null($lastScheduleDate)) {
         $nextScheduleDate = $this->getNearestDefaultWeekday($schedule, $now);
      } else {
         $carbonLastScheduleDate = Carbon::createFromFormat('Y-m-d', $lastScheduleDate->schedule_date);
         // Normally $nextScheduleDate is seven days after $lastScheduleDate
         // But if $lastScheduleDate is not on a standard weekday, add a number less than seven
         $dow = $carbonLastScheduleDate->dayOfWeekIso;
         $delta = $schedule->default_weekday - $dow;
         if ($delta <= 0) {
            $delta += 7;
         }
         $nextScheduleDate = Carbon::createFromFormat('Y-m-d', $lastScheduleDate->schedule_date)->addDays($delta);
         //$nextScheduleDate must be today or a day in the future
         if ($now->greaterThan($nextScheduleDate)) {
            $nextScheduleDate = $this->getNearestDefaultWeekday($schedule, $now);
         }
      }

      return $nextScheduleDate;
   }

   /**
    * Return the date for the nearest coming default weekday
    * @param Schedule      $schedule
    * @param Carbon date   $now
    * @return Carbon date
    */
   private function getNearestDefaultWeekday($schedule, $now) {
      $dow = $now->dayOfWeekIso;
      $delta = $schedule->default_weekday - $dow;

      if ($delta < 0) {
         $delta += 7;
      }
      return $now->addDays($delta);
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

////////////////////////////////////////////////////////////// Member handling 
// SHow the Members view
   public function ShowViewMembers($scheduleId, $status = []) {
      $currentUser = Auth::user();
      if ($currentUser->isScheduleOwner($scheduleId) || $currentUser->isRoot()) {
         $schedule = Schedule::find($scheduleId);
         $admin = V_MemberSchedule::where('schedule_id', $scheduleId)
                 ->where('user_id', $currentUser->id)
                 ->pluck('admin')
                 ->first();
         if ($admin === 0 && $currentUser->authority === 0) {
            return view('errors.403')->with('names', $this->names());
         }
         $createEmailListAction = new CreateEmailList();
         $emailArray = $createEmailListAction->execute($schedule->id);
         $emails = '';
         foreach ($emailArray as $email) {
            $emails .= $email . ', ';
         }
         $emails = substr($emails, 0, -2);
         $vMemberSchedules = V_MemberSchedule::where('schedule_id', $schedule->id)
                 ->orderBy('user_name', 'asc')
                 ->get();
         $memberUserIds = [];
         foreach ($vMemberSchedules as $member) {
            array_push($memberUserIds, $member->user_id);
         }
         $allUsers = User::all();

         $nonMembers = collect();
         foreach ($allUsers as $user) {
            if (!in_array($currentUser->id, $memberUserIds)) {
               $nonMember = new V_MemberSchedule();
               $nonMember->schedule_id = $schedule->id;
               $nonMember->user_id = $user->id;
               $nonMember->user_name = $user->name;
               $nonMember->schedule_name = $schedule->name;
               $nonMember->name_in_schema = explode(" ", $user->name)[0];
               $nonMember->email = $user->email;
               $nonMember->admin = 0;
               $nonMember->group_size = 1;          // TOD: FIX
               $nonMembers->push($nonMember);
            }
         }
         $sortedNonMembers = $nonMembers->sortBy('user_name');
         $sortedNonMembers->values()->all();
         return view('schedule.admin.members', [
             'schedule' => $schedule,
             'vMemberSchedules' => $vMemberSchedules,
             'nonMembers' => $sortedNonMembers,
             'currentUser' => $currentUser,
             'emails' => $emails,
             'status' => $status,
             'names' => $this->names(),
             'isRoot' => $currentUser->isRoot(),
             'manageMembers' => ($currentUser->isScheduleOwner($schedule->id) || $currentUser->isRoot()),
             'isScheduleAdmin' => $currentUser->hasLimitedAuthority($schedule->id) || $currentUser->isScheduleOwner($schedule->id) || $currentUser->isRoot(),
             'isScheduleOwner' => $currentUser->isScheduleOwner($schedule->id)
         ]);
      } else {
         return Redirect::back();
      }
   }

   public function ShowViewNotConnectedMembers($scheduleId, $status = []) {
      $currentUser = Auth::user();
      if ($currentUser->isScheduleOwner($scheduleId) || $currentUser->isRoot()) {

         $schedule = Schedule::find($scheduleId);
         if (is_null($schedule)) {
            dd("ShowNotViewMembers " . $scheduleId);
         }
         $admin = V_MemberSchedule::where('schedule_id', $scheduleId)
                 ->where('user_id', Auth::user()->id)
                 ->pluck('admin')
                 ->first();
         if ($admin === 0 && Auth::user()->authority === 0) {
            return view('errors.403')->with('names', $this->names());
         }
         $vMemberSchedules = V_MemberSchedule::where('schedule_id', $schedule->id)
                 ->orderBy('user_name', 'asc')
                 ->get();
         $memberUserIds = [];
         foreach ($vMemberSchedules as $member) {
            array_push($memberUserIds, $member->user_id);
         }
         $allUsers = User::all();

         $nonMembers = collect();
         $members = collect();
         foreach ($allUsers as $user) {
            if (!in_array($user->id, $memberUserIds)) {
               $nonMember = new V_MemberSchedule();
               $nonMember->schedule_id = $schedule->id;
               $nonMember->user_id = $user->id;
               $nonMember->user_name = $user->complete_name;
               $nonMember->schedule_name = $schedule->name;
               $nonMember->name_in_schema = explode(" ", $user->complete_name)[0];
               $nonMember->email = $user->email;
               $nonMember->admin = 0;
               $nonMember->group_size = 1;
               $nonMember->connected = 0;
               $nonMembers->push($nonMember);
            } else {
               $member = V_MemberSchedule::where('user_id', $user->id)
                               ->where('schedule_id', $schedule->id)->first();  //Only one is possible
               $member->connected = 1;
               $nonMembers->push($member);
            }
         }
         $sortedNonMembers = $nonMembers->sortBy('user_name');
         $sortedNonMembers->values()->all();
         return view('schedule.admin.notConnectedMembers', [
             'schedule' => $schedule,
             'nonMembers' => $sortedNonMembers,
             'members' => $members,
             'currentUser' => Auth::user(),
             'names' => $this->names(),
             'manageMembers' => ($currentUser->isScheduleOwner($schedule->id) || $currentUser->isRoot()),
             'admin' => Utility::getAdminForSchedule($scheduleId),
             'status' => $status
         ]);
      } else {
         return Redirect::back();
      }
   }

// Add registered member(s) to a schedule
// Called from the notConnectedMembers view
// Returns Members view
   public function connectMember(Request $request) {
      $data = request()->all();
      $scheduleId = $data["scheduleId"];
      $schedule = Schedule::find($scheduleId);
      foreach ($data as $key => $value) {
         if (substr($key, 0, 7) === 'connect') {
            // checkbox action is checked. Connect the user to the schema
            $atoms = explode('_', $key);
            $userId = $atoms[1];
            //$name= $data['nameInSchema_'.$userId];
            $groupSize = $data['number_' . $userId];
            $nameInSchema = $data['nameInSchema_' . $userId];

            $status = $schedule->addMember($userId, $groupSize, $nameInSchema);
            if ($status != 'OK') {
               return Redirect::back()->withErrors($status);
               //return $this->ShowViewMembers($scheduleId, $status);
            }
         }
      }
      return Redirect::back();
//      return $this->ShowViewMembers($scheduleId);
   }

// Update the admin flags or remove members from a schedule
// Called from the Members view
// Returns Members view
   public function updateMember(Request $request) {
      $data = request()->all();
      $scheduleId = $data["scheduleId"];

// Update admin flags and name_in_schema. Loop over all users in the schedule
      // $userIds = MemberSchedule::where('schedule_id', $scheduleId)->get()->pluck('user_id');
      $memberSchedules = MemberSchedule::where('schedule_id', $scheduleId)->get();
      $nameInSchema = $this->checkNameInSchema($memberSchedules, $request);
      if ($nameInSchema != "") {
         $errors = [
             'error' => __('Name in schema must be unique in the schema') . ': ' . $nameInSchema
         ];
         return $this->ShowViewMembers($scheduleId, $errors);
      }
      //foreach ($userIds as $userId) {
      foreach ($memberSchedules as $memberSchedule) {
         $userId = $memberSchedule->user_id;
         $user = User::find($memberSchedule->user_id);
         $adminHtmlElementName = 'admin_' . $userId;  //name of html element
//         dd(print_r($data, true));
         if ($request->has($adminHtmlElementName)) {
            $memberSchedule->admin = 1;
         } else if (!$user->isScheduleOwner($scheduleId)) {
            $memberSchedule->admin = 0;
         }

         $memberSchedule->name_in_schema = $request['nameInSchema_' . $userId];
         ;
         $numberName = 'number_' . $userId;   //name of html element
         $memberSchedule->group_size = $request[$numberName];
         $memberSchedule->save();
      }
      // dd(print_r($nameInSchemaNames, true));
      // Remove member(s) from all scheduledates in the schedule
      $scheduleDates = ScheduleDate::where('schedule_id', $scheduleId)->get();
      foreach ($data as $key => $value) {
         if (substr($key, 0, 6) === 'remove') {
            $atoms = explode('_', $key);
            $userId = $atoms[1];
            $this->removeMemberFromSchema($userId, $scheduleId, $scheduleDates);
         }
      }
      return back()->with('success', __('The update succeeded'));
   }

   private function checkNameInSchema($memberSchedules, $request) {
      $returnValue = "";
      foreach ($memberSchedules as $memberSchedule) {
         $userId = $memberSchedule->user_id;
         $newNameInSchema = $request['nameInSchema_' . $userId];
         //$oldNameInSchema=$memberSchedule->name_in_schema;
         $testMemberSchedules = $memberSchedules
                 ->where('name_in_schema', $newNameInSchema)
                 ->where('user_id', '<>', $userId)
                 ->all();
//         foreach ($testMemberSchedules as $testMemberSchedule) {
//            if ($testMemberSchedule->user_id != $userId) {
//               $returnValue= $newNameInSchema;
//               break;
//            }
//         }
         if (count($testMemberSchedules) > 0) {
            $returnValue = $newNameInSchema;
            break;
         }
      }
      return $returnValue;
   }

   // Remove a member from aschedula and all scheduledates in the schedule
   private function removeMemberFromSchema($userId, $scheduleId, $scheduleDates) {
      foreach ($scheduleDates as $scheduleDate) {
         $memberScheduleDate = MemberScheduleDate::where('user_id', $userId)->where('schedule_date_id', $scheduleDate->id)->first();
         $memberScheduleDate->delete();
      }
      $memberSchedule = MemberSchedule::where('user_id', $userId)
              ->where('schedule_id', $scheduleId)
              ->first();
      $memberSchedule->delete();
   }

////////////////////////////////////////////////////////////// 
// Show the Register New User Form
   public function showRegisterUser($scheduleId) {
      $schedule = Schedule::find($scheduleId);
      $isAdmin = Utility::getAdminForSchedule($scheduleId);
      return view('auth.registration', [
          'scheduleId' => $scheduleId,
          'scheduleName' => $schedule->name,
          'isAdmin' => $isAdmin,
          'names' => $this->names(),
      ]);
   }

// Show the Register New Schedule Form
   public function showRegisterSchedule() {
      return view('schedule.admin.registerNewSchedule', [
          'names' => $this->names(),
      ]);
   }

   public function registerNewSchedule(Request $request) {
      $data = request()->all();

      $schedule = new Schedule();
      $schedule->name = $data["schedule_name"];
      $schedule->default_weekday = $data["weekday"];
      $schedule->description = $data["schedule_description"];
      $schedule->default_start_time = $data["schedule_time"];
      $nameInSchema = $data["name_in_schema"];
      DB::beginTransaction();
      try {
         $schedule->save();
         // Connect current user (Schema administrator) to the new schedule
         // with admin=2 (Schema administrator authority)
         $status = $schedule->addMember(Auth::user()->id, 1, $nameInSchema, 2);
         if ($status != 'OK') {
            return back()->with('error', __('Schedule :name could not be registered! Status=:status',
                                    ['name' => $data['schedule_name'],
                                        'status' => $status]));
         }
      } catch (\Exception $e) {
         DB::rollBack();
         return back()->with('error', __('Schedule :name could not be registered!', ['name' => $data['schedule_name']]));
      }
      DB::commit();

      return back()->with('success', __('Schedule :name has been registered', ['name' => $data['schedule_name']]));
   }


/////////////////////////////////////////////////////////////////////////////
   public function ShowViewAdminRegisterMember($scheduleId, $status = []) {
      $schedule = Schedule::find($scheduleId);
      $user=Auth::user();
      if (is_null($schedule)) {
         dd("ShowNotViewMembers " . $scheduleId);
      }
      $admin = V_MemberSchedule::where('schedule_id', $scheduleId)
              ->where('user_id', $user->id)
              ->pluck('admin')
              ->first();
      if ($admin === 0 && $user->isRoot() == 0) {
         return view('errors.403')->with('names', $this->names());
      }
      return view('schedule.admin.registerMember', [
          'schedule' => $schedule,
          'currentUser' => Auth::user(),
          'names' => $this->names(),
          'admin' => Utility::getAdminForSchedule($scheduleId),
          'manageMembers' => ($user->isScheduleOwner($scheduleId) || $user->isRoot()),
          'status' => $status
      ]);
   }

   public function showMySchedules() {
      $user = Auth::user();
      $isAdmin = 0;
      if ($user->isRoot()) {
         $isAdmin = 1;
         $myVMemberSchedules = collect([]);
         $schedules = Schedule::all();
         foreach ($schedules as $mySchedule) {
            $mySchedule->schedule_id = $mySchedule->id;
            $mySchedule->default_weekday = $mySchedule->default_weekday;
            $mySchedule->default_start_time = substr($mySchedule->default_start_time, 0, 5);
            $mySchedule->schedule_name = $mySchedule->name;
            $mySchedule->schedule_description = $mySchedule->description;
            $myVMemberSchedules->push($mySchedule);
         }
      } else {
         $myVMemberSchedules = V_MemberSchedule::where('user_id', Auth::id())->get();
//         dd(print_r($myVMemberSchedules, true));
      }
      //$myScheduleIds = $myVMemberSchedules->pluck('schedule_id');

      foreach ($myVMemberSchedules as $myVMemberSchedule) {
         if ($myVMemberSchedule->user_id === $user->id && $myVMemberSchedule->admin > 1) {
            $isAdmin = 1;
         }
         $myVMemberSchedule->admins = V_MemberSchedule::where('schedule_id', $myVMemberSchedule->schedule_id)
                         ->where('admin', 2)
                         ->get()->implode('user_name', ',');
         $myVMemberSchedule->isAdmin = V_MemberSchedule::where('schedule_id', $myVMemberSchedule->schedule_id)
                         ->where('admin', 2)->where('user_id', Auth::id())
                         ->get()->count() + $user->isRoot();
      }
      return view('schedule.mySchedules', [
          'myVMemberSchedules' => $myVMemberSchedules,
          'names' => $this->names(),
          'isAdmin' => $isAdmin
      ]);
   }

   public function updateSchedule(Request $request) {
      $data = request()->all();
      foreach ($data as $key => $value) {
         if (substr($key, 0, 4) === 'name') {
            $atoms = explode('_', $key);
            $scheduleId = $atoms[1];
            $schedule = Schedule::find($scheduleId);
            $schedule->name = $value;
            $description = $data['description_' . $scheduleId];
            $schedule->description = $description;
            $schedule->default_weekday = $data['weekDay_' . $scheduleId];
            ;
            $schedule->default_start_time = $data['time_' . $scheduleId];
            ;
            $schedule->save();
         }
      }
      return $this->showMySchedules();
   }

   //////////////////////////////////////////////////////////////  Comments view
// Show view AdminComments
   public function showViewAdminComments($scheduleId) {
      $user = Auth::user();
      if ($user->isScheduleOwner($scheduleId) || $user->hasLimitedAuthority($scheduleId) || $user->isRoot()) {
         $mytime = Carbon::now();
         $today = $mytime->toDateString();
         $schedule = Schedule::find($scheduleId);
         $scheduleDates = ScheduleDate::where('schedule_id', $scheduleId)
                 ->where('schedule_date', '>=', $today)
                 ->get();
         return view('schedule.admin.comments', [
             'schedule' => $schedule,
             'currentUser' => $user,
             'scheduleDates' => $scheduleDates,
             'names' => $this->names(),
             'isRoot' => $user->isRoot(),
             'manageMembers' => ($user->isScheduleOwner($schedule->id) || $user->isRoot()),
             'isScheduleAdmin' => $user->hasLimitedAuthority($schedule->id) || $user->isScheduleOwner($schedule->id) || $user->isRoot(),
             'isScheduleOwner' => $user->isScheduleOwner($schedule->id)
         ]);
      } else {
         return Redirect::back();
      }
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
      //$schedule = Schedule::find($scheduleId);
//      return redirect(route('schedule.index', ['scheduleId' => $schedule->id]));
      return back()->with('success', __('The update succeeded'));
   }

   public function welcome() {
      $user = Auth::user();
      
      if ($user->isRoot()) {
         $vMemberSchedules = V_MemberSchedule::all()->unique('schedule_id');
      } else {
         $vMemberSchedules = V_MemberSchedule::where('user_id', Auth::user()->id)->get();
      }
      $count = $vMemberSchedules->count();

      return view('schedule.welcome', [
          'mySchedulesCount' => $count,
          'names' => $this->names(),
          'vMemberSchedules' => $vMemberSchedules
      ]);
   }

}
