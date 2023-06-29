<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FixtureTrait;

class ResetPasswordTest extends TestCase {

   use FixtureTrait;

   private $testUserCorrectpassword = 'Qwerty123';
   private $testApplication = 'schedule';

   protected function getValidToken($user) {
      return Password::broker()->createToken($user);
   }

   protected function getInvalidToken() {
      return 'invalid-token';
   }

   protected function passwordResetGetRoute($token) {
      return route('password.reset', $token);
   }

   protected function passwordResetPostRoute() {
      return route('password.update');
   }

   protected function successfulPasswordResetRoute() {
      return route('home');
   }

   public function testUserCanViewAPasswordResetForm() {
      $user = User::factory()->create();
      $response = $this->get($this->passwordResetGetRoute($token = $this->getValidToken($user)));

      $response->assertSuccessful();
      $response->assertViewIs('auth.reset-password');
      $response->assertViewHas('token', $token);
   }

   public function testUserCanNotViewAPasswordResetFormWhenAuthenticated() {
      $user = User::factory()->create();

      $response = $this->actingAs($user)->get($this->passwordResetGetRoute($token = $this->getValidToken($user)));
      $response->assertRedirect(RouteServiceProvider::HOME);
   }

   public function testUserCanResetPasswordWithValidToken() {
      Event::fake();
//        $user = factory(User::class)->create();
      $user = User::factory()->create();
      $response = $this->post($this->passwordResetPostRoute(), [
          'token' => $this->getValidToken($user),
          'email' => $user->email,
          'password' => $this->testUserCorrectpassword,
          'password_confirmation' => $this->testUserCorrectpassword,
      ]);

      $response->assertRedirect($this->successfulPasswordResetRoute());
      $this->assertEquals($user->email, $user->fresh()->email);
      $this->assertTrue(Hash::check($this->testUserCorrectpassword, $user->fresh()->password));
//      $this->assertAuthenticatedAs($user);
      Event::assertDispatched(PasswordReset::class, function ($e) use ($user) {
         return $e->user->id === $user->id;
      });
   }

   public function testUserCannotResetPasswordWithInvalidToken() {
//        $user = factory(User::class)->create(['password' => Hash::make('old-password'),]);
      $user = $this->createUserWithOldPassword();
      $response = $this->from($this->passwordResetGetRoute($this->getInvalidToken()))->post($this->passwordResetPostRoute(), [
          'token' => $this->getInvalidToken(),
          'email' => $user->email,
          'password' => 'new-awesome-password',
          'password_confirmation' => 'new-awesome-password',
      ]);

      $response->assertRedirect($this->passwordResetGetRoute($this->getInvalidToken()));
      $this->assertEquals($user->email, $user->fresh()->email);
      $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
      $this->assertGuest();
   }

   public function testUserCannotResetPasswordWithoutProvidingANewPassword() {
      $user = $this->createUserWithOldPassword();
      $response = $this->from($this->passwordResetGetRoute($token = $this->getValidToken($user)))->post($this->passwordResetPostRoute(), [
          'token' => $token,
          'email' => $user->email,
          'password' => '',
          'password_confirmation' => '',
      ]);

      $response->assertRedirect($this->passwordResetGetRoute($token));
      $response->assertSessionHasErrors('password');
      $this->assertTrue(session()->hasOldInput('email'));
      $this->assertFalse(session()->hasOldInput('password'));
      $this->assertEquals($user->email, $user->fresh()->email);
      $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
      $this->assertGuest();
   }

   public function testUserCannotResetPasswordWithoutProvidingAnEmail() {
      $user = $this->createUserWithOldPassword();

      $response = $this->from($this->passwordResetGetRoute($token = $this->getValidToken($user)))->post($this->passwordResetPostRoute(), [
          'token' => $token,
          'email' => '',
          'password' => 'new-awesome-password',
          'password_confirmation' => 'new-awesome-password',
      ]);

      $response->assertRedirect($this->passwordResetGetRoute($token));
      $response->assertSessionHasErrors('email');
      $this->assertFalse(session()->hasOldInput('password'));
      $this->assertEquals($user->email, $user->fresh()->email);
      $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
      $this->assertGuest();
   }

   private function createUserWithOldPassword() {
      return User::factory()->create(['password' => Hash::make('old-password')]);
   }

}
