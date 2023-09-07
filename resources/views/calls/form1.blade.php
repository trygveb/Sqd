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
//   console.log('definitionId= '+{{$user->definition_id}});
   // selectElement('definition_id', {{$user->definition_id}});
//   $("#show_voice").removeAttr("checked");
   $('#show_voice').prop('checked', false);
};
function definition_id_changed() {
   id = document.getElementById("definition_id").value;
   const href='/editDefinition/'+id;
   console.log('definition_id_changed, href='+href);
   document.getElementById('showEditDefinitionLink').href=href;
}
function showVoice() {
   if ($('#fieldset_voice').css('display')==='none') {
      $('#fieldset_voice').show();
   } else {
      $('#fieldset_voice').hide();
   }
}
function showCallText() {
   if ($('#div_call_text').css('display')==='none') {
      $('#div_call_text').show();
   } else {
      $('#div_call_text').hide();
   }
}
function createMp3Text() {
      $('#mp3Row').show();
      getCallText(1);
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
         $("#player").attr('src', data.path);
         $("#player").css("pointer-events", "auto");
      }
   });
}
   
function createFormData(ssml=0) {
   
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
   function editStartFormation() {
      const formationId= document.getElementById('start_formation_id').value;
      const url = `/editFormation/`+formationId;
      console.log(url);
      $.ajax({
         dataType: 'json',
         url:url,
         type: 'GET',
         success: function(response) {
            console.log('editStartFormation success');
            $('body').html(response.html);
   //         selectElement('start_formation_id', response.start_formation_id);
         }
   	});
      
   }
function getCallText(ssml=0) {
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
         $("#callText").html(htmlEntities(data.callText));
         $("#startFormation").html('From: '+data.from);
         if (data.endsIn.startsWith('Usually')) {
            $("#endFormation").html(data.endsIn);
         } else {
            $("#endFormation").html('Ends in: '+data.endsIn);
         }
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
         console.log('editCall success');
			$('body').html(response.html);
         //console.log(response.fragments);
         jsonFragments= JSON.parse(response.fragments);
         //[{"id":8,"definition_id":4,"fragment_id":8,"seq_no":1,"part_no":null},{"id":9,"definition_id":4,"fragment_id":9,"seq_no":2,"part_no":null}]
         var i=1;
         jsonFragments.forEach(function(obj) {
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
         selectElement('start_formation_id', response.start_formation_id);
         selectElement('end_formation_id', response.end_formation_id);
		}
	});
   
}
function programIdChanged(sel) {
   console.log('programIdChanged');
    $("#new-call-href").attr('href', "/newCall/"+sel.options[sel.selectedIndex].value);
   GetCallList();
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
         selectElement('definition_id', 0);
      }      
   });
}

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function newCall() {
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
   });
   
   
   const url = `/newCall`;
   console.log(url);
	$.ajax({
      dataType: 'json',
		url:url,
		type: 'GET',
		success: function(response) {
         
         console.log('success');
			$('body').html(response.html);
         //console.log(response.fragments);
         // Show plus and minus buttons on last fragment only
         document.getElementById('plus_button_id_1').style.display='block';
         document.getElementById('minus_button_id_1').style.display='block';
         selectElement('start_formation_id', 0);
         selectElement('end_formation_id', 0);
		}
	});
   
}
  function SaveFormation1() {
 

   var formData = {
     formationName: document.getElementById('formation_name_1').value,
     formationId: document.getElementById('formation_id_1').value
     };
     console.log(formData);
}
 function SaveFormation() {
   $.ajaxSetup({
      headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
   });

   var formData = {
    _token: '<?php echo csrf_token() ?>',
     formationName: document.getElementById('formation_name_1').value,
     formationId: document.getElementById('formation_id_1').value
     };

   $.ajax({
      type:'POST',
      url:'/saveFormation1',
      data:formData,
      success:function(data) {
         console.log('SaveFormation success');
         $('body').html(response.html);
      }
   });
}


function selectElement(id, valueToSelect) {    
   let element = document.getElementById(id);
   element.value = valueToSelect;
}
</script>
@endsection
