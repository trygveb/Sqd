@extends('layouts.app')
@section('content')
<div class="container">
<div style="max-width:650px;margin:auto">

<h1>{{__('What is SdSchema')}}</h1>

<p class=MsoNormal>{{__('SdSchema is a web application for members to be able to register attendance in advance for activities that are held regularly, usually once a week')}}.
    <br>
    {{__('The need exists for activities that require a minimum number of people present for the activity to be carried out, for example square dancing where there must be at least eight people')}}.
    <br>
    {{__('The source code is open and available at')}} <a href="https://github.com/trygveb/Sqd">Github</a>.
</p>
<h5>{{__('How does it work')}}</h5>
{{__('To connect to a schedule, you first need to register, with email address and password')}}.
<br>
{{__('The schedule manager can then connect you to one or more schedules')}}.
<br>
{{__('Once registered, you will, when you log in, be redirected directly to the schedule page , (if you are only connected to one schedule)')}}.
{{__('If you are connected to more than one schedule, you must first select (click on) which schedule you want to access')}}.
<br>
{{__('Once on the schedule page, you can register your presence at future activity events')}}.
<br>
{{__('If you are registered as a couple in the schedule, you can register whether zero, one or two people are coming')}}.
{{__('Otherwise, register Yes or No')}}.
<br>
{{__('The option "Maybe" is also included to indicate that you want to decide later')}}.
<br>
{{__('Note that your name on the schedule page is the "Name_in_schema" given to you by the Schedule Administrator')}},
<a href="#name_in_schema">{{__('see below')}}</a>.
<br><br>
{{__('To create a schedule, you need to be Schedule Administrator')}}.
{{__('To become that, you must')}} <a href="{{route('contact')}}">{{__('contact the application manager')}}.</a>
<br><br>
<h5>{{__('As Schedule Administrator you can')}}:</h5>
<ul>
   <li>{{__('create new schedules with name, description and a (default) day of the week and a (default) time')}}</li>
   <li>{{__('connect registered members to your schedules')}}</li>
   <li>{{__('add and remove dates for schedule activities')}}</li>
   <li>{{__('update the comment field for the respective activity date')}}</li>
   <li>{{__('in each schedule register whether the member represents one or two people')}}</li>
   <li>{{__('in each schedule register a "Name_in_schedule" for each member')}}<sup>1</sup></li>
   <li>{{__('give limited authority on the schedule to other members who can then')}}
       {{__('add dates, and update comment fields')}} </li>
 </ul>
<a name="name_in_schema">1)</a> 
{{__('"Name_in_schedule" is the name that will be displayed in the schedule')}}.
{{__('The schedule administrator gives each member or couple an unique "Name_in_schedule"')}}.
{{__("The default value is the member's first name")}}.
{{__('If the member is registered as a couple, it will probably be something else')}}.
{{__('It may not be longer than 12 characters')}}.
</div>

    
   </div>
<!--</div>-->

@endsection
@section('scripts')

@endsection