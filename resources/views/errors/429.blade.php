@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
            <div class="card">

                <div class="card-body">
                    {{__('Sorry, you have made too many reuests in one minute. Please wait a while before retrying')}}.<br>
                    {{ __('Please select application') }} <a class="aMain" href="{{ route('calls.guest',['application' => 'sdCalls']) }}"> SdCalls </a>
                    {{ __('or')}}
                    <a class="aMain" href="{{ route('schedule.home',['application' => 'schedule']) }}"> SdSchema!</a>
                </div>
            </div>
    </div>
</div>
@endsection
