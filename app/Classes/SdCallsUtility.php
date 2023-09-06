<?php

namespace App\Classes;

use App\Models\Calls\Definition;
use App\Models\Calls\DefinitionFragment;
use App\Models\Calls\Formation;
use App\Models\Calls\Fragment;
use App\Models\Calls\Program;
use App\Models\Calls\VCallDef;
use App\Models\Calls\Pause;
use App\Models\Calls\SdCall;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Http\Request;
use App\Classes\Utility;

class SdCallsUtility {

   public static function fixFileName($txt) {
      $x = array(' ', '/', '|', '&');
      $y = array('_', '_', '_', '_');
      return str_replace($x, $y, $txt);
   }

   public static function createCallText(Definition $definition,$ssml= 0) {
      $txt = '';

      $definitionFragments = DefinitionFragment::where('definition_id', $definition->id)
              ->orderBy('seq_no')
              ->get();
      $prevPartNo = 0;
      foreach ($definitionFragments as $definitionFragment) {
         $fragmentId = $definitionFragment->fragment_id;
         $partNo = $definitionFragment->part_no;
         if (!is_null($partNo)) {
            if ($prevPartNo != $partNo) {
               $txt .= ' ' . $partNo . ',';
               $prevPartNo = $partNo;
            }
         }
         $fragment = Fragment::find($fragmentId);
         if ($definitionFragment->fragment_type_id == 2) {
            $txt .= '  (' . $fragment->text . ')';
         } else {
            $txt .= ' ' . $fragment->text;
         }
         if ($ssml > 0) {
             $txt .= self::pauseText(Pause::find($definitionFragment->pause_id)->time);
         } else {
            $txt .= Pause::find($definitionFragment->pause_id)->symbol;
         }
      }
//      Utility::Logg('CreateTexts->createCallText, txt=', $txt);
      if ($ssml==0) {
         $txt .= '.';
      }
      return $txt;
   }

   /**
    * 
    * @param string $fileName file name without path and extension
    * @param string $txt
    */
   public static function createMp3File($fileName, $txt, $voiceParams) {
//      try {
//      Utility::Logg('SdCallsUtility->createMp3File called with text:', $txt);
      $textToSpeechClient = new TextToSpeechClient();

      $input = new SynthesisInput();
      $input->setSsml($txt);

      $voice = new VoiceSelectionParams();
      $voice->setLanguageCode($voiceParams['languageCode']);

      // optional
      $voice->setName($voiceParams['name']);
      //$voice->setName('en-US-Standard-B');
// voice list https://cloud.google.com/text-to-speech/docs/reference/rest/v1/voices/list
      // https://cloud.google.com/text-to-speech/docs/voices   ar-XA-Wavenet-C
      $audioConfig = new AudioConfig();
      $audioConfig->setAudioEncoding(AudioEncoding::MP3);
      $audioConfig->setSpeakingRate($voiceParams['speakingRate']);
// https://cloud.google.com/dotnet/docs/reference/Google.Cloud.TextToSpeech.V1/latest/Google.Cloud.TextToSpeech.V1.AudioConfig
      $resp = $textToSpeechClient->synthesizeSpeech($input, $voice, $audioConfig);

      $resultData = $resp->getAudioContent();

      $textToSpeechClient->close();

// mklink /D ".\public\storage\" "D:\Development\VagrantCode\Calls\storage\app\public\"         

//      Utility::logg('SdCallsUtility->createMp3File', 'fileName=' . $fileName);
      Storage::disk('public')->put($fileName, $resultData);
      $path = self::createPathName($fileName);
//      Utility::logg('SdCallsUtility->createMp3File', 'asset=' . asset($path));
      return asset($path);
   }

   public static function createPathName($fileName) {
      if (env('APP_ENV') == 'local') {
         $cmd = sprintf('cp /home/vagrant/sqd/storage/app/public/%s /home/vagrant/sqd/public/test', $fileName);
//         Utility::logg('SdCallsUtility->createPathName', $cmd);
         $result = Process::run($cmd)->throw();
         ;
         $path = sprintf('test/%s', $fileName);
      } else {
         $path = sprintf('storage/%s', $fileName);
      }
//      Utility::logg('SdCallsUtility->createPathName, path=', $path);

      return $path;
   }

   public static function createFileName($definition, Request $request) {
      $tag = '';
      if ($request->include_start_formation === 'true') {
         $tag .= 's';
      }
      if ($request->include_end_formation === 'true') {
         $tag .= 'e';
      }
      if ($request->include_formations_in_repeats === 'true') {
         $tag .= 'i';
      }
      $voiceId= 1;
      $tag .= '_'.$request->repeats.'_'.$request->speaking_rate.'_'.$voiceId;
      $startFormation= $definition->startFormation->name;
      
      $pos= strpos($startFormation, ',', 1);      
      if ($pos === false) {
      } else {
         $startFormation= substr($startFormation,0,$pos) . ' etc';
      }
      $fileName = str_replace(' ', '_', sprintf('%s %s from %s %s',
                              $definition->program->name, $definition->sd_call->name, $startFormation, $tag)) . '.mp3';
      return $fileName;
   }

   public static function createSsmlText($definition, $includeStartFormation, $includeEndFormation, $repeats, $includeFormations) {
//      $txt = '<speak> ';
      $txt='';
      $txtFrom = '';
      $txtEndsIn = '';
      if ($includeStartFormation && !is_null($definition->startFormation)) {
         $txtFrom = 'from ' . $definition->startFormation->name;
      }
      if ($includeEndFormation && !is_null($definition->endFormation)) {
         $pos1 = strpos($definition->endFormation->name, "end in");
         $pos2 = strpos($definition->endFormation->name, "ends in");
         if ($pos1===false && $pos2===false) {
            $txtEndsIn = 'ends in ' . $definition->endFormation->name;
         } else {
            $txtEndsIn = $definition->endFormation->name;
         }
      }
      $txtCall = self::createCallText($definition, 1);
      for ($n = 0; $n < $repeats + 1; $n++) {
        $pause= self::pauseText(Pause::where('name','Medium')->first()->time);
         $txt .= sprintf('%s%s',$definition->sd_call->name , $pause);
         if ($includeStartFormation && ($n == 0 || $includeFormations)) {
              $txt .= sprintf('%s%s',$txtFrom , $pause);

         }
         $txt .= sprintf('%s%s',$txtCall , $pause);
         if ($includeEndFormation && ($n == 0 || $includeFormations)) {
            $txt .= sprintf('%s%s',$txtEndsIn , $pause);
         }
      }
      Utility::Logg('CreateTexts->createSsmlText, txt=', $txt);
      $txt = '<speak> '. self::fixText($txt). ' </speak>';
      Utility::Logg('CreateTexts->createSsmlText, txt=', $txt);
      return $txt;
   }

public static function fixText($txt)
        {

            $txt = str_replace("  ", " ",$txt);
            $txt = str_replace(" 1/4 In (turn 1/4 in", " (turn 1/4 in",$txt);
            $txt = str_replace(" in ", " inn ",$txt);
            $txt = str_replace(" In ", " inn ",$txt);
            $txt = str_replace(" inside ", " inn-side ",$txt);
            $txt = str_replace("&", "and",$txt);
            $txt = str_replace("(s)", "s",$txt);
            $txt = str_replace("|", " or ",$txt);
            $txt = str_replace("1/4", "1 quarter",$txt);
            $txt = str_replace("3/4", "3 quarters",$txt);
            $txt = str_replace("inward", "inn-ward",$txt);
            $txt = str_replace("L - H", "Left-Hand",$txt);
            $txt = str_replace("L-H", "Left-Hand",$txt);
            $txt = str_replace("Promenade", "Promenaid",$txt);
            $txt = str_replace("R - H", "Right-Hand",$txt);
            $txt = str_replace("R-H", "Right-Hand",$txt);
            $txt = str_replace("Thar", "Dhar",$txt);
            $txt = str_replace("\"\"Z\"\"", ",C,",$txt);
            //$txt = addPauses($txt);


            return $txt;
        }



   private static function pauseText($pause) {
       $txt= sprintf('<break time="%dms"/>', $pause);
       return $txt;
   }
   
   public static function GetCalls($programId) {
      if ($programId == 0) {
         $vCallDefs = VCallDef::all();
      } else {
         $vCallDefs = VCallDef::where('program_id', $programId)->get();
      }
      return $vCallDefs->unique('definition_id');
   }

   public static function GetCallNames() {
//      $list = SdCall::all()->orderBy('name')->pluck('name', 'id');
      $list = SdCall::orderBy('name')->get()->pluck('name','id' );
      return $list;
   }

   public static function GetFormationList() {
      $list = Formation::orderBy('name')->get()->pluck('name', 'id');
      return $list;
   }

   public static function GetFragmentList() {
     // $list = Fragment::all()->pluck('text', 'id')->unique();
      $list = Fragment::orderBy('text')->get()->pluck('text','id' );
      return $list;
   }

   public static function GetLanguageList() {
      $list = ['en-US', 'en-AU', 'en-GB'];
      return $list;
   }

   public static function GetPausesNames() {
      $list = Pause::orderBy('time')->get()->pluck('name','id' );
      return $list;
   }

   public static function GetProgramList() {
      $list = Program::all()->pluck('name', 'id')->unique();
      $list[0] = 'All';
      return $list;
   }

   /*
    * @param string $languageCode
    * @param int    $gender 1= male, 2=female
    * @param string $type (Standard, News,Wavenet, Neural2, Studio)
    * @return array with voice names
    */

   public static function GetVoiceList($languageCode, $gender, $type) {
      $filteredVoices = [];
      $client = new TextToSpeechClient();

      // Fetch the available voices
      $voices1 = $client->listVoices(['languageCode' => $languageCode])->getVoices();
      $voices = \iterator_to_array($voices1);

      foreach ($voices as $voice) {
         if ($voice->getSsmlGender() == $gender) {
            if (str_contains($voice->getName(), $type)) {
               if (str_contains($voice->getName(), $languageCode)) {
                  array_push($filteredVoices, $voice->getName());
               }
            }
         }
      }
      $client->close();
      return $filteredVoices;
   }

   public static function GetVoiceTypeList() {
      return ['Standard', 'News', 'Wavenet', 'Neural2', 'Studio'];
   }

}
