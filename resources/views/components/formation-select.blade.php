<div class="form-group"  >
   <label for="start_formation_id">{{$startEnd}} formation(s):</label>
   <a href="{{ route('calls.showNewFormation') }}" style="float: right;">New formation</a>
   <a id="showEditFormationLink" href="{{route('calls.showEditFormation', ['formation_id' =>$formationId, 'definition_id' => $definitionId])}}"
      style="float: right;margin-right:10px;">Edit formation</a>
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
