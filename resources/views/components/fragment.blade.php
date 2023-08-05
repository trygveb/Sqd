@if ($visible)
<div id="{{$divName}}" class="form-group">
@else
<div id="{{$divName}}" class="form-group"  style="display: none;">
@endif
   <label for="{{$selectName}}">Call text fragment {{$seqNo}}:</label>
   <input type="checkbox" id="{{$checkbox1Name}}" name="{{$checkbox1Name}}" style="margin-left:20px;">
   <label for="{{$checkbox1Name}}">Secondary</label>
   @if ($seqNo<6)
      <button id="{{$minusButtonName}}" style="float:right;display: none;" onclick="minusClicked({{$seqNo}});return false;">-</button>
      <button id="{{$plusButtonName}}" style="float:right;display: none;" onclick="plusClicked({{$seqNo}});return false;">+</button>
   @endif
   <select class="form-control" name="{{$selectName}}" id="{{$selectName}}" >
      <option value="0">
      </option>
       @foreach($fragmentList as $key => $fragmentText)
       <option value="{{ $key }}">
           {{ $fragmentText }}
       </option>
       @endforeach
         
   </select>

</div>
