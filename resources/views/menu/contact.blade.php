@extends('layouts.app')
@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="card">
         <div class="card-header">{{__('Contact')}}</div>
         <div class="card-body">
            
            {{__('This website is owned by')}} {{config('app.siteOwner')}}. {{__('Use the contact form')}} {{__('below')}} {{__('if you want to contact me')}}!
         </div>
<!--      </div>-->
      <br><br>
<!--      <div class="card">
         <div class="card-header">@lang('Get In Touch With Us')</div>-->
      @if(session()->has('success'))
         <div class="alert alert-success">
            {{ session()->get('success') }}
         </div>
      @endif
      @if(session()->has('error'))
         <!-- Captcha test not  passed -->
         <div class="alert alert-danger">
            {{ session()->get('error') }}
         </div>
   @endif
         <fieldset>
            <legend>{{__('Contact Form')}}</legend>
         <!--<div class="card-body">-->
            <form method="POST" id="contactForm" action="{{ route('contact.sendMail') }}">
               @csrf
               <input type="hidden" name="application" value="{{$names['application']}}" />
               <div class="form-group">
                  <label for="name" >{{ __('Name') }}</label>
                  <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                           size="50" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
               @error('name')
                  <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                  </span>
               @enderror
               </div>

               <div class="form-group">
                  <label for="email">{{ __('E-Mail Address') }}</label>
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
               @error('email')
                  <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                  </span>
               @enderror
               </div>

               <div class="form-group">
                  <label for="message">@lang('Message')</label>
                  <textarea name="message" id="message" class="form-control @error('name') is-invalid @enderror"
                     style="min-height:100px;" value="{{ old('message') }}" required></textarea>
                  @error('message')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                  @enderror
               </div>
               <x-submit-button submitText="{{__('Send e-mail')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{ route('home') }}"/>                                
            </form>
         </fieldset>
         </div>
      </div>       
   </div>
<!--</div>-->

@endsection
@section('scripts')

@endsection