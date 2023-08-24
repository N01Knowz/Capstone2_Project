<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/login.css">
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
        <div class="loginContainer">
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
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>
            </div>
            <p id="register-sentence">New to ADA? <span><a href="register">Sign Up</a></span></p>
        </div>
    </div>
</body>
</html>