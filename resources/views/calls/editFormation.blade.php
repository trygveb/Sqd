@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8" style="max-width:500px;">
         <fieldset>
@include('includes.reportError') 
            @if ($mode=='edit')
            <legend>Edit formation name</legend
            @else
            <legend>New formation</legend
            @endif
            
            <form method="POST" action="{{route('calls.saveFormation')}}" >
            @csrf
            @if ($mode=='edit')
               <x-formation-name mode="edit" formationName="{{$formationName}}" formationId="{{$formationId}}" />
            @else
               <x-formation-name mode="new" formationName="" formationId="" />
            @endif
               
               <x-submit-button onclickFunction="SaveFormation()" submitText="{{__('Save')}} {{__('formation name')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{route('calls.form1')}}" />
               
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
