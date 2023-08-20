@if ($visible)
<div id="{{$divName}}" class="form-group">
@else
<div id="{{$divName}}" class="form-group"  style="display: none;">
@endif
   <label for="{{$selectName}}" style="margin-right:10px;">Text {{$seqNo}}:</label>
   <label for="{{$checkbox1Name}}">Secondary</label>
   <input type="checkbox" id="{{$checkbox1Name}}" name="{{$checkbox1Name}}" style="margin-left:2px;" {{ $fragmentTypeId == 2 ? 'checked' : '' }}>
   
   <label for="{{$selectSeparatorName}}" style="margin-left:10px;" >Separator:</label>
   <select name="{{$selectSeparatorName}}" id="{{$selectSeparatorName}}" >
      <option value="." {{ $separator == '.' ? 'selected' : '' }}>Point</option>
      <option value=";" {{ $separator == ';' ? 'selected' : '' }}>Semikolon</option>
      <option value="," {{ $separator == ',' ? 'selected' : '' }}>Komma</option>
      <option value=" " {{ $separator == ' ' ? 'selected' : '' }}>None</option>
   </select>
   
   
      <button id="{{$plusButtonName}}" style="float:right;display:{{ $seqNo == $count ? 'block' : 'none' }}" onclick="plusClicked({{$seqNo}});return false;">+</button>
      <button id="{{$minusButtonName}}" style="float:right;display:{{ $seqNo == $count ? 'block' : 'none' }}" onclick="minusClicked({{$seqNo}});return false;">-</button>
   <a href="{{ route('calls.showEditFragment', ['fragmentId' => $fragmentId, 'definitionId' => $definitionId]) }}"
      style="float:right;margin-right:5px;">Edit</a>
   
   <select class="form-control" name="{{$selectName}}" id="{{$selectName}}" >
      <option value="0">
      </option>
       @foreach($fragmentList as $key => $fragmentText)
       @if ($mode=='edit')
         <option value="{{ $key }}" {{ $fragmentId == $key ? 'selected' : '' }}>
       @else
         <option value="{{ $key }}">
       @endif
       @if ($fragmentTypeId==2)
          ( {{ $fragmentText }} )
       @else
           {{ $fragmentText }}
       @endif
       </option>
       @endforeach
         
   </select>

</div>
