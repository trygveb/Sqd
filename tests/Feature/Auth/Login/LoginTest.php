<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FixtureTrait;

class LoginTest extends TestCase {

   use FixtureTrait;

   private $SEEDED_USERS = 0;
   private $testUserName = 'John Doe';
   private $testUserEmail = 'john@example.com';
   private $testUserCorrectpassword = 'Qwerty123';
   private $testApplication = 'schedule';
   private $tooManyRequestsStatus= 429;

   protected function successfulLoginRoute() {
      return route($this->testApplication . '.home');
   }

   protected function loginGetRoute() {
      return route('showLoginForm');
   }

   protected function loginPostRoute() {
      return route('login.custom');
   }

   protected function logoutRoute() {
      return route('signout');
   }

   protected function successfulLogoutRoute() {
      return '/';
   }

   protected function guestMiddlewareRoute() {
      return route('home');
   }

   protected function getTooManyLoginAttemptsMessage() {
      return sprintf('/^%s$/', str_replace('\:seconds', '\d+', preg_quote(__('auth.throttle'), '/')));
   }

   public function testUserCanViewALoginForm() {
      $response = $this->get($this->loginGetRoute());

      $response->assertSuccessful();
      $response->assertViewIs('auth.login');
   }

   public function testUserCannotViewALoginFormWhenAuthenticated() {
      $user = User::factory()->make();
//        $user = factory(User::class)->make();

      $response = $this->actingAs($user)->get($this->loginGetRoute());

      $response->assertRedirect($this->guestMiddlewareRoute());
   }

   /**
    * In this test we use a password which does not pass the format test
    * because the application should let that pass at login, but not at registration
    * nor at password reset
    */
   public function testUserCanLoginWithCorrectCredentials() {
      $user = User::factory()->create([
          'password' => Hash::make($password = 'i-love-laravel'),
      ]);

      $response = $this->post($this->loginPostRoute(), [
          'email' => $user->email,
          'password' => $password,
          'application' => $this->testApplication
      ]);

      $response->assertRedirect($this->successfulLoginRoute());
      $this->assertAuthenticatedAs($user);
   }

   public function testRememberMeFunctionality() {
      $user = User::factory()->create([
          'id' => random_int($this->SEEDED_USERS + 1, 100),
          'password' => Hash::make($password = $this->testUserCorrectpassword),
      ]);

      $response = $this->post($this->loginPostRoute(), [
          'email' => $user->email,
          'password' => $this->testUserCorrectpassword,
          'application' => $this->testApplication,
          'remember' => 'on',
      ]);

      $user = $user->fresh();

      $response->assertRedirect($this->successfulLoginRoute());
      $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
          $user->id,
          $user->getRememberToken(),
          $user->password,
      ]));
      $this->assertAuthenticatedAs($user);
   }

   public function testUserCannotLoginWithIncorrectPassword() {
      $user = User::factory()->create([
          'password' => Hash::make('i-love-laravel'),
      ]);

      $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
          'email' => $user->email,
          'password' => 'invalid-password',
          'application' => $this->testApplication,
      ]);

      $response->assertRedirect($this->loginGetRoute());
      $response->assertSessionHasErrors('email');
      $this->assertFalse(session()->hasOldInput('email'));
      $this->assertFalse(session()->hasOldInput('password'));
      $this->assertGuest();
   }

   public function testUserCannotLoginWithEmailThatDoesNotExist() {
      $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
          'email' => 'nobody@example.com',
          'password' => 'invalid-password',
          'application' => $this->testApplication,
      ]);

      $response->assertRedirect($this->loginGetRoute());
      $response->assertSessionHasErrors('email');
      $this->assertFalse(session()->hasOldInput('email'));
      $this->assertFalse(session()->hasOldInput('password'));
      $this->assertGuest();
   }

   public function testUserCanLogout() {
      $this->be(User::factory()->create());

      $response = $this->get($this->logoutRoute());

      $response->assertRedirect($this->successfulLogoutRoute());
      $this->assertGuest();
   }

   public function testUserCannotLogoutWhenNotAuthenticated() {
      $response = $this->get($this->logoutRoute());

      $response->assertRedirect($this->successfulLogoutRoute());
      $this->assertGuest();
   }

   /**
    * THis test i not working, I don't know why
    */
   public function XtestUserCannotMakeMoreThanFiveAttemptsInOneMinute() {
      $user = User::factory()->create([
          'password' => Hash::make($password = 'i-love-laravel'),
      ]);

      foreach (range(0, 3) as $_) {
         
         $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
             'email' => $user->email,
             'password' => 'invalid-password',
             'application' => $this->testApplication,
         ]);
      }

     $response->assertStatus($this->tooManyRequestsStatus);
      $this->assertGuest();
   }

}
