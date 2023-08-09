<fieldset>
     <legend>Call</legend>
 @include('includes.program') 
     <div class="form-group">
         <label for="definition_id">Call:</label>
        
         @if ($user->isCallsAdministrator())
          <!--<button style="float: right;"  onclick="newCall()">New definition</button>-->
           <a href="{{ route('calls.showNewCall') }}" style="float: right;">New definition</a>
           <a id="showEditDefinitionLink" href="/editDefinition/{{$user->definition_id}}" style="float: right;margin-right:10px;">Edit definition</a>
           
           <!--class="btn btn-primary"-->
<!--          <button style="float: right;" onclick="editCall()" >Edit definition</button>-->
         @endif
         <select onchange="definition_id_changed()" class="form-control" name="definition_id" id="definition_id" >
             @foreach($vCallDefs as $vCallDef)
             <option value="{{ $vCallDef->definition_id }}" {{ old('call', $user->call) == $vCallDef->definition_id ? 'selected' : '' }}>
                 {{ $vCallDef->call_name }} from {{ $vCallDef->start_formation_name }}
             </option>
             @endforeach
         </select>
        
     </div>

     <br>
      <p id="startFormation" style="height:50px;background-color: #333;">
       <br><br>
       <div  style="margin-left:auto; margin-right:auto; height: 100px; overflow-y: scroll;">
      <p id="callText" style="height:100px;background-color: #333;">
       </div>
       <br><br>
      <p id="endFormation" style="height:50px;background-color: #333;">
      </p>

      <p id="file_name"></p>
      <button class="btn btn-primary" onClick="getCallText()">Get Call text</button>

</fieldset>

