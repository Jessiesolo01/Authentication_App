<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
</head>
<body>
    <p>We will send a link to your email, use that link to reset password</p>
    <form action="{{ route('reset.password.post') }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="token" hidden value="{{$token}}">
        <br>
        {{-- <input type="email" name="email" value="{{$request->resetemail}}">
        <input type="email" name="resetemail" value="{{$email}}"> --}}
        <input type="email" name="resetpassword_email" placeholder="re-enter the email">
        <input type="password" name="resetpassword" placeholder="enter new password">
        <input type="password" name="resetpassword_confirmation" placeholder="confirm password">
        <button>Submit</button>
    </form>
</body>
</html>

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
</head>
<body>
    <p>We will send a link to your email, use that link to reset your password.</p>
    <form action="{{ route('reset.password.post') }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="token" hidden value="{{ $token }}">
        
        {{-- Email Input --}}
        {{-- <input type="email" name="resetpassword_email" placeholder="Re-enter the email" value="{{ old('resetpassword_email') }}">
        @error('resetpassword_email')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        
        {{-- Password Input --}}
        {{-- <input type="password" name="resetpassword" placeholder="Enter new password">
        @error('resetpassword')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror --}}
        
        {{-- Confirm Password Input --}}
        {{-- <input type="password" name="resetpassword_confirmation" placeholder="Confirm password">
        @error('resetpassword_confirmation')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        
        <button>Submit</button>
    </form>
</body>
</html> --}} 