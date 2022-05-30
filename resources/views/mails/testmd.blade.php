@component('mail::message')
# Welcome {{ $user->name }}



@component('mail::button', ['url' => route('login')])
Please Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
