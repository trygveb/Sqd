<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\Schedule\Schedule;
use App\Models\User;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Password;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class CustomAuthController extends BaseController {

   public function create(array $data) {
      return User::create([
                  'first_name' => $data['first_name'],
                  'middle_name' => $data['middle_name'],
                  'family_name' => $data['family_name'],
                  'email' => $data['email'],
                  'password' => Hash::make($data['password'])
      ]);
   }

   public function customLogin(Request $request) {

      $request->validate([
          'email' => 'required',
          'password' => ['required',
//              config('app.passwordMinLength'),  // This sould only be checked at registration or at password reset
//              config('app.passwordRegex')
          ],
      ]);

      $remember= $request->remember;
      $credentials = $request->only('email', 'password');
      if (Auth::attempt($credentials, $remember)) {
         if (!Auth::user()->hasVerifiedEmail()) {
            // Auth::logout();
            return redirect()
                            ->route('verification.notice')
                            ->with('danger', __('Please confirm your email before logging in.'));
         }
         
         //return redirect()->intended('/home')->withSuccess('Signed in');
         return redirect(route($this->names()['routeRoot'].'.home'));
      }

     return redirect(route('showLoginForm'))->withErrors(['email' => [__('Sorry, login details are not valid')]]);
   }

   /**
    * Handle the registration request
    * Registration is normally done by the user, and will trigger a confirmation email to the user
    * If the parameter "isAdmin" in the request array is =1, this email will not be sent, and 
    * the user's email adress (in the request array) is supposed to be correct (and verified)
    * @param Request $request
    * @return type
    */
   public function handleRegistration(Request $request) {
      $data = $request->all();
      $request->validate([
          'complete_name' => 'unique:users',
          'family_name' => 'required',
          'first_name' => 'required',
          'email' => 'required|email|unique:users',
          'password' => ['required',
              config('app.passwordMinLength'),
              config('app.passwordRegex'),
              'same:password_confirmation'
          ],
         'password_confirmation' => 'required',
      ]);
     // dd(print_r($data, true));

      $user = $this->create($data);
      App::setLocale(LaravelLocalization::getCurrentLocale());
      if ($data['isAdmin']==0) {
         event(new Registered($user));
         Auth::login($user);
         return redirect(route('verification.notice'));
      } else {
         $user->email_verified_at= now();
         $user->save();
         return back()->with('success',__('Member :name has been registered!',['name' => $data['complete_name']]));
      }
   }

   public function handleEmailVerification(EmailVerificationRequest $request) {
      $request->fulfill();

      return back()->with('status', 'EmailVerification_OK'); //redirect(route('home'));
   }

   public function handleThePasswordResetFormSubmission(Request $request) {

      $request->validate([
          'token' => 'required',
          'email' => 'required|email',
          'password' => ['required',
              config('app.passwordMinLength'),
              config('app.passwordRegex')
      ]]);

      $status = Password::reset(
                      $request->only('email', 'password', 'password_confirmation', 'token'),
                      function ($user, $password) {
                         $user->forceFill([
                             'password' => Hash::make($password)
                         ])->setRememberToken(Str::random(60));

                         $user->save();

                         event(new PasswordReset($user));
                      }
      );

      //return $status === Password::PASSWORD_RESET ? redirect()->route('showLoginForm')->with('status', __($status)) : back()->withErrors(['email' => [__($status)]]);
      return $status === Password::PASSWORD_RESET ? back()
              ->with('status', __('Your password has been reset')) : back()->withErrors(['email' => [__($status)]]);
   }

   public function showRegisterForm() {
      
    //  return view('auth.registration')->with('names', $this->names());

      return view('auth.registration', [
         'isAdmin' =>0,
         'names' => $this->names(),
      ]);

   }

   // User has asked for a new e-mail verification mail.
   public function sendEmailVerificationNotification(Request $request) {
      $user = $request->user();
      //$user->application = $request->application;
      $user->sendEmailVerificationNotification();
      return back()->with('status', 'verification-link-sent');
   }

   public function sendPasswordResetLink(Request $request) {
      
      $request->validate(['email' => 'required|email']);


      //The email contains a link to the 'password.reset' route
      $status = Password::sendResetLink($request->only('email'));
      return $status === Password::RESET_LINK_SENT ?
              back()->with(['status' => 'EmailVerification_OK']) :
              back()->withErrors(['email' => __($status)]);

//      return back()->with('message', 'Verification link sent!');
   }

   // Show the view with the password reset link request form:
   public function showForgotPasswordForm() {
//      return view('auth.forgot-password')->with('application', $this->names());
      return view('auth.forgot-password')->with('names', $this->names());
   }

   public function showLoginForm() {
      return view('auth.login', ['names' => $this->names()]);
   }

   // Show the Reset Password form
   public function showResetPasswordForm($token) {
      return view('auth.reset-password')
              ->with('token', $token)
              ->with('names', $this->names());
   }

   /**
    * Shows the notice which tells the user to open the mail eith a link fot email verification
    * @param type $application
    * @return type
    */
   public function showVerifyEmail() {
      return view('auth.verify-email-notice')->with('names', $this->names());
   }

//   public function verificationVerify(EmailVerificationRequest $request) {
////   public function verificationVerify($id, $hash) {
////      dd("verificationVerify hash=".$hash);
//      $request->fulfill();
//      return redirect('/home');
//   }

   public function signOut() {
      Session::flush();
      Auth::logout();
      
      return Redirect('/');
   }

}
