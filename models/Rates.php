<?php

require_once 'classes/DB.php';

final class Rates
{
    static public function save($value)
    {
        DB::save("UPDATE rates set val = '$value'");
    }

    static public function getLast()
    {
        return DB::queryOne("SELECT val from rates where id = 1");
    }
}