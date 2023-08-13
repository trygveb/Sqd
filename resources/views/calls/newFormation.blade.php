@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8" style="max-width:500px;">
         <fieldset>
             <legend>New Formation</legend>
            <form method="POST" action="{{ route('calls.saveFormation')}}">

            @csrf
               <div class="form-group">
                  <label for="call_name">Formation name:</label>
                  <input class="form-control" name="formation_name" id="formation_name">
               </div>
               <x-submit-button submitText="{{__('Save')}} {{__('formation')}}" cancelText="{{ __('Cancel')}}"
                                cancelUrl="{{route('calls.showForm1', ['data' => 1])}}" />

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
