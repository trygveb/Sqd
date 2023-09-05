<fieldset>
     <legend>Call</legend>
 @include('includes.program') 
     <div class="form-group">
         <label for="definition_id">Call:</label>
        
         @if ($user->isCallsAdministrator())
           <a href="/newCall/1" id="new-call-href" name="new-call-href" style="float: right;">New call</a>
           <a id="showEditDefinitionLink" href="/editDefinition/{{$user->definition_id}}" style="float: right;margin-right:10px;">Edit definition</a>
         @endif
         <select onchange="definition_id_changed()" class="form-control" name="definition_id" id="definition_id" >
             @foreach($vCallDefs as $vCallDef)
             <option value="{{ $vCallDef->definition_id }}" >
                 {{ $vCallDef->call_name }} from {{ $vCallDef->start_formation_name }}
             </option>
             @endforeach
         </select>
        
     </div>
<div style="display:none;" id="div_call_text">
     <br>
      <p id="startFormation" style="height:50px;background-color: #333;">
       <br><br>
       <div  style="margin-left:auto; margin-right:auto; height: 100px; overflow-y: scroll;">
      <p id="callText" style="height:100px;background-color: #333;">
       </div>
       <br><br>
      <p id="endFormation" style="height:50px;background-color: #333;">
      </p>
</div>
      <p id="file_name"></p>
      <button class="btn btn-primary" onClick="getCallText()">Get Call text</button>
      <input style="float: right; margin-top:0.3em;" type="checkbox" name="show_call_text" id="show_call_text" 
             onClick="showCallText()">
      <label style="float:right;margin-right:10px;" class="form-check-label" for="show_call_text">Show call text</label>
</fieldset>

