<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin User Page</title>
</head>
<body>

<a href="{{route('admin.index')}}">Navigate to main admin page</a>
<h1>All Users</h1>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->created_at }}</td>
            <td>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">No users found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
<a href="{{ route('admin.users.create') }}">+ Add New User</a>
</body>
</html>
