<?php

namespace App\Http\Controllers;

use App\Classes\Utility;
use App\Models\User;
use App\Models\Calls\Definition;
use App\Models\Calls\DefinitionFragment;
use App\Models\Calls\Formation;
use App\Models\Calls\SdCall;
use App\Models\Calls\StartEndFormation;
use App\Classes\SdCallsUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
      $fileName = $request->path;
      Utility::Logg('CallsController->createMp3File', 'fileName=' . $fileName);

      $path = SdCallsUtility::createMp3File($fileName, $callText, $voiceParams);

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


   private function getDefinitionFragment($request, $definitionId, $seqNo) {
      $selectName = sprintf('fragment_id_%d', $seqNo);
      $fragmentId = $request->$selectName;
      $checkbox1Name = sprintf('checkbox1_id_%d', $seqNo);
      $secondary = $request->$checkbox1Name;
      $definitionFragment = new DefinitionFragment();
      $definitionFragment->fragment_type_id = 1;
      $definitionFragment->seq_no = 0;   // Do not save $definitionFragment with seq_no=0
      if ($fragmentId > 0) {
         $definitionFragment->definition_id = $definitionId;
         $definitionFragment->fragment_id = $fragmentId;
         $definitionFragment->seq_no = $seqNo;
//               Utility::Logg('CallsController', 'method saveCall,insert DefinitionFragment: '.print_r($definitionFragment, true));
      }
      if ($secondary == 'on') {
         $definitionFragment->fragment_type_id = 2;
      }
      return $definitionFragment;
   }

   private function getStartEndFormation($startFormationId, $endFormationId) {
      // Try to find existing StartEndFormation
      // if not found, create a new

      StartEndFormation::where('start_formation_id', $startFormationId)
              ->where('end_formation_id', $endFormationId)
              ->firstOr(function () use ($startFormationId, $endFormationId) {
                 $startEndFormation = new StartEndFormation();
                 $startEndFormation->start_formation_id = $startFormationId;
                 $startEndFormation->end_formation_id = $endFormationId;
                 $startEndFormation->save();
              });
      $startEndFormation = StartEndFormation::where('start_formation_id', $startFormationId)
              ->where('end_formation_id', $endFormationId)
              ->first();
      return $startEndFormation;
   }
   public function saveCall(Request $request) {
//      dd('saveCall');
      $callName = $request->call_name_1;
      $callId = $request->call_id_1;
      if ($callId > 0) {
         $definitionId = $request->definition_id;  // Edit
      } else {
         $definitionId = 0;  //new call defintion
      }
      Utility::Logg('CallsController', 'method saveCall call id='.$callId.', call name=' . $callName);
      $programId = $request->program_id;
//      Utility::Logg('CallsController', 'method saveCall program id='.$programId);
      $startFormationId = $request->start_formation_id;
      Utility::Logg('CallsController', 'method saveCall startFormation id='.$startFormationId);
      $endFormationId = $request->end_formation_id;
      Utility::Logg('CallsController', 'method saveCall endFormation id='.$endFormationId);
      DB::beginTransaction();
      try {
         if ($callId > 0) {
            $sdCall = SdCall::find($callId);
            $sdCall->name = $callName;
            $sdCall->save();
         } else {
            $sdCall = SdCall::firstOrCreate([
                        'name' => $callName
            ]);
         }
         if ($definitionId > 0) {
            $definition = Definition::find($definitionId);
         } else {
            $definition= new Definition();
         }
         $definition->program_id = $programId;
         $definition->call_id = $sdCall->id;
         $startEndFormation = $this->getStartEndFormation($startFormationId, $endFormationId);
         $definition->start_end_formation_id = $startEndFormation->id;
         $definition->save();
         $fragments = $definition->fragments;
//          Utility::Logg('CallsController', 'method saveCall, fragments count='.$fragments->count());
         foreach ($fragments as $fragment) {
            DefinitionFragment::destroy($fragment->pivot->id);
         }
         for ($seqNo = 1; $seqNo <= 6; $seqNo++) {
            $definitionFragment = $this->getDefinitionFragment($request, $definitionId, $seqNo);
            if ($definitionFragment->seq_no > 0) {
//               Utility::Logg('CallsController', 'method saveCall, save definitionFragment '.$seqNo);
               $definitionFragment->save();
            }
         }
      } catch (\Illuminate\Database\QueryException $ex) {
         DB::rollback();
         Utility::Logg('CallsController', 'Database error=' . $ex->getMessage());
         return redirect()->back()->with('error', 'A database error occurred, please contact support.');
      }
      DB::commit();
      return redirect()->back()->with('success', 'Call  saved successfully.');
   }
   public function saveFormation1(Request $request) {
      dd('Här');
      $returnHTML = view('calls.test')->render();
      return response()->json(array(
                  'success' => true,
                  'html' => $returnHTML
      ));      
   }
   
   public function saveFormation(Request $request) {
      $formationName= $request->formation_name;
      $formationId= $request->formation_id;
      $formation= Formation::find($formationId);
      $formation->name=$formationName;
      Utility::Logg('CallsController', 'saveFormation called, formationName='.$formationName.', formationId='.$formationId);
//      $names = $this->names();
//      $user = User::find(auth()->id());
      DB::beginTransaction();
      try {
         $formation->save();
      } catch (\Illuminate\Database\QueryException $ex) {
         DB::rollback();
         Utility::Logg('CallsController', 'Database error=' . $ex->getMessage());
         return redirect()->back()->with('error', 'A database error occurred, please contact support.');
      }
      DB::commit();
      Utility::Logg('CallsController', 'saveFormation  formationName='.$formationName.', formationId='.$formationId);
      return redirect()->back()->with('success', 'Formation name saved.');
//       $returnHTML = view('calls.editFormation',
//              compact('mode', 'user', 'names',  'formationName', 'formationId'))->render();
//      Utility::Logg('CallsController', 'saveFormation, returnHTML created');
//      return response()->json(array(
//                  'success' => true,
//                  'html' => $returnHTML
//      ));
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
//      Utility::Logg('CallsController', 'method showForm1 called');
      if (Auth::check()) {
         $user = User::find(auth()->id());
         $program_id = $user->program_id;
      } else {
         
      }
      $languageList = SdCallsUtility::GetLanguageList();
      $voiceTypeList = SdCallsUtility::GetVoiceTypeList();
      $programList = SdCallsUtility::GetProgramList();
      $maxRepeats = 25;
      $vCallDefs = SdCallsUtility::GetCalls($program_id);
      $names = $this->names();
      return view('calls.form1', compact('user', 'names', 'languageList', 'voiceTypeList', 'programList', 'vCallDefs', 'maxRepeats'));
   }

   /**
    * 
    * @param type $definitionId
    * @return type
    */
   public function showEditDefinition($definitionId) {
      if (Auth::check()) {
         $user = User::find(auth()->id());
      }
      $definition = Definition::find($definitionId);
      $startEndFormation = StartEndFormation::find($definition->start_end_formation_id);
      $definitionFragments = DefinitionFragment::where('definition_id', $definitionId)
              ->get()
              ->sortBy('seq_no')
              ->values()
              ->toArray();
//      Utility::Logg('CallsController', 'definitionFragments fetched, definitionFragments=' . print_r($definitionFragments, true));
      $formationList = SdCallsUtility::GetFormationList();
      $names = $this->names();
      $fragmentList = SdCallsUtility::GetFragmentList();
      $programList = SdCallsUtility::GetProgramList();
      //$calls = SdCallsUtility::GetCallNames();
      $callId = $definition->call_id;
      $callName = SdCall::find($callId)->name;
      $programId=$definition->program_id;
      $startFormationId= $startEndFormation->start_formation_id;
      $endFormationId=$startEndFormation->end_formation_id;
      $mode = 'edit';
      //$fragments = json_encode($definitionFragments);
      return view('calls.editCall',
              compact('mode', 'definition', 'user', 'names', 'callName', 'callId', 'programId', 'startFormationId', 'endFormationId', 'definitionFragments', 'programList', 'formationList', 'fragmentList'));
//      return response()->json(array(
//                  'success' => true,
//                  'html' => $returnHTML,
//                  'call_id' => $callId,
//                  'program_id' => $definition->program_id,
//                  'start_formation_id' => $startEndFormation->start_formation_id,
//                  'end_formation_id' => $startEndFormation->end_formation_id,
//                  'fragments' => json_encode($definitionFragments)
//      ));
   }
   
   public function showEditFormation($formationId, $definitionId) {
      if (Auth::check()) {
         $user = User::find(auth()->id());
      }
      $formation= Formation::find($formationId);
      $formationName=$formation->name;
//      $formationList = SdCallsUtility::GetFormationList();
      $names = $this->names();
      $mode = 'edit';
//      Utility::Logg('CallsController', 'method showEditFormation, formationId=' . $formationId . ', mode='.$mode);
      return view('calls.editFormation',
              compact('mode', 'user', 'names','formationId', 'formationName', 'definitionId'));
   }

   public function showNewCall() {
      Utility::Logg('CallsController', 'method showNewCall called');
      if (Auth::check()) {
         $user = User::find(auth()->id());
      }
      $formationList = SdCallsUtility::GetFormationList();
      $names = $this->names();
      $fragmentList = SdCallsUtility::GetFragmentList();
      $programList = SdCallsUtility::GetProgramList();

      $mode = 'new';
//      Utility::Logg('CallsController', 'method showNewCall called, creating returnHTML');
      $returnHTML = view('calls.editCall',
              compact('mode', 'user', 'names', 'programList', 'formationList', 'fragmentList'))->render();
//      Utility::Logg('CallsController', 'method showNewCall called, returnHTML created');
      return response()->json(array(
                  'success' => true,
                  'html' => $returnHTML
      ));
   }
   public function showNewFormation() {
      Utility::Logg('CallsController', 'method showNewFormation called');
      dd('CallsController', 'method showNewFormation called');
   }
}
