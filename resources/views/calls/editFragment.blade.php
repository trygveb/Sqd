@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8" style="max-width:500px;">
         <fieldset>
@include('includes.reportError') 
            @if ($mode=='edit')
            <legend>Edit fragment text {{$definitionId}}</legend>
            @else
            <legend>New fragment {{$definitionId}}</legend>
            @endif
            
            <form method="POST" action="{{ route('calls.saveFragment')}}" >
            @csrf
            @if ($mode=='edit')
               <x-fragment-text mode="edit" fragmentText="{{$fragmentText}}" fragmentId="{{$fragmentId}}" />
               <x-submit-button submitText="{{__('Save')}} {{__('fragment text')}}" cancelText="{{ __('Cancel')}}"
                                cancelUrl="{{route('calls.showEditDefinition', ['definition_id' => $definitionId])}}" />
            @else
               <x-fragment-text mode="new" fragmentText="" fragmentId="" />
               <x-submit-button submitText="{{__('Save')}} {{__('fragment text')}}" cancelText="{{ __('Cancel')}}"
                                cancelUrl="{{route('calls.showEditDefinition', ['definition_id' => $definitionId])}}" />
               
            @endif
               
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
