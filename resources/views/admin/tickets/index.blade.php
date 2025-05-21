<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Ticket Page</title>
</head>
<body>
<a href="{{route('admin.index')}}">Navigate to main admin page</a>
<h1>All Tickets</h1>
<!-- Date Range Filter Form -->
<form method="GET" action="{{ route('admin.tickets.index') }}">
    <label for="from">Start Date & Time:</label>
    <input type="datetime-local" id="from" name="from"
           value="{{ request('from') ? \Carbon\Carbon::parse(request('from'))->format('Y-m-d\TH:i') : '' }}">

    <label for="to">End Date & Time:</label>
    <input type="datetime-local" id="to" name="to"
           value="{{ request('to') ? \Carbon\Carbon::parse(request('to'))->format('Y-m-d\TH:i') : \Carbon\Carbon::today()->endOfDay()->format('Y-m-d\TH:i') }}">

    <button type="submit">Filter</button>
    <a href="{{ route('admin.tickets.index') }}" style="margin-left: 10px;">Clear</a>
</form>

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
