<?php

class Database
{
    private static $connection = null;

    public static function connect()
    {
        if (self::$connection == null) 
        {
            self::$connection = new mysqli
            (
                $_ENV['DB_HOST'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS'],
                $_ENV['DB_NAME'],
                $_ENV['DB_PORT']
            );

            if (self::$connection->connect_error) 
            {
                die("Database Connection Failed");
            }
        }

        return self::$connection;
    }
}

?>