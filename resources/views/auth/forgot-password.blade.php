<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="css/forgot-password.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="forgot-password-container">
        <img src="/images/logo.png" class="login-image">
        <div class="email-message">
            {{ __('Let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>
        @if(session('status'))
        <script>
            alert("Reset link has been sent.");
        </script>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="forgot-password-form">
            @csrf

            <!-- Email Address -->
            <div class="email-input-container">
                <label for="email" class="email-label">Email:</label>
                <div class="flex-input">
                    <input id="email" class="email-input" type="email" name="email" :value="old('email')" required autofocus />
                </div>
            </div>
            @error('email')
            <div style="color: red;">{{ $message }}</div>
            @enderror
            <div class="buttons-container">
                <button class="password-reset-button">
                    {{ __('Send Reset Link') }}
                </button>
                <a href="/login">
                    <button type="button" class="cancel-button">
                        Cancel
                    </button>
                </a>
            </div>
        </form>
    </div>
</body>

</html>