<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Models\User;
use App\Classes\Utility;
use App\Models\Calls\FragmentType;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider {

   /**
    * Register any application services.
    *
    * @return void
    */
   public function register() {
      //
   }

   /**
    * Bootstrap any application services.
    *
    * @return void
    */
   public function boot() {
      Utility::startLogg();
      $fragmentType= FragmentType::where('name','Paranthesis')->get()->first();
      View::share('fragmentTypeParanthesisId', $fragmentType->id);
      Blade::if('isAdmin', function (User $user) {
         return ($user->authority > 0);
      });
   }

}
