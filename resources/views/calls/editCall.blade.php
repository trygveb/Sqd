@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8" style="max-width:500px;">
         <fieldset>
            <legend>Edit call</legend>
            <form method="POST" action="{{ route('calls.saveCall')}}" >
            @csrf
<x-call-name mode="{{$mode}}" callName="{{$callName}}" callId="{{$callId}}" definitionId="{{$definition->id}}"/>
<x-program-select mode="{{$mode}}" />
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
