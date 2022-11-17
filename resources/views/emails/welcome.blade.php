@component('mail::message')
# {!! __('email.welcome_to_the_site') !!}, {{ $user['name'] }}!

Now you can log into your personal account.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
