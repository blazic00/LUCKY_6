
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Main Page</title>

</head>
<body>

<h1>Main page</h1>
<nav>
    <a href="{{ route('lucky_6') }}">LUCKY 6</a>
    <a href="{{ route('tickets', ['user_id' => Auth::id()]) }}">History</a>
    <!-- Example logout button in your view -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>

    </form>
</nav>

</body>
</html>
