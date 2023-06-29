<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Schedule\Schedule;
use App\Models\Schedule\MemberSchedule;
use App\Models\Schedule\MemberScheduleDate;
use App\Models\Schedule\ScheduleDate;

class DatabaseSeeder extends Seeder {

   /**
    * Seed the application's database.
    *
    * @return void
    */
   public function run() {

      $this->prepareSeed();

      $this->createUsers();
   }

   private function createHashedPassword() {
      $password = 'laravel';
      $hashedPassword = Hash::make($password);
      if (!Hash::check($password, $hashedPassword)) {
         echo "Pasword does not match!\n";
      }
      return$hashedPassword;
   }

   private function createUsers() {
      $hashedPassword = $this->createHashedPassword();
      $now= now();
      $this->insertUser('kalle.anka@sqd.se', $hashedPassword, 'Kalle', 'Anka', $now);
      $this->insertUser('kajsa.anka@sqd.se', $hashedPassword, 'Kajsa', 'Anka', $now);
      $this->insertUser('knatte.anka@sqd.se', $hashedPassword, 'Knatte', 'Anka', $now);
      $this->insertUser('fnatte.anka@sqd.se', $hashedPassword, 'Fnatte', 'Anka', $now);
      $this->insertUser('tjatte.anka@sqd.se', $hashedPassword, 'Tjatte', 'Anka', $now);
      $this->insertUser('morten.gas@sqd.se', $hashedPassword, 'Mårten', 'Gås', $now);
      $this->insertUser('jan.langben@sqd.se', $hashedPassword, 'Jan', 'Långben', $now);
      $this->insertUser('musse.pigg@sqd.se', $hashedPassword, 'Musse', 'Pigg', $now);
      return User::count();
   }
   private function insertUser($email, $hashedPassword, $firstName, $familyName, $now) {
      DB::table('users')->insert([
          'email' => $email,
          'password' => $hashedPassword,
          'first_name' => $firstName,
          'family_name' => $familyName,
          'authority' => 0,
          'email_verified_at' => $now,
          'created_at' => $now,
          'updated_at' => $now,
      ]);
      
   }

   private function prepareSeed() {
      // Delete all users. By the FK relationship ON DELETE CASCADE,
      // the data in tables member_schedule and member_schedule_date will also be deleted.
      DB::connection('laravel')->table('users')->delete();

      // Delete all schedules and By the FK relationship ON DELETE CASCADE,
      // the data in table schedule_date will also be deleted.
      DB::connection('schedule')->table('schedule')->delete();

      DB::connection('laravel')->statement('ALTER TABLE users AUTO_INCREMENT=0;');
      DB::connection('schedule')->statement('ALTER TABLE schedule AUTO_INCREMENT=0;');
      DB::connection('schedule')->statement('ALTER TABLE schedule_date AUTO_INCREMENT=0;');
      DB::connection('schedule')->statement('ALTER TABLE member_schedule AUTO_INCREMENT=0;');
      DB::connection('schedule')->statement('ALTER TABLE member_schedule_date AUTO_INCREMENT=0;');
   }

}
