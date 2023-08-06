@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8" style="max-width:500px;">
         <fieldset>
            <legend>New call definition</legend>
             <form method="POST" action="{{ route('calls.saveCall')}}" 
               @csrf
               @if ($mode=='edit')
                  <x-call-name mode="edit" callName="{{$callName}}" callId="{{$callId}}" definitionId="{{$definition->id}}"/>                 
               @else
                 <x-call-name mode="new" callName="" callId="" definitionId=""/>
               @endif
               <x-submit-button submitText="{{__('Save')}} {{__('definition')}}" cancelText="{{ __('Cancel')}}" cancelUrl="{{route('calls.form1')}}" />
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
