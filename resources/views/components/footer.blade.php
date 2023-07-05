<footer class="footer">
   <div class="container" style="text-align:center;  padding-bottom:20px;">
      @if ($routeRoot==='schedule')
         <span class="text-muted" >sdSchema {{config('app.env')}} &nbsp;@include('schedule.version',[])&nbsp;&nbsp;(@include('schedule.versionTime',[]))</span>
      @elseif ($routeRoot==='calls')
         <span class="text-muted" >sdCalls {{config('app.env')}}&nbsp;@include('calls.version',[])&nbsp;&nbsp;(@include('calls.versionTime',[]))</span>
      @else
         <span class="text-muted" >sqd.se {{config('app.env')}}&nbsp;@include('version',[])&nbsp;&nbsp;(@include('versionTime',[]))</span>
      @endif
@include('cookie-consent::index')
  </div>
</footer>  
