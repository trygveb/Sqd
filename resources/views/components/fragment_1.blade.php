@if ($visible)
<div id="{{$divName}}" class="form-group">
@else
<div id="{{$divName}}" class="form-group"  style="display: none;">
@endif
   <label for="{{$selectName}}">Call text fragment {{$seqNo}}:</label>
   <input type="checkbox" id="{{$checkbox1Name}}" name="{{$checkbox1Name}}" style="margin-left:20px;" {{ $fragmentTypeId == 2 ? 'checked' : '' }}>
   <label for="{{$checkbox1Name}}">Secondary</label>
      <button id="{{$plusButtonName}}" style="float:right;display:{{ $seqNo == $count ? 'block' : 'none' }}" onclick="plusClicked({{$seqNo}});return false;">+</button>
      <button id="{{$minusButtonName}}" style="float:right;display:{{ $seqNo == $count ? 'block' : 'none' }}" onclick="minusClicked({{$seqNo}});return false;">-</button>
   @if ($mode=='edit')
   <select class="form-control" name="{{$selectName}}" id="{{$selectName}}" >
       @foreach($fragmentList as $key => $fragmentText)
       <option value="{{ $key }}" {{ $fragmentId == $key ? 'selected' : '' }}>
       @if ($fragmentTypeId==2)
          ( {{ $fragmentText }} )
       @else
           {{ $fragmentText }}
       @endif
       </option>
       @endforeach
   </select>
   @else
   <input type="text" list="fragments" maxlength=120 size=45  name="fragment_text" id="fragment_text">
   <datalist id="fragments">
        @foreach($fragmentList as $key => $fragmentText)
       <option value="{{ $fragmentText }}">
       @endforeach
   </datalist>
   @endif

</div>
