<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
@if ($errors->has('login'))
    <div>
        {{ $errors->first('login') }}
    </div>
@endif
<h1>Admin Login Page</h1>
<form action="{{ route('admin.login') }}" method="POST">
    @csrf
    <!-- Add input fields for username and password -->
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
