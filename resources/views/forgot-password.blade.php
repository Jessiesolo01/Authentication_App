<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
</head>
<body>
    <p>We will send a link to your email, use that link to reset password</p>
    <form action="{{ route("forgot.password.post") }}" method="POST">
        @csrf
        <input type="email" name="resetemail" placeholder="enter your email">
        <button>Submit</button>
    </form>
</body>
</html>