<?php

namespace App\Http\Controllers;

use App\Classes\Utility;

class BaseController extends Controller {

   public function names() {
      $names = session('names');
      //$names= Config::get('names');

      if (is_null($names)) {
         Utility::Logg('BaseController->names()', '$names is null');
         $this->setNames();
      }
      $names = session('names');
//      Utility::Logg('BaseController->names()', sprintf('names=%s', print_r($names, true)));
      if ($names['application'] === '' || $names['routeRoot'] === '') {
         return $this->setNames();
      } else {
         return $names;
      }
   }

   public function setNames($routeRoot = '') {


      $names = [];
      if ($routeRoot === '') {
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
      } else if ($routeRoot==='schedule') {
            $application = 'SdSchema';
      } else if ($routeRoot==='calls') {
            $application = 'SdCalls';
      }
      $names['routeRoot'] = $routeRoot;
      $names['application'] = $application;
      // Config::set('names', $names);
      session(['names' => $names]);
      Utility::Logg('BaseController->setNames()', sprintf('names=%s', print_r($names, true)));
      return $names;
   }

}
