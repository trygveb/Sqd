@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8" style="max-width:500px;">
         <fieldset>
            @if ($mode=='edit')
            <legend>Edit call definition</legend>
            @else
            <legend>New call definition</legend>
            @endif
            
            <form method="POST" action="{{ route('calls.saveCall')}}" >
            @csrf
            @if ($mode=='edit')
               <x-call-name mode="edit" callName="{{$callName}}" callId="{{$callId}}" definitionId="{{$definition->id}}"/>
               <x-program-select mode="{{$mode}}" programId="{{$programId}}" />
               <x-formation-select  mode="{{$mode}}" startEnd="Start" definitionId="{{$definition->id}}" formationId="{{$startFormationId}}" />
               <x-formation-select  mode="{{$mode}}" startEnd="End" definitionId="{{$definition->id}}" formationId="{{$endFormationId}}" />
            @foreach($definitionFragments as $definitionFragment)
               <x-fragment count="{{count($definitionFragments)}}"
                           mode="{{$mode}}"
                           seqNo="{{$definitionFragment['seq_no']}}"
                           fragmentTypeId="{{$definitionFragment['fragment_type_id']}}"
                           fragmentId="{{$definitionFragment['fragment_id']}}"
                           definitionId="{{$definition->id}}"
                           :fragmentList=$fragmentList
                           :visible=true
               />
            @endforeach
            @for ($seqNo = count($definitionFragments)+1; $seqNo <=6 ; $seqNo++)
               <x-fragment count="{{count($definitionFragments)}}"
                           mode="{{$mode}}"
                           seqNo="{{$seqNo}}"
                           fragmentId="0"
                           definitionId="{{$definition->id}}"
                           fragmentTypeId="1"
                           :fragmentList=$fragmentList
                           :visible=false
               />
            @endfor
            @else
               <x-call-name mode="new" callName="" callId="" definitionId=""/>
               <x-program-select mode="{{$mode}}" program_id="" />
               <x-formation-select  mode="{{$mode}}" definitionId="" startEnd="Start" formationId="" />
               <x-formation-select  mode="{{$mode}}" definitionId="" startEnd="End" formationId="" />
               <x-fragment count=1
                           mode="{{$mode}}"
                           seqNo="1"
                           fragmentId="0"
                           definitionId="{{$definition->id}}"
                           fragmentTypeId="1"
                           :fragmentList=$fragmentList
                           :visible=true
                />
            @for ($seqNo = 2; $seqNo <=6 ; $seqNo++)
               <x-fragment count="1"
                           mode="{{$mode}}"
                           seqNo="{{$seqNo}}"
                           fragmentId="0"
                           definitionId="{{$definition->id}}"
                           fragmentTypeId="1"
                           :fragmentList=$fragmentList
                           :visible=false
               />
            @endfor
            @endif
            <p style="margin-left: auto; margin-right: auto;display:table;" >
               <a href="{{ route('calls.showNewFragment',['definition_id' => $definition->id]) }}" class="btn btn-secondary" >New fragment</a>
               <a href="{{ route('calls.showNewFormation', ['definition_id' => $definition->id]) }}" class="btn btn-secondary" >New formation</a>
            </p>
               <br>
               <x-submit-button submitText="{{__('Save')}} {{__('definition')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{route('calls.showForm1')}}" />
               
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
   function minusClicked(seqNo) {
      console.log('minusClicked '+seqNo);
      var fragmentElement= document.getElementById('fragment_id_' + seqNo);
      fragmentElement.value=0;
      document.getElementById('div_id_' + (seqNo)).style.display='none';
      document.getElementById('plus_button_id_' + (seqNo-1)).style.display='block';
      document.getElementById('minus_button_id_' + (seqNo-1)).style.display='block';
   }
   function plusClicked(seqNo) {
      console.log('plusClicked '+seqNo);
      document.getElementById('div_id_' + (seqNo+1)).style.display='block';
      document.getElementById('plus_button_id_' + (seqNo)).style.display='none';
      document.getElementById('minus_button_id_' + (seqNo)).style.display='none';
      document.getElementById('plus_button_id_' + (seqNo+1)).style.display='block';
      document.getElementById('minus_button_id_' + (seqNo+1)).style.display='block';
   }


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
