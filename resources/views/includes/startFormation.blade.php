<div class="form-group"  >
   <label for="start_formation_id">Start formation(s):</label>
   <button style="float: right;"  onclick="newFormation()">New formation</button>
   <button style="float: right;" onclick="editStartFormation()" >Edit formation</button>
   <select class="form-control" name="start_formation_id" id="start_formation_id">
       @foreach($formationList as $key => $formation)
       <option value="{{ $key }}">
           {{ $formation }}
       </option>
       @endforeach
   </select>
</div>
