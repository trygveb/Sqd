<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
//use Illuminate\Support\Facades\Gate;
//use App\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
//use Illuminate\Support\Facades\URL;
//use Illuminate\Support\Carbon;
//use Illuminate\Support\Facades\Config;
use Illuminate\Support\HtmlString;

class AuthServiceProvider extends ServiceProvider {

   /**
    * The policy mappings for the application.
    *
    * @var array<class-string, class-string>
    */
   protected $policies = [
           // 'App\Models\Model' => 'App\Policies\ModelPolicy',
   ];
   private function createSalutation() {
      return sprintf('%s<br>%s, %s %s',__('Regards') ,config('app.mailFromName'),__('administrator on'), config('app.name'));
   }
   /**
    * Register any authentication / authorization services.
    *
    * @return void
    */
   public function boot() {
      $this->registerPolicies();
      
      VerifyEmail::toMailUsing(function ($notifiable, $url) {
         
         return (new MailMessage)
                 ->subject(Lang::get('Verify email address') . ', ' . config('app.name'))
                 ->line(Lang::get('Please click the button below to verify your email address.'))
                 ->line(Lang::get('When you do that, a pop-up window will appear with a status message.'))
                 ->line(Lang::get('After reading the status message, you may close the pop-up window.'))
                 ->action(Lang::get('Verify email address'), $url)
                 ->salutation(new HtmlString($this->createSalutation()))
                 ->line(Lang::get('If you did not create an account, no further action is required.'));
      });
      ResetPassword::toMailUsing(function ($notifiable, $token) {
          $fullUrl = request()->fullUrl();
          $url= str_replace('forgot-password','reset-password/'.$token, $fullUrl);
//         dd(sprintf('fullUrl=%s, url=%s',$fullUrl,$url));
         //$url= config('app.frontend_url').'/reset-password/'.$url;
         return (new MailMessage)
                 ->subject(Lang::get('Reset Password Notification for') . ' ' . config('app.name'))
                 ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
                 ->action(Lang::get('Reset Password'), $url)
                 ->salutation(new HtmlString($this->createSalutation()))
                 ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
                 ->line(Lang::get('If you did not request a password reset, no further action is required.'));
      });

//      // Adds current application (schema or call) to the url used in the verify email email,
//      //  in order to return to the current application
//      VerifyEmail::createUrlUsing(function ($user) {
////         dd('VerifyEmail::createUrlUsing user= ' . $user);
////         if (is_null($user->application)) {
////            $user->application = 'schema';
////         }
//         return URL::temporarySignedRoute(
//                 'verification.verify',
//                 Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
//                 [
//                     'id' => $user->getKey(),
//                     'hash' => sha1($user->getEmailForVerification()),
//                     //'application' => $user->application
//                     'application' => 'schema'
//                 ]
//         );
//      });

   }
}
