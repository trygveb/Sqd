<div class="form-group"  >
   <label for="start_formation_id">{{$startEnd}} formation(s):</label>
   <button style="float: right;" >New formation</button>
   <button style="float: right;" >Edit formation</button>
   <select class="form-control" name="{{$selectName}}" id="{{$selectName}}">
       @foreach($formationList as $key => $formation)
       @if ($mode=='edit')
       <option value="{{ $key }}" {{ $formationId == $key ? 'selected' : '' }}>
       @else
       <option value="{{ $key }}">
       @endif
           {{ $formation }}
       </option>
       @endforeach
   </select>
</div>
