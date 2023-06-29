<?php

namespace App\Classes;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule\V_MemberSchedule;

class Utility {
   /**
    * 
    * @param type $scheduleId
    * @return int =1 if user is ScheduleAdmin for the given schedule, else zero.
    */
    public static function getAdminForSchedule($scheduleId) {
        $adminForSchedule=0;
        if (Auth::check()) {
            $adminForSchedule = V_MemberSchedule::where('schedule_id', $scheduleId)
                ->where('user_id', Auth::user()->id)
                ->pluck('admin')
                ->first();
        }
        return $adminForSchedule;
     }
   private static function logfile() {
      return "x.log";
   }
   
   public static function startLogg() {
      // Truncate log file
        Storage::put(self::logFile(), '');
        self::Logg('startLogg', 'start logging');
        return true;
   }

   private static function logLevel() {
      return 1;
   }

 



   public static function Logg($caller, $message, $print = false) {
      $timeStamp = Utility::LoggTimeFormat();
      if ($print) {
         print(sprintf("%s %s: %s<br>\n", $timeStamp, $caller, str_replace("\n", "<br>\n", $message)));
      }
      if (self::logLevel() > 0) {
         Storage::append(self::logFile(), sprintf("%s %s: %s\n", $timeStamp, $caller, $message));
         //$myfile = fopen(self::logFile(), 'a');
//         fwrite($myfile, sprintf("%s %s: %s\n", $timeStamp, $caller, $message));
//         fclose($myfile);
      }
   }

   public static function LoggBatch($caller, $message, $jobId = 0) {
      $timeStamp = Utility::LoggTimeFormat();
      $myfile = fopen(Utility::$DataFilesFolder . "/batch.log", 'a');
      fwrite($myfile, sprintf("%s %s: %s\n", $timeStamp, $caller, $message));
      fclose($myfile);
   }

   public static function ErrorLogFile() {
      global $logFile;
      $errorLogFile = substr($logFile, 0, -4) . "Error.log";
      return $errorLogFile;
   }

   public static function WarningLogFile() {
      global $logFile;
      $errorLogFile = substr($logFile, 0, -4) . "Warning.log";
      return $errorLogFile;
   }

   public static function LoggError($caller, $message, $errorNumber) {
      global $logFile;
      print(sprintf("%s: %d: %s<br>\n", $caller, $errorNumber, $message));
      $myfile = fopen(Utility::ErrorLogFile(), 'a');
      fwrite($myfile, sprintf("%s: %s: error %d: %s\n", Utility::LoggTimeFormat(), $caller, $errorNumber, $message));
      fclose($myfile);
      mail('trygve.botnen@gmail.com', 'Job finished with error', sprintf("%s: %s", $caller, $message));
      exit($errorNumber);
   }

   public static function LoggTimeFormat() {
      return Utility::TimeFormat(new \DateTime('NOW'));
   }

   public static function TimeFormat($objDateTime) {
      $timeString1 = $objDateTime->format('c');  // 2018-01-23T08:20:17+01:00 
      $timeString2 = substr($timeString1, 0, -6); // 2018-01-23T08:20:17 
      $timeString3 = str_replace("T", " ", $timeString2);
      return $timeString3;
   }

   public static function LoggWarning($caller, $message, $errorNumber = 0) {
      global $logFile;
      print(sprintf("%s: %d: %s<br>\n", $caller, $errorNumber, $message));
      $myfile = fopen(Utility::WarningLogFile(), 'a');
      fwrite($myfile, sprintf("%s: %d: %s %s\n", Utility::LoggTimeFormat(), $errorNumber, $caller, $message));
      fclose($myfile);
   }

   public static function Sleep($seconds, $print = false) {
      Utility::Logg(__METHOD__, "Will now sleep for " . $seconds . " seconds.", $print);
      sleep($seconds);
   }

   public static function Sleep1($caller, $seconds, $print = false) {
      Utility::Logg(__METHOD__, sprintf("%s: Will now sleep for %d seconds", $caller, $seconds), $print);
      sleep($seconds);
   }

   public static function StopExecution($caller) {
      global $logFile;
      $txt = sprintf("%s: Execution stopped<br>\n", $caller);
      print($txt);
      $myfile = fopen($logFile, 'a');
      fwrite($myfile, $txt);
      fclose($myfile);
      exit(0);
   }

   public static function TruncateFile($path) {
      $fp = fopen($path, "w");
      fclose($fp);
   }

   public static function SearchRecursive($projectPath, $fileName) {
      $result = array();

      $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($projectPath), RecursiveIteratorIterator::SELF_FIRST);
      foreach ($objects as $name => $object) {
         if (stripos($name, $fileName)) {
            array_push($result, $name);
         }
      }

      return $result;
   }

  
}
