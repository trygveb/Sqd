<?php

namespace App\Models;

use App\Models\Schedule\MemberSchedule;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordInterface;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmailContract, CanResetPasswordInterface {

   use HasApiTokens,
       HasFactory,
       Notifiable,
       CanResetPassword,
       MustVerifyEmail;

   /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $fillable = [
       'first_name',
       'middle_name',
       'family_name',
       'email',
       'password',
       'email_verified_at',
   ];

   /**
    * The attributes that should be hidden for serialization.
    *
    * @var array<int, string>
    */
   protected $hidden = [
       'password',
       'remember_token',
   ];

   /**
    * The attributes that should be cast.
    *
    * @var array<string, string>
    */
   protected $casts = [
       'email_verified_at' => 'datetime',
   ];

   // Override the following functions in trait MustVerifyEmail
   public function markEmailAsVerified() {
      $this->email_verified_at = now();
      $this->save();
   }

   public function hasVerifiedEmail() {
      //dd('hasVerifiedEmail');
      return !is_null($this->email_verified_at);
   }

   public function mySchedules() {
      if ($this->isRoot()) {
         $memberSchedules = MemberSchedule::all()->unique('schedule_id');
      } else {
         $memberSchedules = MemberSchedule::where('user_id', $this->id)->get();
      }
      return $memberSchedules;
   }

   public function isScheduleAdministrator() {
      return ($this->authority >= 1);
   }

   public function isScheduleOwner($scheduleId) {
      $memberSchedule = MemberSchedule::where('user_id', $this->id)
                      ->where('schedule_id', $scheduleId)->first();
      if (is_null($memberSchedule)) {
         return false;
      } else {
         return $memberSchedule->admin == 2;
      }
   }

   public function hasLimitedAuthority($scheduleId) {
      $memberSchedule = MemberSchedule::where('user_id', $this->id)
                      ->where('schedule_id', $scheduleId)->first();
      if (is_null($memberSchedule)) {
         return false;
      } else {
         return $memberSchedule->admin == 1;
      }
   }

   public function isRoot() {
      return ($this->authority == 2);
   }

}
