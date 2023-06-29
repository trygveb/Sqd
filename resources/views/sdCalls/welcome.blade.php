@extends('sdCalls.layout')

@section('content')
<div class="container">
   <div class="col-md-12 text-center">

      <h1 class="text-center"> {{ __('Welcome to')}} SdCalls</h1>
    
   @guest
      <br>
      <a style="pointer-events: none" class="nav-link" href="{{ route('showLoginForm',['application' =>'sdCalls']) }}">{{ __('Login') }}</a>
      {{ __('or') }}
      <a style="pointer-events: none" class="nav-link" href="{{ route('showRegisterForm',['application' =>'sdCalls']) }}">{{ __('Register') }}</a>       
   @endguest
   @auth
   @if (! Auth::user()->hasVerifiedEmail())
      <a href="{{route('verification.notice',['application' => 'schedule'])}}">{{__('Please confirm your email')}}!</a>
   @endif
   @endauth
   </div>
   <div class="row justify-content-center">
      <div class="col-md-8">
         <div class="card">
            <div class="card-header">SdCalls information</div>
                <div class="card-body">
                    SdCalls is an application under development.<br>
                    Currently theres is nothing to see here.<br>
                    Welcome back later!
                </div>
         </div>
      </div>
   </div>
</div>
@endsection
