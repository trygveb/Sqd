<div class="form-group">
   <label for="formation_name_1">Call name:</label>
   @if ($mode=='edit')
   <input type="text" maxlength=120 size=40 name="formation_name_1" id="formation_name_1" required value="{{$formationName}}">
   <input type="hidden"  name="formation_id_1" id="formation_id_1" required value="{{$formationId}}">
   <input type="hidden" name="definition_id" id="definition_id" required value="{{$definitionId}}">
   @else
   <input type="hidden"  name="formation_id_1" id="formation_id_1" required value="0">
   <input type="text" list="formations" maxlength=120 size=45  name="formation_name_1" id="formation_name_1">
   <datalist id="formations">
        @foreach($formationList as $key => $formationName)
       <option value="{{ $formationName }}">
       @endforeach
   </datalist>
   @endif
</div>