@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ __('Please select application') }} <a class="aMain" href="{{ route('calls.home',['application' => 'sdCalls']) }}"> SdCalls </a>
                    {{ __('or')}}
                    <a class="aMain" href="{{ route('schedule.home',['application' => 'schedule']) }}"> SdSchema!</a>
                </div>
            </div>
    </div>
</div>
@endsection
