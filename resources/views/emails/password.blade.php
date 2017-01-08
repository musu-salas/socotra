Hi {{ $user->first_name }},<br>
We have received a request to reset your password at {{ config('custom.code') }}<em>!</em><br><br>

If you really forgot the password to access your account, please follow this link to reset access:<br>
&nbsp;- <a href="{{ $link = url('/password/reset', $token).'?email=' . urlencode($user->getEmailForPasswordReset()) }}">{{ $link }}</a>

<br><br>
Otherwise, feel free to delete this email.

<br><br>
Your <a href="{{ url('') }}">{{ config('custom.code') }}</a> with &hearts;