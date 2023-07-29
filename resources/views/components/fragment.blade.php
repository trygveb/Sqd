<div class="form-group"  >
   <label for="{{$htmlName}}">Call text fragment {{$seqNo}}:</label>
   <select class="form-control" name="{{$htmlName}}" id="{{$htmlName}}">
       @foreach($fragmentList as $key => $fragmentText)
       <option value="{{ $key }}">
           {{ $fragmentText }}
       </option>
       @endforeach
   </select>
</div>
