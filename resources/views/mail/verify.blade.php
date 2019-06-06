@component('mail::message')
# Hello {{ $user->name }}!

Thank for create account. Please verify your email going the link

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verify
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
