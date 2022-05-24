<?php

final class DB
{
    static protected PDO $dbh;

    static protected $host = 'localhost';
    static protected $dbname = 'telegram';
    static protected $user = 'root';
    static protected $password = 'root';


    static public function connect()
    {
        DB::$dbh = new PDO('mysql:host=' . DB::$host . ';dbname=' . DB::$dbname, DB::$user, DB::$password);
    }

    static public function query($sql)
    {
        DB::connect();

        $stmt = DB::$dbh->query($sql, PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    static public function queryOne($sql)
    {
        DB::connect();

        return DB::$dbh->query($sql, PDO::FETCH_ASSOC)
            ->fetch(PDO::FETCH_OBJ)->val;
    }

    static public function save($sql)
    {
        DB::connect();

        DB::$dbh->prepare($sql);
        DB::$dbh->exec($sql);
    }
}
