<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="css/verify-email.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="verify-email-container">
        <img src="/images/logo.png" class="login-image">

        <div class="email-message">
            {{ __('Before getting started, verify your email address by clicking on the link we just emailed to you. If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
        <div class="email-message">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div>
                    <button class="resend-button">
                        {{ __('Resend Verification Email') }}
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="logout-button">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>

    </div>
</body>

</html>