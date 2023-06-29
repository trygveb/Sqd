@extends('layouts.app')
@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="card" style="max-width:800px;">
         <div class="card-header">{{__('Cookie Policy')}}</div>
         <div class="card-body">
            @include('menu.cookiePolicyText')
         </div>
      
            <a  href="{{ url()->previous() }}" class="btn btn-secondary"> {{__('Back')}}</a>
       
      </div>
   </div>       
</div>
<!--</div>-->

@endsection
@section('scripts')

@endsection