<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main-container">
        <div class="logoBackground">
            <div class="comLogo">
                <img src="images/logoWhite.png" id="logo">
            </div>
            <div class="photoBooks">
                <img src="images/background.png" id="background"></div>
        </div>
        <form method="POST" action="{{ route('register') }}" class="loginContainer">
            @csrf
            <p id="registrationWord">Registration</p>
            <div class="loginForm">
                <p id="label">First Name</p>
                <input type="text" name="first_name" class="inputVariables">
                <p id="label">Last Name</p>
                <input type="text" name="last_name" class="inputVariables">
                <p id="label">Email</p>
                <input type="text" name="email" class="inputVariables">
                <p id="label">Password</p>
                <input type="password" name="password" class="inputVariables">
                <p id="label">Confirm Password</p>
                <input type="password" name="password_confirmation" class="inputVariables">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <button id="sign-up-button">Sign Up</button>
            </div>
            <p id="login-sentence">Already have an account? <span><a href="/">Sign In</a></span></p>
        </form>
    </div>
</body>
</html>

