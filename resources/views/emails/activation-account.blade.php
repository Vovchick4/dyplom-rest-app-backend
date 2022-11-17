@component('mail::message')
# {!! __('email.confirm_email') !!}

{!! __('email.tap_the_button') !!}

@component('mail::button', ['url' => $link])
{!! __('email.button_title') !!}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
