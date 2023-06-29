@extends('layouts.app')
@section('content')
@php
   $emailVerified='NO';
   $status='';
   if (session()->has('status')) {
      $status= session()->get('status');
      echo($status);
      if ($status=='EmailVerification_OK') {
         $emailVerified='YES';
      }
   } 
@endphp

<div class="container">
        
    <div class="row justify-content-center">
        <div class="col-md-8">
        <!-- Session Status -->
        <!--<x-auth-session-status class="mb-4" :status="session('status')" />-->
        
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        
         <div class="card">
             <!--emailVerified= {{$emailVerified}}  status={{$status}}<br>-->
            <div class="card-header">{{ __('Forgot Your Password?') }}</div>            
            <div class="card-body">
         @if ($emailVerified=='NO')
               {{ __('OK. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
               <br>
         @else
         <div class="alert alert-success">
               {{__('We have emailed your password reset link')}}!
               <br>
               {{__('Open the mail and click on the the button with text ":text"', ['text' => __('Reset Password')])}}.
               <br>
               {{__('After submitting the form for password reset in the pop-up window, you can close that and return to this window and press the "Back to login" button below')}}.
         </div>
         @endif
               <br>
               <form method="POST" action="{{ route('password.email') }}">
                   @csrf
            @if ($emailVerified=='NO')
                   <!-- Email Address -->
                  <div>
                       <label for="email" >{{__('E-Mail Address')}}</label>
                       <input id="email" class="form-control block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                  </div>
            @endif
                   <br>
                        <p style="float:right;">
                  @if ($emailVerified=='NO')
                           <button type="submit" class="btn btn-primary">{{ __('Send Password Reset Link')}}</button>
                  @endif
                           <a style="margin-left:5px;" href="{{url('login')}}" class="btn btn-primary"> {{ __('Back to login')}}</a>
                        </p>

<!--                     </div>                   
                  </div>-->
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection