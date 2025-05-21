<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Ticket History</title>
</head>
<body>
<a href="{{route('index')}}">Navigate to main page</a>
<h1>Ticket History</h1>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>user_id</th>
        <th>round_id</th>
        <th>numbers</th>
        <th>hits</th>
        <th>payout</th>
        <th>created_at</th>
        <th>status</th>
    </tr>
    </thead>
    <tbody>
    @forelse($tickets as $ticket)
        <tr>
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->user_id }}</td>
            <td>{{ $ticket->round_id }}</td>
            <td>{{$ticket->numbers}}</td>
            <td>{{$ticket->hits}}</td>
            <td>{{$ticket->payout}}</td>
            <td>{{$ticket->created_at}}</td>
            <td>{{$ticket->status}}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4">No tickets found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
