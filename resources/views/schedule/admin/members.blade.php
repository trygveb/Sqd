@extends('schedule.layout')
@section('menu1')

@if ($isScheduleAdmin)
<x-schedule-admin-menu schedule-id="{{$schedule->id}}"  manageMembers="{{$manageMembers}}"/>
@endif

@endsection


@section('content')
   @if ($errors->any())
       <div class="alert alert-danger">
           <ul>
               @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
               @endforeach
           </ul>
       </div>
   @endif
   
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
 @if ($message = Session::get('success'))
   <div class="alert alert-success alert-block">
      <button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
   </div>
@endif

   <h1>{{__('Manage members for schedule')}}  <a href="{{route('schedule.index', ['scheduleId' => $schedule->id])}}">{{$schedule->name}}</a></h1>
   <span class="link_text">{{__('Show')}}:</span>
   <div id="connected_div" style="display:none;">
      <a class="btn btn-link" id="connected_rb"  href="{{route('schedule.showMembers',['scheduleId' => $schedule->id])}}">{{__('Connected members')}}</a>
   </div>
   <div id="not_connected_div" style="display:inline;">
      <a class="btn btn-link" id="not_connected_rb"  href="{{route('schedule.showNotConnectedMembers',['scheduleId' => $schedule->id])}}">{{__('Not connected members')}}</a>
   </div>
   <div id="new_member_div" style="display:inline;">
      <a class="btn btn-link" id="new_member_rb"  href="{{route('schedule.showViewAdminRegisterMember',['scheduleId' => $schedule->id])}}">{{__('Register new member')}}</a>
   </div>
   <br>

   {{-- Show form with connected members, email adresses for updating or removing  --}}
   <form action="{{ route('schedule.updateMember')}}" method="POST" id="updateMemberForm">
      <fieldset>
          <div>
          <fieldset>
          <div style="margin-bottom:5px;">
          {{__('E-mail addresses')}}:
         <a class="btn-link" id="copyButton" style="float:right;" onClick="copyEmailAdresses()"> {{__('Copy to clipboard')}}</a>
          </div>    
        
         <textarea class="form-control" style="background-color:#ccc" id="emailAdresses"  readonly cols="70">{{$emails}}</textarea>
         </fieldset>
         </div>
          
         <div class="form-info-text" id="help_text" style="display:none;">
         <br>
         <ul>
            <li>{{__('Number=2 for pairs, otherwise 1.')}}</li>
            <li>{{__('"Name_in_schedule" must be unique in the schedule, but may be different in different schedules.')}}</li>
            <li>{{__('"Name_in_schedule" should be short,maximum 12 characters.')}}</li>
            <li>{{__('If you check Remove, that member will be removed from this schedule, but will remain registered in SdSchema.')}}</li>
         </ul>
         </div>
      <br>
         <x-member-table
            legendTitle="{{__('Connected members')}}"
            connected="yes"
            :schedule="$schedule"
            :vMemberSchedules="$vMemberSchedules"   />
    *) Admin= {{__('give limited authority on the schedule to other members who can then')}} 
              {{__('add dates, and update comment fields')}},
<br><br>
         <x-submit-button submitText="{{__('Update')}}"
                   cancelText="{{ __('Cancel')}}"
                   cancelUrl="{{route('schedule.index', ['scheduleId' => $schedule->id])}}"
                   myId="submitButton"
                   onclickFunction="return checkSubmit()" />
      </fieldset>
      </form> 
   
 
 </div>
@section('scripts')
<script>

 //Detect changes in input fields
$("input").on("change keyup paste", function(){
    checkForm(1);
});

window.onload = function() {
  const submitButton = document.getElementById("submitButton");
  submitButton.disabled = true;
  checkForm();
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

function copyEmailAdresses() {
    $("#emailAdresses").select();
    document.execCommand('copy');
    alert("{{__('Email adresses copied to clipboard')}}");

}
function checkForm(status=0) {
   fixSubmitButton(status);
}


//Change text on submit button if members are marked for removal
function fixSubmitButton(status=0) {
   const submitButton = document.getElementById("submitButton");
   if (status > 0) {
      submitButton.disabled = false;
   }
      var n= countDeletes();
//      console.log('status='+status + ', ' + n + ' deletes');
      if (n===0) {
         submitButton.textContent  ="{{__('Update')}}";
      } else {
         submitButton.textContent  ="{{__('(Update and) remove checked mebers from schedule')}}";
      }
   };
   
function checkUniqueNames() {
   var elements = document.getElementById("updateMemberForm").elements;
   var textElementValues=[];
   for (var i = 0, element; element = elements[i++];) {
      if (element.type === "text") {
//        console.log("textfield="+element.value);
        textElementValues.push(element.value);
     }
   }
   duplicates=findDuplicates(textElementValues);
  
   if (duplicates.length > 0) {
      alert("{{__('Name in schema must be unique in the schema')}}:"+duplicates);
      return false;
   }
  return true;
};



function countDeletes() {
   var checkBoxes=  document.querySelectorAll('.cbRemove');
   let n=0;
   for (let i = 0; i < checkBoxes.length; i++) {
      if (checkBoxes[i].checked) {
         n++;
      }
   }
   return n;
};

function checkSubmit() {
   if (!checkUniqueNames()) {
      return false;
   };
   var n= countDeletes();
   if (n> 0 ) {
   submitButton.disabled = false;
   return confirm("{{__('Are you sure? You have selected')}} "+n+" {{__('members for removal')}}.\n\
{{__('This can not be undone. If the member wants to join again later, he/she must register again')}}");
   } else {
      return true;
   }
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
