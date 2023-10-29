<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="loginContainer">
        <img src="/images/logo.png" class="login-image">
        <p id="loginWord">Login</p>
        <p id="welcomeSentence">Welcome to ADA!</p>
        <div class="form-container">
            <form method="POST" action="{{ route('login') }}" class="loginForm">
                @csrf
                <p id="label">Email</p>
                <input type="text" class="inputVariables" name="email">
                <p id="label">Password</p>
                <input type="password" class="inputVariables" name="password">
                <button id="sign-in-button">Sign In</button>
                @if ($errors->any())
                <div class="alert alert-danger" style="color: red;">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </form>
            <a href="/forgot-password">
                <p class="forgot-password-sentence">Forgot password?</p>
            </a>
        </div>
        <p id="register-sentence">New to ADA? <span><a href="register">Sign Up</a></span></p>
    </div>
    @if(session('password_changed'))
    <script>
        var message = "{{ session('password_changed') }}";
        var title = "Successfully Changed Password";
        alert(title + "\n\n" + message);
    </script>
    @endif
    @if(session('reset_success'))
    <script>
        var message = "{{ session('reset_success') }}";
        alert(message);
    </script>
    @endif
</body>

</html>