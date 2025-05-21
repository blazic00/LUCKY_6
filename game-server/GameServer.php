<?php

use Swoole\WebSocket\Server;

interface Observer{
    public function update($message);
}

class GameServer implements Observer
{
    private $server;
    private $clients = [];

    private $userFdMap = [];

    private $config;
    private $gameManager;

    public function __construct()
    {
        $this->config = require 'config.php'; // Load configuration
        $this->server = new Server($this->config['server_host'], $this->config['server_port']);
        $this->gameManager = new GameManager();
        $this->gameManager->attach($this);

        $this->setupHandlers();
    }

    public function start()
    {
        $this->server->start();
    }


    private function setupHandlers()
    {
        $this->server->on('open', function ($server, $request) {
            // Parse the query string from the request URL
            parse_str($request->server['query_string'], $queryParams);

            // Extract user_id if present
            $userId = $queryParams['user_id'] ?? null;

            if ($userId !== null) {
                // Store user_id mapped to the client's fd
                $this->clients[$request->fd] = [
                    'user_id' => $userId,
                    'connected_at' => time()
                ];

                // Add mapping: user_id => fd
                $this->userFdMap[$userId] = $request->fd;


                echo "Connection opened: fd={$request->fd}, user_id={$userId}\n";
            } else {
                echo "Connection opened without user_id: fd={$request->fd}\n";
            }
        });

        $this->server->on('message', function ($server, $frame) {
            echo "Received message: {$frame->data}\n";
            $server->push($frame->fd, "Server received: {$frame->data}");
        });

        $this->server->on('close', function ($server, $fd) {
            $userId = $this->clients[$fd]['user_id'];
            unset($this->clients[$fd]);
            unset($this->userFdMap[$userId]);
            echo "Connection closed: {$fd}\n";
        });


        $roundTimer = $this->config['round_timer'];

        //Start round every interval //TO DO hard coded
        Swoole\Timer::tick($roundTimer, function () {
            Swoole\Coroutine::create(function () {
                $this->gameManager->run();
            });
        });

    }

    public function update($payload)
    {

            // This method will be triggered when GameManager calls notify()
            //echo "GameManager Notification: ". $payload['event'] . "\n";

            switch ($payload['event']) {
                case 'ticket_results':
                    $tickets = $payload['data'];
                    foreach ($tickets as $ticket) {
                        if($ticket['payout'] == 0){
                            continue;
                        }
                        $ticketResult = [
                            'ticket_id' => $ticket['id'],
                            'round_id' => $ticket['round_id'],
                            'numbers' => $ticket['numbers'],
                            'hits' => $ticket['hits'],
                            'payout' => $ticket['payout'],
                        ];

                        $userId = $ticket['user_id'];
                        $fd = $this->userFdMap[$userId];
                        echo $fd. " " . $userId;
                        $new_payload = [
                            'event' => 'ticket_result',
                            'data' => $ticketResult,
                        ];
                        $this->server->push($fd, json_encode($new_payload));
                    }
                        break;
                default:
                    foreach ($this->clients as $fd => $_) {
                        $this->server->push($fd, json_encode($payload));
                    }
            }
    }
}
