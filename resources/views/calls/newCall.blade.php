@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8" style="max-width:450px;">
         <fieldset>
            <form method="POST" action="{{ route('calls.saveNewCallName',['data' => 1])}}">
            @csrf
            <fieldset>
               <legend>New Call name</legend>
           
         
               <div class="form-group">
                  <label for="call_name">Call name:</label>
                  <input class="form-control" name="call_name" id="call_name">
               </div>
             <x-submit-button submitText="{{__('Add')}} {{__('call name')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{route('calls.newCall', ['data' => 1])}}" />
            </form>
            </fieldset>
            <form method="POST" action="{{ route('calls.saveNewCallName',['data' => 1])}}">
            @csrf
            <fieldset>
               <legend>Modify call</legend>
               <div class="form-group"  style="max-width:450px;">
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
            </form>
            </fieldset>
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
