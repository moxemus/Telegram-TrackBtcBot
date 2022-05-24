<?php

require_once 'classes/DB.php';

final class Members
{
    static public function save($id)
    {
        DB::save("INSERT INTO users (id) values ('$id')");
    }

    static public function getUsers()
    {
        return DB::query("SELECT id from users");
    }
}