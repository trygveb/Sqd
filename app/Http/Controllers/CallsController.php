<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Models\User;
use App\Models\Definition;
use App\Classes\SdCallsUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CallsController extends BaseController {
   
   public function __construct() {
      $this->middleware('auth');
   }
   public function index() {
      if (Auth::check()) {
         $user = User::find(auth()->id());
         $program_id=$user->program_id;
      } else {

      }
      $languageList = SdCallsUtility::GetLanguageList();
      $voiceTypeList = SdCallsUtility::GetVoiceTypeList();
      $programList = SdCallsUtility::GetProgramList();
      $maxRepeats = 25;
      $calls = SdCallsUtility::GetCalls($program_id);
      $names=$this->names();
      return view('calls.form1', compact('user', 'names', 'languageList', 'voiceTypeList', 'programList', 'calls', 'maxRepeats'));
   }
}