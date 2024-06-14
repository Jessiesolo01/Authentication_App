<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
    <h1>Sign Up</h1>
    <form action="/signup" method="POST">
        @csrf
        <input type="text" placeholder="name" name="name">
        <input type="text" placeholder="email" name="email">
        <input type="password" placeholder="password" name="password">
        <button>Register</button>
        <br>
        <br>
        <a href="{{ route('signin') }}">Already created an account?</a>
    </form>
</body>
</html>