<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Models\User;
use App\Models\Definition;
use App\Classes\SdCallsUtility;
use Illuminate\Http\Request;



class CallsController extends Controller {

   public function index() {
      $user = User::find(auth()->id());
      $languageList = SdCallsUtility::GetLanguageList();
      $voiceTypeList = SdCallsUtility::GetVoiceTypeList();
      $programList = SdCallsUtility::GetProgramList();
      $maxRepeats = 25;
      $calls = SdCallsUtility::GetCalls($user->program_id);
      return view('sdCalls.form1', compact('user', 'languageList', 'voiceTypeList', 'programList', 'calls', 'maxRepeats'));
   }
}