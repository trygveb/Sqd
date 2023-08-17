<div class="form-group">
   <label for="formation_name_1">Formation name:</label>
   @if ($mode=='edit')
   <input type="text" maxlength=120 size=40 name="formation_name" id="formation_name" required value="{{$formationName}}">
   <input type="hidden"  name="formation_id" id="formation_id" required value="{{$formationId}}">
   @else
   <input type="text"  name="formation_id" id="formation_id" required value="0">
   <input type="text" list="formations" maxlength=120 size=45  name="formation_name" id="formation_name">
   <datalist id="formations">
        @foreach($formationList as $key => $formationName)
       <option value="{{ $formationName }}">
       @endforeach
   </datalist>
   @endif
</div>