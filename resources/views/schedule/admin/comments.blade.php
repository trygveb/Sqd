@extends('schedule.layout')
@section('menu1')
@if ($isScheduleAdmin)
   <x-schedule-admin-menu schedule-id="{{$schedule->id}}"  manageMembers="{{$manageMembers}}"/>
@endif
@endsection
@section('content')

@php
    $userId=$currentUser->id;
    $tableMaxWidth=520;
    $group= $currentUser->group;
    if ($group > 1) {
        $tableMaxWidth=700;
    }
@endphp
  <div class="container">
 @if ($message = Session::get('success'))
   <div class="alert alert-success alert-block">
      <button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
   </div>
@endif
      
      <div class="table-responsive" style="overflow-x:auto; overflow-y:hidden;">

        <form id="myForm" action="{{ route('schedule.updateComments',['scheduleId' => $schedule->id])}}" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="scheduleId" value="{{$schedule->id}}">
          <fieldset>
              <legend>{{__('Change comments for schedule')}} <a href="{{route('schedule.index', ['scheduleId' => $schedule->id])}}">{{$schedule->name}}</a></legend>
            
        <table class="table table-bordered" style="max-width:{{$tableMaxWidth}}px;">
        
            <thead>
              <th style="vertical-align:middle;" class="text-nowrap text-center">{{__('Date')}}</th>
              <th class="text-nowrap text-center" style="width:290px;">{{__('Comment')}}</th>
            </thead>
            <tbody>

        @foreach ($scheduleDates as $scheduleDate)
               <tr class='status'>
                  <td class="text-nowrap" style="max-width:90px !important;height:32px; padding:2px 7px;vertical-align:middle;">{{$scheduleDate->schedule_date}}
                  </td>
            @php
                  $commentName='comment_'.$scheduleDate->id;
                  $deleteName='delete_'.$scheduleDate->id;
            @endphp
                  <td class="text-nowrap" style="padding:1px 7px;" >
                     <input type="text" maxlength=50 size=40 name="{{$commentName}}" value="{{$scheduleDate->comment}}">
                  </td>
               </tr>
        @endforeach
            </tbody>
         </table>
            <x-submit-button submitText="{{ __('Save changes')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{route('schedule.index', ['scheduleId' => $schedule->id])}}" />
              </fieldset>

        </form>
     </div>
 </div>
<!--</div>-->
@section('scripts')
@endsection

@endsection
