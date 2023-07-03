<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Config;
use App\Classes\Utility;

class BaseController extends Controller {

   public function names() {
      
      $names= Config::get('names');
      if (is_null($names)) {
         $this->setNames();
      }
      $names= Config::get('names');
      if ($names['application'] === '' || $names['routeRoot'] === '') {
         return $this->setNames();
      } else {
         return $names;
      }
   }

   private function setNames() {
      Utility::Logg('BaseController', 'in setNames');
      $names=[];
      $fullUrl = request()->fullUrl();
      // Set default values
      $application = 'sqd.se';
      $routeRoot = 'sqd';
      if (str_contains($fullUrl, 'schema')) {
         $application = 'SdSchema';
         $routeRoot = 'schedule';
      } else if (str_contains($fullUrl, 'calls')) {
         $application = 'SdCalls';
         $routeRoot = 'calls';
      }
      $names['routeRoot']=$routeRoot;
      $names['application']=$application;
      
      Config::set('names', $names);
      return $names;
   }

}
