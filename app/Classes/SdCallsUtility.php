<?php

namespace App\Classes;

use App\Models\Calls\Definition;
use App\Models\Calls\DefinitionFragment;
use App\Models\Calls\Formation;
use App\Models\Calls\Fragment;
use App\Models\Calls\Program;
use App\Models\Calls\VCallDef;
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

   public static function createCallText(Definition $definition) {
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
      }
      Utility::Logg('CreateTexts->createCallText, txt=', $txt);

      return $txt;
   }

   /**
    * 
    * @param string $fileName file name without path and extension
    * @param string $txt
    */
   public static function createMp3File($fileName, $txt, $voiceParams) {
//      try {
      Utility::Logg('SdCallsUtility->createMp3File', 'Called');
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

      Utility::logg('SdCallsUtility->createMp3File', 'fileName=' . $fileName);
      Storage::disk('public')->put($fileName, $resultData);
      $path = self::createPathName($fileName);
      Utility::logg('SdCallsUtility->createMp3File', 'asset=' . asset($path));
      return asset($path);
   }

   public static function createPathName($fileName) {
      if (env('APP_ENV') == 'local') {
         $cmd = sprintf('cp /home/vagrant/sqd/storage/app/public/%s /home/vagrant/sqd/public/test', $fileName);
         Utility::logg('SdCallsUtility->createPathName', $cmd);
         $result = Process::run($cmd)->throw();
         ;
         Utility::logg('SdCallsUtility->createPathName', $result->output());
         $path = sprintf('test/%s', $fileName);
      } else {
         $path = sprintf('storage/%s', $fileName);
      }
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
      $tag .= $request->repeats;
      $fileName = str_replace(' ', '_', sprintf('%s from %s %s',
                              $definition->sd_call->name, $definition->start_end_formation->startFormation->name, $tag)) . '.mp3';
      Utility::Logg('SdCallsUtility->createPathName, fileName=', $fileName);
//      $path = asset(sprintf('%s.mp3', $fileName));
//      Utility::Logg('SdCallsUtility->createPathName, path not used=', $path);
      return $fileName;
   }

   public static function createSsmlText($definition, $includeStartFormation, $includeEndFormation, $repeats, $includeFormations) {
      $txt = '<speak> ';
      $txtFrom = '';
      $txtEndsIn = '';
      $startEndFormation = $definition->start_end_formation;
      if ($includeStartFormation && !is_null($startEndFormation->startFormation)) {
         $txtFrom = 'from ' . $startEndFormation->startFormation->name;
      }
      if ($includeEndFormation && !is_null($startEndFormation->endFormation)) {
         $txtEndsIn = 'ends in ' . $startEndFormation->endFormation->name;
      }
      $txtCall = self::createCallText($definition);
      for ($n = 0; $n < $repeats + 1; $n++) {
         $txt .= $definition->sd_call->name . '; ';
         if ($includeStartFormation && ($n == 0 || $includeFormations)) {
            $txt .= $txtFrom . ';';
         }
         $txt .= $txtCall . ';';
         if ($includeEndFormation && ($n == 0 || $includeFormations)) {
            $txt .= $txtEndsIn . ';';
         }
         //$txt .=';';
      }
      $txt = $txt . ' </speak>';
      Utility::Logg('CreateTexts->createSsmlText, txt=', $txt);

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
      $calls = SdCall::all();
      return $calls;
   }

   public static function GetFormationList() {
      $list = Formation::orderBy('name')->get()->pluck('name', 'id');
      return $list;
   }

   public static function GetFragmentList() {
      $list = Fragment::all()->pluck('text', 'id')->unique();
      return $list;
   }

   public static function GetLanguageList() {
      $list = ['en-US', 'en-AU', 'en-GB'];
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
