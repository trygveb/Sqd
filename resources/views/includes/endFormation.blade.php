<div class="form-group"  >
   <label for="end_formation_id">End formation(s):</label>
   <select class="form-control" name="end_formation_id" id="end_formation_id">
       @foreach($formationList as $key => $formation)
       <option value="{{ $key }}" style="max-width:200px;">
           {{ $formation }}
       </option>
       @endforeach
   </select>
</div>
