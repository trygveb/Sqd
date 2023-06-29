@extends('schedule.layout')
@section('menu1')
@if ($isScheduleAdmin)
<x-schedule-admin-menu schedule-id="{{$schedule->id}}"  manageMembers="{{$manageMembers}}"/>@endif
@endsection
@section('content')
<h1>{{__('Add/Remove dates for schedule')}} <a href="{{route('schedule.index', ['scheduleId' => $schedule->id])}}">{{$schedule->name}}</a></h1>
 <div class="container">

     <br>
      <form action="{{ route('schedule.addDates',['scheduleId' => $schedule->id])}}" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="scheduleId" value="{{$schedule->id}}">
          <fieldset>
      @if ($maxNumberOfFutureDates > 0)
            <legend>{{__('Enter dates to be added')}}</legend>
         {{__('Standard weekday is')}} {{$weekdays}}{{__('s')}} kl. {{$danceTime}}
         <br><br>
         <div class="table-responsive" style="overflow-x:auto; overflow-y:hidden;">
             {{__('Add')}} <input type="number" id="quantity" name="quantity" min="1" max="{{$maxNumberOfFutureDates}}" size="4" value="1"> 
            <select id="weekdays" disabled style="background-color:#dddddd;">
              <option value="0">{{__('Sundays')}}</option>
              <option value="1">{{__('Mondays')}}</option>
              <option value="2">{{__('Tuesdays')}}</option>
              <option value="3">{{__('Wednesdays')}}</option>
              <option value="4">{{__('Thursdays')}}</option>
              <option value="5">{{__('Fridays')}}</option>
              <option value="6">{{__('Saturdays')}}</option>
            </select>
              {{__('from')}} 
             <input type="date" id="startDate" name="startDate", oninput="dateIsChanged()" min="{{$minDate}}" max="{{$maxDate}}" value="{{$nextDate}}">

        </div>
          <br>
      @endif
      @if ($maxNumberOfFutureDates < 1)
          {{__('You have reached the maximun number of future dates')}} ({{config('app.maxNumberOfFutureDates')}})
      @else
         <x-submit-button submitText="{{__('Add')}} {{__('date')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{route('schedule.index', ['scheduleId' => $schedule->id])}}" />
      @endif
         </fieldset>

      </form>
     <br>
    
     <form action="{{ route('schedule.removeDates')}}" id="removeForm" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="scheduleId" value="{{$schedule->id}}">
          <fieldset>
            <legend>{{__('Select dates to be deleted')}}</legend>

            <table class="table table-bordered table-sm table-sd-schema" >
               <thead>
                  <th class="text-nowrap text-center" style="width:25%;">{{__('Date')}}</th>
                  <th class="text-nowrap text-center">{{__('Comment')}}</th>
                  @if ($isScheduleOwner || $isRoot)
                  <th class="text-nowrap text-center" style="padding:2px 5px 2px 5px;width:25%;">{{__('Delete')}}</th>
                  @endif
               </thead>
               <tbody>
         @foreach ($scheduleDates as $scheduleDate)
            @php
               $deleteName='delete_'.$scheduleDate->id;
            @endphp
                  <tr class='status'>
                     <td class="text-nowrap" style="padding:1px 7px;">{{$scheduleDate->schedule_date}}</td>
                     <td class="text-nowrap" style="padding:1px 7px;" >
                         {{$scheduleDate->comment}}
                     </td>
                     @if ($isScheduleOwner || $isRoot)
                     <td style="text-align:center"><input type="checkbox"  class="inp" onclick="hideOrShowRemoveButton()" name="{{$deleteName}}"> {{__('Delete')}}</td>
                     @endif
                  </tr>
         @endforeach
               </tbody>
            </table>
            <br>
             @if ($isScheduleOwner || $isRoot)
            <x-submit-button submitText="{{ __('Remove date(s)')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{route('schedule.index', ['scheduleId' => $schedule->id])}}"
                             my-id="removeButton" onclick-function="return checkDeletes()" />
             @endif
         </fieldset>
     
     </form>
 </div>
@section('scripts')
<script>
 
window.onload = function() {
   checkWeekday();
    // hideOrShowRemoveButton();
};



function checkWeekday() {
   let element = document.getElementById("weekdays");
    element.value = "{{$weekDaysNumber}}";
}

//  If the user picks a day from the date picker, update the the value in the
//  weekdays SELECT input element to show correct the day of week
function dateIsChanged() {
   var selectedDate = document.getElementById("startDate").value;
   var dayOfWeek= new Date(selectedDate).getDay();
   console.log(selectedDate+' veckodag='+dayOfWeek);
   let element = document.getElementById("weekdays");
   element.value= dayOfWeek;
}
//Hide Remove button if  no date is marked for removal
function hideOrShowRemoveButton() {
      var n= countDeletes();
      console.log(n + ' deletes');
      const removeButton = document.getElementById("removeButton");
      if (n==0) {
          removeButton.style.display = "none";      
      } else {
          removeButton.style.display = "inline-block";      
      }
   }
   
   function countDeletes() {
      var checkBoxes=  document.querySelectorAll('.inp');
      console.log(checkBoxes.length + ' checkBoxes');
      let n=0;
      for (let i = 0; i < checkBoxes.length; i++) {
         if (checkBoxes[i].checked) {
            n++;
         }
      }
      return n;
   }
   
   function checkDeletes() {
      var n= countDeletes();
      if (n> 0 ) {
         return confirm('Är du säker? Du har markerat '+n+' datum för borttag.');
      } else {
         return true;
      }
   }     

</script>
@endsection

@endsection
