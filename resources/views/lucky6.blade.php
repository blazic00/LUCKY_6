<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Ticket Demo</title>
    <style>
        #number-grid {
            display: grid;
            grid-template-columns: repeat(10, 40px);
            grid-gap: 5px;
            margin-top: 10px;
        }
        .number-cell {
            width: 40px;
            height: 40px;
            background-color: #f0f0f0;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        #notifications {
            width: 100%;
            height: 150px;
            border: 1px solid #ccc;
            padding: 10px;
            overflow-y: auto;
            background-color: #f9f9f9;
            white-space: pre-line;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<a href="{{route('index')}}">Navigate to main page</a>

<h1>Lucky 6 Bingo</h1>

<!-- Ticket Creation Form -->
<form id="ticket-form">
    @csrf
    <label for="numbers">Choose 6 numbers (1-48):</label><br>
    <input type="number" name="number" min="1" max="48" id="num1" required>
    <input type="number" name="number" min="1" max="48" id="num2" required>
    <input type="number" name="number" min="1" max="48" id="num3" required>
    <input type="number" name="number" min="1" max="48" id="num4" required>
    <input type="number" name="number" min="1" max="48" id="num5" required>
    <input type="number" name="number" min="1" max="48" id="num6" required><br><br>
    <button type="submit">Create Ticket</button>
</form>

<!-- Number Grid -->
<h3>Numbers Drawn</h3>
<div id="number-grid"></div>

<!-- Notifications -->
<h3>Notifications</h3>
<div id="notifications"></div>

<script>
    @if(Auth::check())
    const userId = {{ Auth::id() }};
    let ws = new WebSocket(`ws://localhost:9502?user_id=${userId}`);
    @else
    const userId = null;
    console.warn("User not authenticated, WebSocket disabled.");
    @endif
    let drawnNumbers = [];

    const addNotification = message => {
        const notifications = document.getElementById('notifications');
        notifications.innerText += message + "\n";
        notifications.scrollTop = notifications.scrollHeight;
    };

    const updateNumberGrid = () => {
        const grid = document.getElementById('number-grid');
        grid.innerHTML = ''; // Clear before redraw
        drawnNumbers.forEach(num => {
            const cell = document.createElement('div');
            cell.className = 'number-cell';
            cell.textContent = num;
            grid.appendChild(cell);
        });
    };

    ws.onopen = () => {
        console.log("WebSocket connected.");
        addNotification("Connected to Game Server.");
    };

    ws.onmessage = e => {
        console.log("Received from server:", e.data);
        const data = JSON.parse(e.data);

        if (data.event === "new_round") {
            drawnNumbers = []; // Clear grid for new round
            updateNumberGrid();
            addNotification(`New round started! ROUND ID = ${data.data.roundId}`);
        }
        else if (data.event === "number_drawn") {
                drawnNumbers = data.data.numbers;
                updateNumberGrid();

        }
        else if (data.event === "round_end") {
            addNotification(`Round has ended! ROUND ID = ${data.data.roundId}`);
        }
        else if (data.event === "ticket_result") {
            const result = data.data;
            addNotification(`Your ticket result: Ticket #${result.ticket_id}, Round #${result.round_id}, Numbers: ${result.numbers}, Hits: ${result.hits}, Payout: ${result.payout}`);
        }
    };

    ws.onerror = err => {
        console.error("WebSocket error:", err);
        addNotification("Error: WebSocket connection failed!");
    };

    document.getElementById('ticket-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const numbers = [
            parseInt(document.getElementById('num1').value),
            parseInt(document.getElementById('num2').value),
            parseInt(document.getElementById('num3').value),
            parseInt(document.getElementById('num4').value),
            parseInt(document.getElementById('num5').value),
            parseInt(document.getElementById('num6').value)
        ];

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch('/tickets', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ numbers })
            });

            const result = await response.json();

            if (response.ok) {
                console.log("Ticket created:", result);
                addNotification("Ticket created successfully!");
            } else {
                console.error("Ticket creation failed:", result.error);
                addNotification(`Error: ${result.error}`);
            }
        } catch (error) {
            console.error("Error creating ticket:", error);
            addNotification("Error: Could not connect to Laravel API.");
        }
    });
</script>

</body>
</html>
