@if ($visible)
<div id="{{$divName}}" class="form-group">
@else
<div id="{{$divName}}" class="form-group"  style="display: none;">
@endif
   <label for="{{$selectName}}">Call text fragment {{$seqNo}}:</label>
   <input type="checkbox" id="{{$checkbox1Name}}" name="{{$checkbox1Name}}" style="margin-left:20px;">
   <label for="{{$checkbox1Name}}">Secondary</label>
   @if ($seqNo==$count)
      <button id="{{$plusButtonName}}" style="float:right;" onclick="plusClicked({{$seqNo}});return false;">+</button>
      <button id="{{$minusButtonName}}" style="float:right;" onclick="minusClicked({{$seqNo}});return false;">-</button>
   @endif
   <select class="form-control" name="{{$selectName}}" id="{{$selectName}}" >
      <option value="0">
      </option>
       @foreach($fragmentList as $key => $fragmentText)
       @if ($mode=='edit')
         <option value="{{ $key }}" {{ $fragmentId == $key ? 'selected' : '' }}>
       @else
         <option value="{{ $key }}">
       @endif
           {{ $fragmentText }}
       </option>
       @endforeach
         
   </select>

</div>
