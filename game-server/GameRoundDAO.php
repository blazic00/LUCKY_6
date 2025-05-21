<?php


class GameRoundDAO
{
    private $pdo;

    public function __construct(){
        $this->pdo = Database::getConnection();
    }

    public function countPendingRounds(){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM game_rounds WHERE status = 'pending'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function createNewRound(){
        $timestamp = (new DateTime('now', new DateTimeZone('Europe/Belgrade')))->format('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("INSERT INTO game_rounds (status, created_at, updated_at) VALUES ('pending', :created_at, :updated_at)");
        $stmt->execute([
            'created_at' => $timestamp,
            'updated_at' => $timestamp,

        ]);
        return $this->pdo->lastInsertId();
    }

    public function getPendingRound(){
        $stmt = $this->pdo->prepare("SELECT id FROM game_rounds WHERE status = 'pending' ORDER BY created_at ASC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch();
    }

    public function startRound($roundId){
        $timestamp = (new DateTime('now', new DateTimeZone('Europe/Belgrade')))->format('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("UPDATE game_rounds SET started_at = NOW(), status = 'running', updated_at = :updated_at WHERE id = :id");
        $stmt->execute([
            'id' => $roundId,
            'updated_at' => $timestamp,
        ]);
    }

    public function getTicketsForRound($roundId){
        //Gather tickets placed for the round
        $stmt = $this->pdo->prepare("SELECT * FROM tickets WHERE round_id = :round_id");
        $stmt->execute(['round_id' => $roundId]);
        return $stmt->fetchAll();
    }

    public function endRound($roundId, $drawNumbers)
    {
        $timestamp = (new DateTime('now', new DateTimeZone('Europe/Belgrade')))->format('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("UPDATE game_rounds SET status = 'ended', drawn_numbers = :numbers, updated_at = :updated_at WHERE id = :id");
        $stmt->execute([
            'numbers' => json_encode($drawNumbers),
            'id' => $roundId,
            'updated_at' => $timestamp,
        ]);
    }

    public function updateTickets(array $tickets)
    {

        foreach ($tickets as $ticket) {
            $timestamp = (new DateTime('now', new DateTimeZone('Europe/Belgrade')))->format('Y-m-d H:i:s');
            $stmt = $this->pdo->prepare("UPDATE tickets SET updated_at= :updated_at , hits = :hits, payout = :payout, status = 'processed' WHERE id = :id");
            $stmt->execute([
                'id' => $ticket['id'],
                'hits' => $ticket['hits'],
                'payout' => $ticket['payout'],
                'updated_at' => $timestamp,
            ]);
        }
    }

    public function updateUserBalance(array $tickets)
    {
        foreach ($tickets as $ticket) {
            $timestamp = (new DateTime('now', new DateTimeZone('Europe/Belgrade')))->format('Y-m-d H:i:s');
            $stmt = $this->pdo->prepare("UPDATE users SET updated_at= :updated_at , balance = balance + :reward  WHERE id = :id");
            $stmt->execute([
                'id' => $ticket['user_id'],
                'reward' => $ticket['payout'],
                'updated_at' => $timestamp,
            ]);
        }
    }
}
