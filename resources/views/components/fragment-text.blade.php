<div class="form-group">
   <label for="fragment_name">Fragment text:</label>
   @if ($mode=='edit')
   <input type="text" maxlength=120 size=40 name="fragment_text" id="fragment_text" required value="{{$fragmentText}}">
   <input type="hidden"  name="fragment_id" id="fragment_id" required value="{{$fragmentId}}">
   @else
   <input type="hidden"  name="fragment_id" id="fragment_id" required value="0">
   <input type="text" list="fragments" maxlength=120 size=45  name="fragment_text" id="fragment_text">
   <datalist id="fragments">
        @foreach($fragmentList as $key => $fragmentText)
       <option value="{{ $fragmentText }}">
       @endforeach
   </datalist>
   @endif
</div>