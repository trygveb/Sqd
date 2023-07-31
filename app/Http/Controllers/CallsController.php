<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Models\User;
use App\Models\Calls\Definition;

use App\Classes\SdCallsUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CallsController extends BaseController {
   
   public function __construct() {
      $this->middleware('auth');
   }
   

   public function getVoiceList(Request $request) {
      $voiceNames = SdCallsUtility::GetVoiceList($request->language, $request->voice_gender, $request->voice_type);
      return response()->json($voiceNames, 200);
   }

   public function getCallList(Request $request) {
      $callList = SdCallsUtility::GetCalls($request->program_id)->toArray();
//      Utility::Logg('getCallList:'.$request->program_id, print_r($callList, true));
      return response()->json($callList, 200);
   }

   public function createMp3File(Request $request) {
      Utility::Logg('CallsController->createMp3File', 'Called');
      $callText = $request->callText;
     //$path = $request->path;
      $voiceParams = [
          "languageCode" => $request->language,
          "name" => $request->voice_name,
          "speakingRate" => $request->speaking_rate
      ];
     // Utility::Logg('CallsController->createMp3File', print_r($voiceParams, true));
      $fileName=$request->path;
      Utility::Logg('CallsController->createMp3File','fileName='.$fileName);
      
      $path=SdCallsUtility::createMp3File($fileName, $callText, $voiceParams);
     
      return response()->json(array('path' => asset($path)), 200);
   }

   public function getCallText(Request $request) {
      $definitionId = $request->definition_id;
      $definition = Definition::find($definitionId);
      $path = '';
      Utility::Logg('CallsController->getCallText ssml=', $request->ssml);
      Utility::Logg('CallsController->getCallText repeats=', $request->repeats);
      if ($request->ssml === 'true') {
         $callText = SdCallsUtility::createSsmlText($definition,
                         $request->include_start_formation === 'true',
                         $request->include_end_formation === 'true',
                         intval($request->repeats),
                         $request->include_formations_in_repeats === 'true'
         );
         $path = SdCallsUtility::createFileName($definition, $request);
      } else {
         $callText = SdCallsUtility::createCallText($definition);
      }

      $this->saveUser($request);
      $from = '';
      $endsIn = '';
      if (!is_null($definition->start_end_formation)) {
         $startEndFormation = $definition->start_end_formation;
         if (!is_null($startEndFormation->startFormation)) {
            $from = $definition->start_end_formation->startFormation->name;
         }
         if (!is_null($startEndFormation->endFormation)) {
            $endsIn = $definition->start_end_formation->endFormation->name;
         }
      }
//      $createMp3FileAction= new CreateMp3FileAction();
//      $createMp3FileAction->execute($definitionId, $callText[1]);
      return response()->json(array('callText' => $callText, 'from' => $from, 'endsIn' => $endsIn, 'path' => $path), 200);
   }
   public function saveCall(Request $request) {
      $call_name = $request->call_name;
      Utility::Logg('CallsController', 'method saveNewCallName called, call name='.$call_name);
      return redirect()->back()->with('success', 'New Call  name saved successfully.');
   }
   public function saveUser(Request $request) {
      $user = User::find(auth()->id());
      $user->language = $request->input('language');
      $user->voice_type = $request->input('voice_type');
      $user->voice_gender = $request->input('voice_gender');
      $user->voice_pitch = floatval($request->input('voice_pitch'));
      $user->speaking_rate = floatval($request->input('speaking_rate'));
      $user->volume_gain_db = floatval($request->input('volume_gain'));
      $user->voice_name = $request->input('voice_name');
      $user->program_id = $request->input('program_id');
      $user->include_start_formation = $request->input('include_start_formation') == 'true' ? 1 : 0;
      $user->include_end_formation = $request->input('include_end_formation') == 'true' ? 1 : 0;
      $user->definition_id = $request->input('definition_id');
      $user->repeats = floatval($request->input('repeats'));
      $user->include_formations_in_repeats = $request->input('include_formations_in_repeats') == 'true' ? 1 : 0;
      try {
         $user->save();
      } catch (Exception $e) {
         Utility::Logg('saveUser Exception=', $e->getMessage());
      }

      return redirect()->back()->with('success', 'Settings saved successfully.');
   }

   public function showForm1() {
      Utility::Logg('CallsController', 'method showForm1 called');
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
//       Utility::Logg("CallsController:index, calls", print_r($calls, true));
//       Utility::Logg("CallsController:index, languageList", print_r($languageList, true));
//       Utility::Logg("CallsController:index, voiceTypeList", print_r($voiceTypeList, true));
//       Utility::Logg("CallsController:index, programList", print_r($programList, true));
      $names=$this->names();
      return view('calls.form1', compact('user', 'names', 'languageList', 'voiceTypeList', 'programList', 'calls', 'maxRepeats'));
   }
   public function showEditCall($definitionId) {
      Utility::Logg('CallsController', 'method showEditCall called, id='.$definitionId);
      if (Auth::check()) {
         $user = User::find(auth()->id());
      }
      $formationList= SdCallsUtility::GetFormationList();
      $names=$this->names();
      $fragmentList = SdCallsUtility::GetFragmentList();
      $programList = SdCallsUtility::GetProgramList();
      $calls=SdCallsUtility::GetCallNames();
      $returnHTML = view('calls.editCall_1', compact('user', 'names', 'calls', 'programList', 'formationList', 'fragmentList'))->render();
      Utility::Logg('CallsController', 'returnHTML='.$returnHTML);
      //return view($returnHTML);
      return response()->json(array('success' => true, 'html'=>$returnHTML));      
      //return view('calls.editCall',compact('user', 'names', 'calls', 'programList', 'formationList', 'fragmentList'));
   }
   
   public function showNewCall() {
      Utility::Logg('CallsController', 'method showNewCall called');
      if (Auth::check()) {
         $user = User::find(auth()->id());
      }
      $formationList= SdCallsUtility::GetFormationList();
      $names=$this->names();
      $fragmentList = SdCallsUtility::GetFragmentList();
      $programList = SdCallsUtility::GetProgramList();
      $calls=SdCallsUtility::GetCallNames();
      return view('calls.newCall',compact('user', 'names', 'calls', 'programList', 'formationList', 'fragmentList'));
   }
}