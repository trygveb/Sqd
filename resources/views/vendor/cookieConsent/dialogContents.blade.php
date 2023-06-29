<div class="js-cookie-consent cookie-consent">

    <span class="cookie-consent__message">
        
        {{__('We use functional cookies to improve user experience')}}.<br>
        {{__('By continuing to use this website, you agree to our')}}
        <a href="javascript:void(0);" onclick="document.getElementById('cookiePolicy').style.visibility='visible'" >cookiePolicy</a>

    </span>

    <button class="btn btn-primary js-cookie-consent-agree cookie-consent__agree">
        {{ __('Accept for') }} {!! config('app.cookieLifetime') !!}  {{__('days')}}
    </button>
<span id="cookiePolicy" style='visibility:hidden; '>
    <h2>Cookie Policy
         <a style="font-size:small;margin-left:20px;" href="javascript:void(0);" 
            onclick="document.getElementById('cookiePolicy').style.visibility='hidden';" >{{__('Close')}}</a>
    </h2>
@include('menu.cookiePolicyText')

</span>
</div>
