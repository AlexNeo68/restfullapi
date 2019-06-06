@component('mail::message')
# Hello {{ $user->name }}!

You changed email. Please confirm it going to link below

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Confirm
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
