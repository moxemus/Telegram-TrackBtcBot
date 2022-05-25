<?php

final class DB
{
    static private PDO $dbh;

    static private $host = 'localhost';
    static private $dbname = 'telegram';
    static private $user = 'root';
    static private $password = 'root';


    static private function connect()
    {
        if (!isset(self::$dbh))
            self::$dbh = new PDO('mysql:host=' . DB::$host . ';dbname=' . DB::$dbname, DB::$user, DB::$password);
    }

    static public function query($sql)
    {
        self::connect();

        return self::$dbh->query($sql, PDO::FETCH_ASSOC)
            ->fetchAll();
    }

    static public function queryOne($sql)
    {
        self::connect();

        return self::$dbh->query($sql, PDO::FETCH_ASSOC)
            ->fetch(PDO::FETCH_OBJ)->val;
    }

    static public function save($sql)
    {
        self::connect();

        self::$dbh->prepare($sql);
        self::$dbh->exec($sql);
    }
}
