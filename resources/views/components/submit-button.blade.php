<p style="margin-left: auto; margin-right: auto;display:table;" >
<button type="submit" class="btn btn-primary" id="{{$myId ?? ''}}" onclick="{{$onclickFunction ?? ''}}" > 
    {{$submitText}} 
</button>
<a style="margin-left:5px;" href="{{$cancelUrl}}" class="btn btn-secondary"> {{$cancelText}}</a>
</p>
