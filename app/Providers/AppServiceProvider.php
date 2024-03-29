<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Models\User;
use App\Classes\Utility;

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
      Blade::if('isAdmin', function (User $user) {
         return ($user->authority > 0);
      });
   }

}
