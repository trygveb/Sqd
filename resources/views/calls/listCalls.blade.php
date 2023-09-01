 @extends('layouts.app')

@section('content')

<h1>List of generated call definitions</h1>
@foreach($mp3Files as $mp3File)
@php
$mp3FileText=substr($mp3File, strpos($mp3File, '/', 1)+1);
@endphp
<a href="{{$mp3File}}">{{$mp3FileText}}</a><br>
@endforeach

@endsection
@section('scripts')
<script>

</script>
@endsection
