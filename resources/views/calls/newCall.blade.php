@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8" style="max-width:500px;">
         <fieldset>
             <legend>New Call</legend>
            <form method="POST" action="{{ route('calls.saveCall',['data' => 1])}}">

            @csrf
               <div class="form-group">
                  <label for="call_name">Call name:</label>
                  <input class="form-control" name="call_name" id="call_name">
               </div>
          
              
@include('includes.program')
@include('includes.startFormation')
@include('includes.endFormation')
               <x-fragment seqNo="1" :fragmentList=$fragmentList :visible=true />
               <x-fragment seqNo="2" :fragmentList=$fragmentList :visible=false />
               <x-fragment seqNo="3" :fragmentList=$fragmentList :visible=false />
               <x-fragment seqNo="4" :fragmentList=$fragmentList :visible=false />
               <x-fragment seqNo="5" :fragmentList=$fragmentList :visible=false />
               <x-fragment seqNo="6" :fragmentList=$fragmentList :visible=false />
               <x-submit-button submitText="{{__('Add')}} {{__('call')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{route('calls.newCall', ['data' => 1])}}" />

            </form>
         
         </fieldset>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function GetCallList() {
// Called from includes.program when user changes program
// Not used in this form
}
function plusClicked(seqNo) {
   console.log('plusClicked '+seqNo);
   document.getElementById('div_id_' + (seqNo+1)).style.display='block';
   document.getElementById('button_id_' + (seqNo)).style.display='none';
}
</script>
@endsection
