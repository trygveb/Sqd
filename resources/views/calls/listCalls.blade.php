 @extends('layouts.app')

@section('content')

<h1>List of generated call definitions</h1>
@foreach($mp3Files as $mp3File)
<a href="{{$mp3File}}">{{$mp3File}}</a><br>
@endforeach

@endsection
@section('scripts')
<script>

</script>
@endsection
