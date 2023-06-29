@extends('layouts.app')

@section('content')
<div class="container">

    <h1>@lang('Privacy policy')</h1>
    <strong>1. @lang('Information collection')<br/>
    </strong>@lang('We collect information from you when you register on our website and when you submit the contact form. The information collected includes the name you provide and your email address, as well as your encrypted password.')
    <p>@lang('We also save your schedule status values. These are automatically erased after 12 months, or when you opt to leave the schedule.')</p>
    <p>@lang('In addition, we automatically receive and save certain information from your computer and browser, including your IP address, software and hardware information and the requested page. However, this information is not linked to your registration details, and it is erased periodically.')</p>
    <h3></h3>
    <strong>2. @lang('Use of information')<br/></strong>
    @lang('The information we collect from you is only used to contact you by e-mail when needed. We do not sell, trade, or otherwise transfer personally identifiable information to third parties.')
    <h3></h3>
    <strong>3. @lang('Schedules')<br/></strong>
    <ul>
        <li>@lang('Anyone can sign up for a schedule.')
    <li>@lang('The administrator who creates a schedule can decide whether the schedule should be password protected. In that case, you must enter the password when registering for the schedule.')
    <li>@lang('Members only have access to the schedules they have signed up for. Non-members have not access to any schedule.')
    </ul>
    <h3></h3>
    <strong>4. @lang('Schedule administrators')<br/></strong>
    @lang('The person or persons who are administrators of a schedule have access to all participants email addresses. An administrator can grant administrator authority to any member who is registered on the schedule.')
    <h3></h3>
    <strong>5. @lang('Information protection')<br/></strong>
    @lang('We use advanced encryption methods to protect sensitive data transmitted over the Internet.')
    <h3></h3>
    <strong>6. @lang('Cookies')<br/></strong>
     @include('menu.cookiePolicyText')
    <h3></h3>
    <strong>7. @lang('Consent')<br/></strong>
    @lang('By continuing to use this website, you agree to this') @lang('Privacy policy')
    <br><br>
   <a style="margin-left:5px;" href="javascript:history.back()" class="btn btn-secondary"> {{ __('Back')}}</a>

</div>

</section> <!-- end bricks -->

@endsection
