<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <h1>Log In</h1>
    <form action="/login" method="POST">
        @csrf
        <input type="text" placeholder="email" name="loginemail">
        <input type="password" placeholder="password" name="loginpassword">
        <button>Login</button>
        <br>
        <br>
        <a href="{{ route('forgot.password') }}">Forgot password?</a>
        <br>
        <a href="{{ route('signup') }}">Don't have an account yet?</a>
    </form>
</body>
</html>