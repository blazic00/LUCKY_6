<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>

</head>
<body>

<h1>Admin page</h1>
<nav>
    <a href="{{ route('admin.users.index') }}">Clients</a>
    <a href="{{ route('admin.tickets.index') }}">Tickets</a>
    <!-- Example logout button in your view -->
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</nav>

</body>
</html>
