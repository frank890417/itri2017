歡迎您的加入，請點選下方連結認證您的帳號：<BR>
<a href="{{ $link = route('email-verification.check', $user->verification_token) . '?email=' . urlencode($user->email) }}">{{ $link }}</a>