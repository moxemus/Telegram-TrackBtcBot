<?php

require_once 'classes/DB.php';

final class Members
{
    static public function save(array $member)
    {
        try {
            DB::save(" INSERT INTO users (id, created, first_name, last_name, username, language_code) " .
                " VALUES ('" . implode("','",  $member) . "')");

        } catch (Throwable $exception) {
            echo $exception->getMessage();
        }
    }

    static public function getUsers()
    {
        return DB::query("SELECT id, is_admin from users");
    }
}