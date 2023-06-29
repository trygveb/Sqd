<?php

namespace App\Http\Middleware;

use Closure;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class CustomEnsureEmailIsVerified {

   /**
    * Handle an incoming request. 
    * Add the application name in the url to the return route if user email is not verified
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
    * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    */
   public function handle($request, Closure $next, $redirectToRoute = null) {
      
      
      if (!$request->user() ||
              ($request->user() instanceof MustVerifyEmail &&
              !$request->user()->hasVerifiedEmail())) {
         return $request->expectsJson() ? abort(403, 'Your email address is not verified.') :
            Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
      }

      return $next($request);
   }

}
