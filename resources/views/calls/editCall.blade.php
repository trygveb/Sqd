@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8" style="max-width:500px;">
         <fieldset>
            @if ($mode=='edit')
            <legend>Edit call definition</legend
            @else
            <legend>New call definition</legend
            @endif
            <form method="POST" action="{{ route('calls.saveCall')}}" >
            @csrf
            @if ($mode=='edit')
               <x-call-name mode="edit" callName="{{$callName}}" callId="{{$callId}}" definitionId="{{$definition->id}}"/>
               <x-program-select mode="{{$mode}}" programId="{{$programId}}" />
               <x-formation-select  mode="{{$mode}}" startEnd="Start" formationId="{{$startFormationId}}" />
               <x-formation-select  mode="{{$mode}}" startEnd="End" formationId="{{$endFormationId}}" />
            @else
               <x-call-name mode="new" callName="" callId="" definitionId=""/>
               <x-program-select mode="{{$mode}}" program_id="" />
               <x-formation-select  mode="{{$mode}}" startEnd="Start" formationId="" />
               <x-formation-select  mode="{{$mode}}" startEnd="End" formationId="" />
            @endif
            @foreach($definitionFragments as $definitionFragment)
               <x-fragment count="{{count($definitionFragments)}}" mode="{{$mode}}" seqNo="{{$definitionFragment['seq_no']}}" fragmentId="{{$definitionFragment['fragment_id']}}" :fragmentList=$fragmentList :visible=true />
            @endforeach
            @for ($seqNo = count($definitionFragments)+1; $seqNo <=6 ; $seqNo++)
               <x-fragment count="{{count($definitionFragments)}}" mode="{{$mode}}" seqNo="{{$seqNo}}" fragmentId="{{$definitionFragment['fragment_id']}}" :fragmentList=$fragmentList :visible=false />
            @endfor
               <x-submit-button submitText="{{__('Save')}} {{__('definition')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{route('calls.form1')}}" />
               
            </form>  
         </fieldset>
      </div>
   </div>
</div>
@endsection
@section('scripts')
<script>
window.onload = function() {

};
function xxx() {
        jsonFragments= [];
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
   
}
</script>
@endsection
