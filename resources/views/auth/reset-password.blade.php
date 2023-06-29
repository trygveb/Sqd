@extends('layouts.app')

@section('content')
@php
   $emailVerified='NO';
   $status='';
   if (session()->has('status')) {
      $status= session()->get('status');
      if ($status=='EmailVerification_OK') {
         $emailVerified='YES';
      }
   } 
@endphp
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8">
         <div class="card">
         <!--emailVerified= {{$emailVerified}}  status={{$status}}<br>-->             
            <div class="card-header">{{ __('Reset Password') }}</div>
         @if(session()->has('success'))
            <div class="alert alert-success">
               {{ session()->get('success') }}
            </div>
         @endif
         @if(session()->has('status'))
            <div class="alert alert-success">
               {{ session()->get('status') }}
            </div>
         @endif


            <div class="card-body">

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
 
               <form method="POST" action="{{ route('password.update') }}">
                  @csrf

                  <!-- Password Reset Token -->
                  <input type="hidden" name="token" value="{{ $token }}">

                  <!-- Email Address -->
                  <div class="form-group row">
                     <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                     <div class="col-md-6">
                        <input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                     </div>
                  </div>

                  <!-- Password -->
                  <div class="form-group row">
                     <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                     <div class="col-md-6">
                        <input id="password" class="block mt-1 w-full" type="password" name="password" required />
                     </div>
                  </div>

                  <!-- Confirm Password -->
                   <div class="form-group row">
                     <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                     <div class="col-md-6">
                        <input id="password_confirmation" class="block mt-1 w-full"
                                          type="password"
                                          name="password_confirmation" required />
                     </div>
                  </div>
                 <div class="col-md-6" style="margin-left:auto; margin-right:auto;">
                     <button type="submit" class="btn btn-primary">{{__('Reset Password')}}</button>
                     <a style="margin-left:5px;" onclick="closeWindow()" href="" class="btn btn-secondary"> {{ __('Close window')}}</a>
                  </div>
                  </form>

                  </div>
                  <br><br>
                  
                  {{__(config('app.passwordFormat1'))}}<br>
                  <ol>
                      <li>{{__(config('app.passwordFormat2'))}}</li>
                     <li>{{__(config('app.passwordFormat3'))}}</li>
                     <li>{{__(config('app.passwordFormat4'))}}</li>
                  </ol>
                 
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('scripts')
<script>
   /**
    * Close this window. 
    * The reason for having this function here instead of in public js folder
    * is that it must cope with locale texts
    */
   function closeWindow() {
      if (confirm("{{__('You are going to close the current window.')}}")) {
         window.close('','_parent','');
      } else {
         txt = "You pressed Cancel!";
      } 
   }
</script>
@endsection