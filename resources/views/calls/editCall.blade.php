@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8" style="max-width:500px;">
         <fieldset>
            <legend>Edit call {{$definition->program_id}}-{{$definition->call_id}}-{{$definition->start_end_formation_id}}</legend>
            <form method="POST" action="{{ route('calls.saveCall',['data' => 1])}}" >
            @csrf
              <div class="form-group"  style="max-width:500px;">
                  <label for="program_id">Call:</label>
                  <select class="form-control" name="call_id" id="call_id" >
                      @foreach($calls as $call)
                      <option value="{{ $call->id }}"}}>
                          {{ $call->name }}
                      </option>
                      @endforeach
                  </select>
               </div>
@include('includes.program')
@include('includes.startFormation')
@include('includes.endFormation')
@include('includes.fragments')

               <x-submit-button submitText="{{__('Save')}} {{__('call')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{route('calls.form1')}}" />
               
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
