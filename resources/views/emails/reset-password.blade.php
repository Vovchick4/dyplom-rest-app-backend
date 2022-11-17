@component('mail::message')
# Reset Your Password

{!! __('email.your_new_password') !!}: {{ $password }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
