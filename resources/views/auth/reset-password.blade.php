<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="{{ asset('css/reset-password.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="reset-password-container">
        <img src="/images/logo.png" class="login-image">

        <form method="POST" action="{{ route('password.store') }}" class="reset-password-form">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="">
                <label for="email" class="label">Email: {{$request->email}}</label>
                <input id="email" class="block mt-1 w-full" type="hidden" name="email" value="{{$request->email}}" required autocomplete="username" />
            </div>
            @error('email')
            <div style="color: red;">{{ $message }}</div>
            @enderror
            <!-- Password -->
            <div class="input-container">
                <label for="password" class="label">Password:</label>
                <div class="flex-input">
                    <input id="password" class="input" type="password" name="password" required autocomplete="new-password">
                </div>
            </div>
            @error('password')
            <div style="color: red;">{{ $message }}</div>
            @enderror
            <!-- Confirm Password -->
            <div class="input-container">
                <label for="password_confirmation" class="label">Confirm Password:</label>
                <div class="flex-input">
                    <input id="password_confirmation" class="input" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>
            @error('password_confirmation')
            <div style="color: red;">{{ $message }}</div>
            @enderror
            <div>
                <button class="reset-button">
                    Reset Password
                </button>
            </div>
        </form>

    </div>
</body>

</html>