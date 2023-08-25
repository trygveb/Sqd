<div class="form-group"  style="max-width:100px;">
   <label for="program_id">Program:</label>
   <select class="form-control" name="program_id" id="program_id" onchange="programIdChanged(this)" style="max-width:300px;">
       @foreach($programList as $key => $program)
       <option value="{{ $key }}" >
           {{ $program }}
       </option>
       @endforeach
   </select>
</div>
