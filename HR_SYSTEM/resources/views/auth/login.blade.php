<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container">
        <div class="left">
            <div class="form-wrapper">
                <div class="logo">
                    <img src="{{ asset('company/logo.png') }}" alt="Logo">
                </div>

                <h2>LOG IN FORM</h2>

                @if ($errors->any())
                    <div class="error-messages">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ url('login') }}">
                    @csrf

                    <label>Email:</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>

                    <label>Password:</label>
                    <input type="password" name="password" required>

                    <button type="submit">Login</button>

                    <div class="forgot">
                        <a href="#">Forgot password?</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="right">
            <img src="{{ asset('company/side-photo.jpg') }}" alt="Side Image" class="side-image">
        </div>
    </div>
</body>
</html>
