@extends('schedule.layout')
@section('menu1')
@if ($admin > 0)
   <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   Administration
   </a>
   <div class="dropdown-menu" aria-labelledby="navbarDropdown">
   <a class="dropdown-item" href="{{route('schedule.showAddRemoveDates',['scheduleId' => $schedule->id])}}">{{__('Manage dates')}}</a>
   <a class="dropdown-item" href="{{route('schedule.showMembers',['scheduleId' => $schedule->id])}}">{{__('Manage members')}}</a>
   </div>
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
      <div class="table-responsive" style="overflow-x:auto; overflow-y:hidden;">

        <form id="myForm" action="{{ route('schedule.updateComments')}}" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="scheduleId" value="{{$schedule->id}}">
          <fieldset>
            <legend>{{__('Change comments')}}</legend>
            
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
