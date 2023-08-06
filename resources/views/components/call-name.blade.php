<div class="form-group">
   <label for="call_name_1">Call name:</label>
   @if ($mode=='edit')
   <input type="text" maxlength=120 size=40 name="call_name_1" id="call_name_1" required value="{{$callName}}">
   <input type="hidden"  name="call_id_1" id="call_id_1" required value="{{$callId}}">
   <input type="hidden" name="definition_id" id="definition_id" required value="{{$definitionId}}">
   @else
   <input type="hidden"  name="call_id_1" id="call_id_1" required value="0">
   <input type="text" list="calls" maxlength=120 size=45  name="call_name_1" id="call_name_1">
   <datalist id="calls">
        @foreach($callList as $key => $callName)
       <option value="{{ $callName }}">
       @endforeach
   </datalist>
   @endif
</div>