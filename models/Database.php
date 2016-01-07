<?php

class Database extends Sparrow
{
    public static $type = 'pdomysql';

    public function __construct($database)
    {
        $config = [
            'type' => self::$type,
            'hostname' => DB_HOST,
            'database' => $database,
            'username' => DB_USER,
            'password' => DB_PASS
        ];
        try {
            $this->setDb($config);
        } catch (PDOException $e) {
            echo "Connection to the server database failed.";
            die;
        }
    }
}
