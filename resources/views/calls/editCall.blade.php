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
            @else
               <x-call-name mode="new" callName="" callId="" definitionId=""/>
            @endif
               <x-program-select mode="{{$mode}}" />
   @include('includes.startFormation')
   @include('includes.endFormation')
   @include('includes.fragments')
               <x-submit-button submitText="{{__('Save')}} {{__('definition')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{route('calls.form1')}}" />
               
            </form>  
         </fieldset>
      </div>
   </div>
</div>
@endsection
@section('scripts')
<script>

</script>
@endsection
