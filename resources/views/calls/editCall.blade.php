@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8" style="max-width:500px;">
         <fieldset>
            <legend>Edit call</legend>
            <form method="POST" action="{{ route('calls.saveCall')}}" >
            @csrf
              <div class="form-group">
                  <label for="call_name_1">Call name:</label>
                  <input type="text" maxlength=120 size=40 name="call_name_1" id="call_name_1" required value="{{$callName}}">
                  <input type="hidden"  name="call_id_1" id="call_id_1" required value="{{$callId}}">
                  <input type="hidden" name="definition_id" id="definition_id" required value="{{$definition->id}}">
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

</script>
@endsection
