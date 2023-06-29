<?php

namespace App\Http\Controllers;

class BaseController extends Controller {
   
   
  public function names() {
      $fullUrl = request()->fullUrl();
      // Set default values
      $application='sqd.se';
      $routeRoot='sqd';
      if (str_contains($fullUrl, 'schema')) {
         $application='SdSchema';
         $routeRoot='schedule';
      } else if (str_contains($fullUrl, 'calls')) {
         $application='SdCalls';
            $routeRoot='calls';
      }
      return ['application' => $application,
              'routeRoot' => $routeRoot];
   }
   
}