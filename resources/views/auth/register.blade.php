<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
</head>

<body class="@if($role == 'teacher') teacher-body @else student-body @endif">
    <div class="main-container @if($role == 'teacher') teacher-main-container @else student-main-container @endif">
        <img src="/images/logo.png" class="register-image">
        <form method="POST" action="{{ route('register', ['role' => $role]) }}" class="registerContainer">
            @csrf
            <p id="registrationWord">{{ucfirst($role)}} Registration</p>
            <div class="registerForm">
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
                        <li style="color: red;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <button id="sign-up-button">Sign Up</button>
            </div>
            @isset($user_role)
            @if($user_role == 'super admin')
            <p id="register-sentence"><span><a href="/accounts">Cancel</a></span></p>
            @else
            @endisset
            <p id="register-sentence">Already have an account? <span><a href="/">Sign In</a></span></p>
            @endif
        </form>
    </div>
</body>

</html>