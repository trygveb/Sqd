<fieldset>
     <legend>Call</legend>
 @include('includes.program') 
     <div class="form-group"  style="max-width:400px;">
         <label for="definition_id">Call:</label>
         @if ($user->isCallsAdministrator())
          <button style="float: right;" onclick="window.location='{{ URL::route('calls.newCall'); }}'">New call</button>
          <button style="float: right;" onclick="window.location='{{ URL::route('calls.editCall'); }}'">Edit call</button>
         @endif
         <select class="form-control" name="definition_id" id="definition_id" >
             @foreach($calls as $call)
             <option value="{{ $call->definition_id }}" {{ old('call', $user->call) == $call->definition_id ? 'selected' : '' }}>
                 {{ $call->call_name }} from {{ $call->start_formation_name }}
             </option>
             @endforeach
         </select>
        
     </div>

     <br>
      <p id="startFormation" style="width:400px;height:50px;background-color: #333;">
       <br><br>
       <div  style="margin-left:auto; margin-right:auto; height: 100px; overflow-y: scroll;">
      <p id="callText" style="width:400px;height:100px;background-color: #333;">
       </div>
       <br><br>
      <p id="endFormation" style="width:400px;height:50px;background-color: #333;">
      </p>

      <p id="file_name"></p>
      <button class="btn btn-primary" onClick="getCallText()">Get Call text</button>

</fieldset>
