<?php

require_once 'classes/DB.php';

final class Members
{
    static public function trySave($id)
    {
        try {
            DB::save("INSERT INTO users (id) values ({$id})");
        } catch (Throwable $exception) {
        }
    }

    static public function getUsers()
    {
        return DB::query("SELECT id from users");
    }
}