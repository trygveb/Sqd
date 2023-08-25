<div class="form-group"  style="max-width:100px;">
   <label for="program_id">Program:</label>
   <select class="form-control" name="program_id" id="program_id" onchange="GetCallList()" style="max-width:300px;">
       @foreach($programList as $key => $program)
       @if ($mode=='edit')
       <option value="{{ $key }}" {{ $programId == $key ? 'selected' : '' }}>
       @else
       <option value="{{ $key }}" {{ $programId == $key ? 'selected' : '' }}>
       @endif
           {{ $program }}
       </option>
       @endforeach
   </select>
</div>
