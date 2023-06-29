@extends('schedule.layout')

@section('menu1')
@if ($scheduleAdmin )
   <x-schedule-admin-menu schedule-id="{{$schedule->id}}"  manageMembers="{{$manageMembers}}"/>
@endif
@endsection
@section('content')
<h1>{{__('Schedule for')}} {{$schedule->name}}
    @if ($editAllowed)
    <a href="{{route('schedule.showEdit',['schedule' =>$schedule])}}" class="btn btn-primary" role="button" style="margin-left:5px;en">
        {{__('Change my attendance')}}
    </a>
    @endif
</h1>
  <div class="container">
      <div class="table-responsive" style="overflow-x:auto; overflow-y:hidden;">
          
      <div class="outer">
          @if ($showHistory==0) 
         <input type="checkbox" id="show_history" name="show_history" > <label for="show_history">{{__('Show historical dates')}}</label>
         @else
         <input type="checkbox" id="show_history" name="show_history" checked> <label for="show_history">{{__('Show historical dates')}}</label>
         @endif
         <div class="inner">
         
          <table class="table table-bordered">
        
            <thead style="font-size:smaller;">
              <th class="fix text-nowrap text-center">{{__('Date')}}</th>
              <th class="text-nowrap text-center">{{__('Comment')}}</th>
             
             
              <th class="text-center w30">{{__('Y')}}</th>
              <th class="text-center w30" >{{__('N')}}</th>
              <th class="text-center" style="width:30px;">{{__('?')}}</th>
              <th class="text-center" style="width:30px;">-</th>
            
        @foreach ($memberNames as $userId => $name)
                <th class="text-nowrap text-center" style="font-size:smaller">{{$name}}</th>
        @endforeach
             </thead>
             <tbody>
        @php
               $i=0;
        @endphp
        @foreach ($scheduleDates as $scheduleDate)
               <tr class='status'>
               <td class="fix text-nowrap" style="height:32px; padding:2px 7px;">{{$scheduleDate->schedule_date}}</td>
            @php
                  $commentName='comment_'.$scheduleDate->id;
            @endphp
               <td style="padding:1px 7px;min-width:15ch;max-width:20ch;" >{{$scheduleDate->comment}}</td>
               <td class="text-center">{{$statusSums[$i]['Y']}}</td>
               <td class="text-center">{{$statusSums[$i]['N']}}</td>
               <td class="text-center">{{$statusSums[$i]['M']}}</td>
               <td class="text-center">{{$statusSums[$i++]['NA']}}</td>
            @foreach ( $memberNames as $userId => $name )
                @php
                    $status= $statuses[$userId][$scheduleDate->id];
                    $group= $groups[$userId];
                    $statusName='-';
                    switch ($status) {
                      case 1: if ($group==1) {
                                 $statusName=__('Yes');
                              } else {
                                 $statusName='1';
                              }
                              break;
                      case 2: $statusName='2';
                              break;
                      case 3: $statusName=__('No');
                              break;
                      case 4: $statusName='?';
                              break;
                     }
                     $radioGroupName='status_'.$userId.'_'.$scheduleDate->id;
                @endphp
                 <td class="text-center">{{$statusName}}</td>
            @endforeach
               </tr>
        @endforeach
              </tbody>
            </table>

     </div>
 </div>
<!--</div>-->
@endsection
@section('scripts')
<script>
window.onload = function() {
  document.getElementsByTagName("title")[0].innerHTML="SdSchema {{$schedule->name}}";
};
const historyCheckbox = document.getElementById('show_history')

historyCheckbox.addEventListener('change', (event) => {
  if (event.currentTarget.checked) {
      location.href = "{{route('schedule.index',['scheduleId' => $schedule->id, 'showHistory' => 1])}}";
  } else {
      location.href = "{{route('schedule.index',['scheduleId' => $schedule->id, 'showHistory' => 0])}}";
  }
})
</script>
@endsection