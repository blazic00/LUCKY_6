<?php

class GameManager
{
    private $gameDao;
    private $observers = [];

    private $config;

    public function __construct()
    {
        $this->gameDao = new GameRoundDAO();
        $this->config = require 'config.php'; // Load configuration
    }

    public function attach(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    // Method to notify observers
    public function notify(string $eventType, array $data = [], string $message = '')
    {
        $payload = [
            'event' => $eventType,
            'data' => $data,
            'message' => $message
        ];

        foreach ($this->observers as $observer) {
            $observer->update($payload);
        }
    }



    public function run()
    {
        $roundId = $this->startNewRound();
        $drawNumbers = $this->executeNumberDraw();
        $this->processRound($roundId, $drawNumbers);
    }

    private function executeNumberDraw(){
        //Draw numbers
        $numbers = range(1, 48);
        shuffle($numbers);
        $drawNumbers = array_slice($numbers, 0, 30);

        $usleepTime = $this->config['usleep_time'];

        $reported_numbers = [];
        for($i = 0; $i < count($drawNumbers); $i++) {
            $reported_numbers[$i] = $drawNumbers[$i];
            //echo "Drawing number: $number\n";
            $this->notify('number_drawn',['numbers' => $reported_numbers]);
            //TO DO
            // Non-blocking sleep
            Swoole\Coroutine::sleep($usleepTime / 1_000_000);
        }
        return $drawNumbers;
    }

    private function processRound($roundId, $drawNumbers){
        $this->gameDao->endRound($roundId, $drawNumbers);
        $this->notify("round_end", ['roundId' => $roundId]);

        $ticketsProcessed = $this->processTickets($roundId, $drawNumbers);
        $this->notify("ticket_results",$ticketsProcessed);
    }

    private function startNewRound(){
        //Ensure there is 1 pending round
        $pendingRoundsCount = $this->gameDao->countPendingRounds();
        if ($pendingRoundsCount == 0) {
            $newRoundId = $this->gameDao->createNewRound();
        }
        //Get pending round
        $pendingRound = $this->gameDao->getPendingRound();

        $roundId = $pendingRound['id'];

        //Start round
        $this->gameDao->startRound($roundId);
        $this->notify('new_round',['roundId' => $roundId]);
        //Prepare game_round for next round
        $this->gameDao->createNewRound();

        return $roundId;
    }

    private function processTickets($roundId, $drawNumbers){
        $tickets = $this->gameDao->getTicketsForRound($roundId);
        foreach ($tickets as &$ticket) {
            $ticketNumbers = json_decode(json_decode($ticket['numbers'],true),true);
            $interSection = array_intersect($ticketNumbers, $drawNumbers);
            $matchingNumbers = count($interSection);

            $payoutPerHit = $this->config['payout_per_hit'];
            $payoutThreshold = $this->config['payout_threshold'];

            $ticket['hits'] = $matchingNumbers;
            $ticket['payout'] = $ticket['hits']>= $payoutThreshold ? $ticket['hits'] * $payoutPerHit : 0;
        }
        $this->gameDao->updateTickets($tickets);
        $this->gameDao->updateUserBalance($tickets);
        return $tickets;
    }


}
