<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Feature;
use Illuminate\Support\Facades\Artisan;

trait FixtureTrait {
   public function setUp(): void {
      parent::setUp();
//      Artisan::call('db:wipe', ['--database' => 'sdCalls']);
      Artisan::call('db:wipe', ['--database' => 'schedule']);
      Artisan::call('db:wipe', ['--database' => 'laravel']);
      Artisan::call('migrate');
      \DB::connection('schedule')->beginTransaction();
//      \DB::connection('sdCalls')->beginTransaction();
      \DB::connection('laravel')->beginTransaction();
   }

   public function tearDown(): void {
//      putenv(LaravelLocalization::ENV_ROUTE_KEY);
      \DB::connection('laravel')->rollBack();
//      \DB::connection('sdCalls')->rollBack();
      \DB::connection('schedule')->rollBack();
      parent::tearDown();
   }
   
}