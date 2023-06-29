   @extends('layouts.app')

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
   
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-md-8">
            <div class="card">
             @empty($isAdmin)
                <div class="card-header">{{ __('Register for') }} {{$names['application']}}</div>
             @else
             <div class="card-header">{{ __('Register new member and connect to schema') }}: {{$scheduleName}}</div>
             @endempty

         @if(session()->has('success'))
            <div class="alert alert-success">
               {{ session()->get('success') }}
            </div>
         @endif

            <div class="card-body">
                    
         {{-- Show Register new member form --}}
            <div id="newMemberForm" >
            <x-registration-form
                :names="$names"
                isAdmin="0" />
            </div>
          </div>
      </div>
   </div>
</div>
@endsection
@section('scripts')
<script>
   window.onload = function () {
      bindEvents();
      checkForm();
    
   };
   function bindEvents() {
      $("input[id$=name]").each(function(){
         $(this).on('input', buildName);  // This event covers keyup an paste ans change
      });
   }
// Construct the complete name
   function buildName() {
      document.getElementById("complete_name").value=document.getElementById("first_name").value + ' ' +
              document.getElementById("middle_name").value +' ' + document.getElementById("family_name").value;
}
   function checkForm() {
      var form = document.getElementById('registrationForm');
      var showButton= true;
      for(var i=0; i < form.elements.length; i++){
         if (form.elements[i].value === '' && form.elements[i].hasAttribute('required')){
            showButton= false;    
         }
      }
      @empty($isAdmin)
      if (showButton && document.getElementById('privacy_confirm').checked) {
         document.getElementById('submit-button').disabled = "";
      } else {
         document.getElementById('submit-button').disabled = "disabled";
      }
      @else
      if (showButton) {
         document.getElementById('submit-button').disabled = "";
      } else {
         document.getElementById('submit-button').disabled = "disabled";
      }
      @endempty
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