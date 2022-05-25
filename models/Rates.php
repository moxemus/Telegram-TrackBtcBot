<?php

require_once 'classes/DB.php';

final class Rates
{
    static public function trySave($value)
    {
        try {
            DB::save("UPDATE rates set val = {$value}");
        } catch (Throwable $exception) {
        }
    }

    static public function getLast()
    {
        return DB::queryOne("SELECT val from rates where id = 1");
    }
}