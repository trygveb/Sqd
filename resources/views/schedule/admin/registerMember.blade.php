@extends('schedule.layout')
@section('menu1')
@if ($admin > 0)
   <x-schedule-admin-menu schedule-id="{{$schedule->id}}"  manageMembers="{{$manageMembers}}"/>
@endif
@endsection

@section('content')
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>

@endif
 <div class="container" style="max-width:800px;">
   <h1>{{__('Manage members for schedule')}} <a href="{{route('schedule.index', ['scheduleId' => $schedule->id])}}">{{$schedule->name}}</a></h1>
   <span class="link_text">{{__('Show')}}:</span>
   <div id="connected_div" style="display:inline;">
      <a class="btn btn-link" id="connected_rb"  href="{{route('schedule.showMembers',['scheduleId' => $schedule->id])}}">{{__('Connected members')}}</a>
   </div>
   <div id="not_connected_div" style="display:inline;">
      <a class="btn btn-link" id="not_connected_rb"  href="{{route('schedule.showNotConnectedMembers',['scheduleId' => $schedule->id])}}">{{__('Not connected members')}}</a>
   </div>
   <div id="new_member_div" style="display:none;">
      <a class="btn btn-link" id="new_member_rb"  href="{{route('schedule.showViewAdminRegisterMember',['scheduleId' => $schedule->id])}}">{{__('Register new member')}}</a>
   </div>
   
   
   <br>
   
   
   {{-- Show Register new member form --}}
      <div id="newMemberForm">
      <x-registration-form
          :names="$names"
          isAdmin="1" />
      </div>
   
         
 
 </div>
@section('scripts')
<script>
window.onload = function () {
   console.log('onload');
   bindEvents();
};
// Bind the buildName function for keyup and paste events to all input elements with id ending in "_name"
//
function bindEvents() {
   $("input[id$=name]").each(function(){
      $(this).on('input', buildName);  // This event covers keyup an paste ans change
   });
}
function checkForm() {
   buildName();
}
// Construct the complete name
function buildName() {
   console.log('buildName');
   document.getElementById("complete_name").value=document.getElementById("first_name").value + ' ' +
              document.getElementById("middle_name").value +' ' + document.getElementById("family_name").value;
}
// TODO global showHelp function
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
