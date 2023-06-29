@extends('schedule.layout')
@section('menu1')
@endsection
@section('content')
{{-- This view is used both for schedule admins and normal users.
     It is a form, but the Submit and Help buttons are hidden if you not are a Schedule admin.
   --}}
  <div class="container">
      <div class="table-responsive" style="overflow-x:auto; overflow-y:hidden;">
        <form id="myForm" action="{{ route('schedule.updateSchedule')}}" method="POST" name="firefoxSpecial">
          {{ csrf_field() }}
          <input type="hidden" name="userId" value="{{Auth::id()}}">
          <fieldset style="min-width:850px;">
            <legend>{{__('My schedules')}}
                @if ($isAdmin > 0)
                <a class="btn btn-link" style="float:right;" id="help_link" onclick="showHelp()" >{{__('Help')}}</a>
                @endif
            </legend>
      <div class="form-info-text" id="help_text" style="display:none;">
      {{__('Weekday is a default value, and you can choose other weekdays when you add new dates to the schedule')}}.<br>
      {{__('Starting time is curently only displayed in this page')}}.<br>
      {{__('Schedule name must not be longer than 30 characters')}}.<br>
      {{__('Schedule description must not be longer than 48 characters')}}.

      <br>
      </div>
        <table class="table table-bordered">
            <caption></caption>
            <tr>
              <th style="vertical-align:middle;" class="text-nowrap text-center">{{__('Show')}}</th>
              <th style="vertical-align:middle;" class="text-nowrap text-center">{{__('Name')}}</th>
              <th class="text-nowrap text-center" >{{__('Description')}}</th>
              <th class="text-nowrap text-center" >{{__('Weekday')}}</th>
              <th class="text-nowrap text-center" >{{__('Starting at')}}</th>
              <th class="text-nowrap text-center" >{{__('Administrator')}}</th>
            </tr>
            <!--<tbody>-->

        @foreach ($myVMemberSchedules as $myVMemberSchedule)
         @php
            $schemaNameInput='name_'.$myVMemberSchedule->schedule_id;
            $schemaDescriptionInput='description_'.$myVMemberSchedule->schedule_id;
            $weekdayInput='weekDay_'.$myVMemberSchedule->schedule_id;
            $timeInput='time_'.$myVMemberSchedule->schedule_id;
         @endphp
               <tr class='status'>
                  <td class="text-nowrap" >
                      <a href="{{route('schedule.index',$myVMemberSchedule->schedule_id)}}">{{__('Show')}}</a>
                  </td>
                  <td class="text-nowrap" >
                      @if ($myVMemberSchedule->isAdmin > 0)
                       <input type="text" maxlength=30 size=20 name="{{$schemaNameInput}}"  required value="{{$myVMemberSchedule->schedule_name}}" >
                       @else
                       {{$myVMemberSchedule->schedule_name}}
                       @endif
                  </td>
                  <td class="text-nowrap">
                      @if ($myVMemberSchedule->isAdmin > 0)
                       <input type="text" maxlength=48 size=26 name="{{$schemaDescriptionInput}}"  value="{{$myVMemberSchedule->schedule_description}}" >
                       @else
                       {{$myVMemberSchedule->schedule_description}}
                       @endif
                      
                  </td>
                  <td class="text-nowrap">
                      @if ($myVMemberSchedule->isAdmin > 0)
                        <select class="form-select" aria-label="{{ __('Weekday') }}" name="{{$weekdayInput}}" id="weekday-select" required >
                           <option value="1" @if ($myVMemberSchedule->default_weekday==1) selected @endif>{{ __('Mondays')}}</option>
                           <option value="2" @if ($myVMemberSchedule->default_weekday==2) selected @endif>{{ __('Tuesdays')}}</option>
                           <option value="3" @if ($myVMemberSchedule->default_weekday==3) selected @endif>{{ __('Wednesdays')}}</option>
                           <option value="4" @if ($myVMemberSchedule->default_weekday==4) selected @endif>{{ __('Thursdays')}}</option>
                           <option value="5" @if ($myVMemberSchedule->default_weekday==5) selected @endif>{{ __('Fridays')}}</option>
                           <option value="6" @if ($myVMemberSchedule->default_weekday==6) selected @endif>{{ __('Saturdays')}}</option>
                           <option value="7" @if ($myVMemberSchedule->default_weekday==7) selected @endif>{{ __('Sundays')}}</option>
                       </select>
                       @else
                       
                        @switch($myVMemberSchedule->default_weekday)
                            @case(1)
                                {{ __('Mondays')}}
                                @break
                           @case(2)
                                {{ __('Tuesdays')}}
                                @break
                           @case(3)
                                {{ __('Wednesdays')}}
                                @break
                           @case(4)
                                {{ __('Thursdays')}}
                                @break
                           @case(5)
                                {{ __('Fridays')}}
                                @break
                           @case(6)
                                {{ __('Saturdays')}}
                                @break
                           @case(7)
                                {{ __('Sundays')}}
                                @break
                            @default
                                {{ __('Unknown')}}
                        @endswitch                       
                       @endif
                  </td>
                  <td>
                      @if ($myVMemberSchedule->isAdmin > 0)
                     <input id="schedule_time" type="time"  name="{{$timeInput}}"
                      required value="{{ $myVMemberSchedule->default_start_time }}"  step="60" >
                     @else
                     {{ $myVMemberSchedule->default_start_time }}
                     @endif
                  </td>
                  <td class="text-nowrap">
                      {{$myVMemberSchedule->admins}}
                  </td>
               </tr>
         @endforeach
         
 
         </table>
            @if ($isAdmin > 0)
            <x-submit-button submitText="{{ __('Save changes')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{route('home')}}"/>
            @endif
         </fieldset>

        </form>
     </div>
 </div>
<!--</div>-->
@section('scripts')
<script>
   // Fix Firefox bug for selected option 
   window.onload = function() { document.forms['firefoxSpecial'].reset(); };

   function showHelp() {
      var helpText = document.getElementById("help_text");
      var helpLink= document.getElementById("help_link");
      if (helpText.style.display === 'none') {
         helpText.style.display='inline-block';
         helpLink.innerHTML="{{__('Hide Help')}}";
      } else {
         helpText.style.display='none';
         helpLink.innerHTML="{{__('Help')}}";
      }
   }
   
</script>
@endsection

@endsection
