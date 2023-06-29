@extends('schedule.layout')
@section('menu1')
@if ($admin > 0)
   <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   Administration
   </a>
   <div class="dropdown-menu" aria-labelledby="navbarDropdown">
   <a class="dropdown-item" href="{{route('schedule.showComments',['scheduleId' => $schedule->id])}}">{{__('Manage comments')}}</a>
   <a class="dropdown-item" href="{{route('schedule.showAddRemoveDates',['scheduleId' => $schedule->id])}}">{{__('Manage dates')}}</a>
   </div>
@endif
@endsection
@section('content')
@if (array_count_values($status) > 0)
   @foreach ($status as $key => $value)
         @if (str_contains($key,'error')) 
         <div class="alert alert-danger">
          {{ $value }}</li>
         </div>
         @endif
   @endforeach
@endif
 <div class="container" style="max-width:800px;">
   <h1>{{__('Manage members for schedule')}} <a href="{{route('schedule.index', ['scheduleId' => $schedule->id])}}">{{$schedule->name}}</a></h1>
   <span class="link_text">{{__('Show')}}:</span>
   <div id="connected_div" style="display:inline;">
      <a class="btn btn-link" id="connected_rb"  href="{{route('schedule.showMembers',['scheduleId' => $schedule->id])}}">{{__('Connected members')}}</a>
   </div>
   <div id="not_connected_div" style="display:none;">
      <a class="btn btn-link" id="not_connected_rb"  href="{{route('schedule.showNotConnectedMembers',['scheduleId' => $schedule->id])}}">{{__('Not connected members')}}</a>
   </div>
   <div id="new_member_div" style="display:inline;">
      <a class="btn btn-link" id="new_member_rb"  href="{{route('schedule.showViewAdminRegisterMember',['scheduleId' => $schedule->id])}}">{{__('Register new member')}}</a>
   </div>
   <br>
   
   {{-- Show Form with table with not connected members (default) --}}
      <form action="{{ route('schedule.connectMember')}}" method="POST" id="addMemberForm">
         <fieldset style="max-width:500px;">
         <div class="form-info-text" id="help_text" style="display:none;">
         <ul>
            <li>{{__('Number=2 for pairs, otherwise 1.')}}</li>
            <li>{{__('"Name_in_schedule" here is just a suggestion.')}}</li>
            <li>{{__('"Name_in_schedule" should be short,maximum 12 characters.')}}</li>
            <li>{{__('"Name_in_schedule" and Number will only apply if you check Connect.')}}</li>
            <li>{{__('"Name_in_schedule" must be unique in the schedule, but may be different in different schedules.')}}</li>
         </ul><br>
         </div>

         <x-member-table 
            legendTitle="{{__('Not connected members')}}"
            connected="no"
            :schedule="$schedule"
            :vMemberSchedules="$nonMembers"   />
         <x-submit-button submitText="{{__('(Update and) connect checked mebers')}}"
            cancelText="{{ __('Cancel')}}"
            cancelUrl="{{route('schedule.index', ['scheduleId' => $schedule->id])}}"
            myId="submitButton"
            submit-disabled="disabled"
            onclickFunction="return checkSubmit()" />
     </fieldset>
     </form> 
   
 
 </div>
@section('scripts')
<script>
window.onload = function() {
  const submitButton = document.getElementById("submitButton");
  submitButton.disabled = true;
};
function checkForm(status=0) {
   fixSubmitButton(status);
}
function checkSubmit() {
   return checkUniqueNames();
};

function findDuplicates(arr) {
  let sorted_arr = arr.slice().sort(); // You can define the comparing function here. 
  // JS by default uses a crappy string compare.
  // (we use slice to clone the array so the
  // original array won't be modified)
  let results = [];
  for (let i = 0; i < sorted_arr.length - 1; i++) {
    if (sorted_arr[i + 1] == sorted_arr[i]) {
      results.push(sorted_arr[i]);
    }
  }
  return results;
};   

//Enable submit button if members are marked for connect
function fixSubmitButton(status=0) {
      var n= countConnects();
      const submitButton = document.getElementById("submitButton");
      if (n===0) {
         submitButton.disabled= true;
      } else {
         submitButton.disabled= false;
      }
   };
function checkUniqueNames() {
   var elements = document.getElementById("addMemberForm").elements;
   var textElementValues=[];
   for (var i = 0, element; element = elements[i++];) {
      if (element.type === "text") {
        textElementValues.push(element.value);
     }
   }
   duplicates=findDuplicates(textElementValues);
  
   if (duplicates.length > 0) {
      alert("{{__('Name_in_schedule is not unique')}}:"+duplicates);
      return false;
   }
  return true;
};

function countConnects() {
   var checkBoxes=  document.querySelectorAll('.cbConnect');
   let n=0;
   for (let i = 0; i < checkBoxes.length; i++) {
      if (checkBoxes[i].checked) {
         n++;
      }
   }
   return n;
};
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
