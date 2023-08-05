<div class="form-group">
   <label for="call_name_1">Call name:</label>
   @if ($mode=='edit')
   <input type="text" maxlength=120 size=40 name="call_name_1" id="call_name_1" required value="{{$callName}}">
   <input type="hidden"  name="call_id_1" id="call_id_1" required value="{{$callId}}">
   <input type="hidden" name="definition_id" id="definition_id" required value="{{$definitionId}}">
   @else
   <input type="text" maxlength=120 size=40 name="call_name_1" id="call_name_1" required>
   @endif
</div>