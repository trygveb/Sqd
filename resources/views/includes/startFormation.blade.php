<div class="form-group"  >
   <label for="start_formation_id">Start formation(s):</label>
   <select class="form-control" name="start_formation_id" id="start_formation_id">
       @foreach($formationList as $key => $formation)
       <option value="{{ $key }}">
           {{ $formation }}
       </option>
       @endforeach
   </select>
</div>
