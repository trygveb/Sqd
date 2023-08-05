@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" style="max-width:900px;">
            <!--<form method="GET" action="">-->
                 @csrf
                <fieldset>
 @include('includes.reportError')             
 @include('includes.call')                   

               <div class="form-group form-check">
                  <input type="checkbox" class="form-check-input" name="show_voice" id="show_voice" 
                         onClick="showVoice()">
                  <label class="form-check-label" for="show_voice">Show voice</label>
               </div>
 @include('includes.voice')
 @include('includes.speach')
            </fieldset>

            <!--</form>-->
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
window.onload = function() {
//   GetVoiceList();
   GetCallList();
//   $("#show_voice").removeAttr("checked");
   $('#show_voice').prop('checked', false);
};
function showVoice() {
   if ($('#fieldset_voice').css('display')==='none') {
      $('#fieldset_voice').show();
   } else {
      $('#fieldset_voice').hide();
   }
}
function createMp3Text() {
      $('#mp3Row').show();
      getCallText(true);
}
function createMp3File() {
   console.log('createMp3File');
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
   });
   var formData = createFormData();
   //console.log(formData);
   $.ajax({
      type:'POST',
      url:'/createMp3File',
      data:formData,
      success:function(data) {
         console.log('path='+data.path);
         //$("#player").attr('src', data.path+ "?" + new Date().getTime());
         $("#player").attr('src', data.path);
         $("#player").css("pointer-events", "auto");
      }
   });
}
   
function createFormData(ssml=false) {
   var formData = {
         _token: '<?php echo csrf_token() ?>',
         definition_id: $('#definition_id').find(":selected").val(),
         language:$('#language').find(":selected").val(),
         voice_type:$('#voice_type').find(":selected").val(),
         voice_gender: document.querySelector('input[name="voice_gender"]:checked').value,
         voice_pitch:$('#voice_pitch').val(),
         speaking_rate:$('#speaking_rate').val(),
         volume_gain:$('#volume_gain').val(),
         voice_name:$('#voice_name').find(":selected").text(),
         program_id:$('#program_id').find(":selected").val(),
         include_start_formation:$('#include_start_formation')[0].checked,
         include_end_formation:$('#include_end_formation')[0].checked,
         include_formations_in_repeats:$('#include_formations_in_repeats')[0].checked,
         repeats:$('#repeats').val(),
         ssml:ssml,
         callText: $("#callText").html(),
         path: $("#path").html()
     };
     return formData;   
}

function getCallText(ssml=false) {
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
   });
   var formData= createFormData(ssml); 
   $.ajax({
      type:'POST',
      url:'/getCallText',
      data:formData,
      success:function(data) {
         $("#callText").html(data.callText);
         $("#startFormation").html('From: '+data.from);
         $("#endFormation").html('Ends in: '+data.endsIn);
         $("#path").html(data.path);
      }
   });
}

function GetVoiceList() {
   $("#voice_name").empty();
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
   });
   var formData = {
         _token: '<?php echo csrf_token() ?>',
         language:$('#language').find(":selected").val(),
         voice_type: $('#voice_type').find(":selected").val(),
         voice_gender: document.querySelector('input[name="voice_gender"]:checked').value
     };

   $.ajax({
      type:'POST',
      url:'/getVoiceList',
      data:formData,
      success:function(data) {
         console.log(data);
         // Populate listbox voice_name
         select_elem= document.getElementById("voice_name");
         data.forEach((element, index) => {
           let option_elem = document.createElement('option');
           // Add index to option_elem
           option_elem.value = index;
           // Add element HTML
           option_elem.textContent = element;
           // Append option_elem to select_elem
           select_elem.appendChild(option_elem);
         }); 
      }
   });
}
function GetCallList() {
   $("#definition_id").empty();
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
   });
   programId=$('#program_id').find(":selected").val();
   var formData = {
         _token: '<?php echo csrf_token() ?>',
         program_id:programId
     };

   $.ajax({
      type:'POST',
      url:'/getCallList',
      data:formData,
      success:function(data) {
         var arr = Object.values(data);
         // Populate listbox definition_id
         select_elem= document.getElementById("definition_id");
         arr.forEach((element, index) => {
//           console.log(element);
           let option_elem = document.createElement('option');
           option_elem.value = element.definition_id;
           option_elem.textContent = element.call_name + ', ' + element.program_name + ', from ' + element.start_formation_name;
           select_elem.appendChild(option_elem);
         }); 
      }      
   });
}

function editCall() {
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
   });
   const definition_id= $('select[name="definition_id" ]').val(); 
   
   const url = `/editCall/${definition_id}`;
   console.log(url);
	$.ajax({
      dataType: 'json',
		url:url,
		type: 'GET',
		success: function(response) {
			$('body').html(response.html);
         //console.log(response.fragments);
         json= JSON.parse(response.fragments);
         //[{"id":8,"definition_id":4,"fragment_id":8,"seq_no":1,"part_no":null},{"id":9,"definition_id":4,"fragment_id":9,"seq_no":2,"part_no":null}]
         var i=1;
         json.forEach(function(obj) {
            //console.log(obj.fragment_type_id
            //Show the div containing the fragment listbox (hidden by default)
            document.getElementById('div_id_' + i).style.display='block';
            // Select the list item in the listbox
            selectElement('fragment_id_' + i, obj.fragment_id);
            if ({{$fragmentTypeParanthesisId}}==obj.fragment_type_id) {
               //console.log('Parantehsis');
               checkboxElement=document.getElementById('checkbox1_id_' + i);
               checkboxElement.checked = true;
               var fragmentElement= document.getElementById('fragment_id_' + i);
               var selectedText = fragmentElement.options[fragmentElement.selectedIndex].text; 
               fragmentElement.options[fragmentElement.selectedIndex].text= '('+selectedText+')'
            }
            i++;
         });
         var n= i-1;
         // Show plus and minus buttons on last fragment only
         document.getElementById('plus_button_id_' + n).style.display='block';
         document.getElementById('minus_button_id_' + n).style.display='block';
         //selectElement('call_id', response.call_id);
         //document.getElementById('call_name_1').value= response.call_name;
         selectElement('program_id', response.program_id);
         selectElement('start_formation_id', response.start_formation_id);
         selectElement('end_formation_id', response.end_formation_id);
		}
	});
   
}

</script>
@endsection
