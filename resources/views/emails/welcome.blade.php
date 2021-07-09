Hello {{$user->name}},

Thankyou for creating an account. Please verify your email using this:
{{ route('verify', ['token'=>$user->verification_token]) }}
