<?php
class Database
{
    private static $instance;

    public static function getConnection()
    {
        if (!self::$instance) {
            $config = require 'config.php'; // Load configuration

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $config['db_host'],
                $config['db_port'],
                $config['db_name']
            );

            self::$instance = new PDO(
                $dsn,
                $config['db_user'],
                $config['db_password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        }

        return self::$instance;
    }
}
