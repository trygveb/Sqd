@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8" style="max-width:500px;">
         <fieldset>
            <legend>Edit call</legend>
            <form method="POST" action="{{ route('calls.saveCall',['data' => 1])}}">
            @csrf
         </fieldset>
      </div>
   </div>
</div>
@endsection
