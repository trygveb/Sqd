@if ($visible)
<div id="{{$divName}}" class="form-group">
@else
<div id="{{$divName}}" class="form-group"  style="display: none;">
@endif
   <label for="{{$selectName}}">Call text fragment {{$seqNo}}:</label>
   @if ($seqNo<6)
      <button id="{{$buttonName}}" style="float:right;" onclick="plusClicked({{$seqNo}});return false;">+</button>
   @endif
   <select class="form-control" name="{{$selectName}}" id="{{$selectName}}" >
       @foreach($fragmentList as $key => $fragmentText)
       <option value="{{ $key }}">
           {{ $fragmentText }}
       </option>
       @endforeach
         
   </select>

</div>
