<?php

require_once 'models/Members.php';
require_once 'models/Rates.php';

final class  Telegram
{
    static private $url_telegram = 'https://api.telegram.org/bot';
    static private $token_telegram = 'your-telegram-token';

    static private $url_btc = 'https://api.nomics.com/v1/currencies/ticker?ids=BTC&interval=1d,1d&convert=USD&key=';
    static private $token_btc = 'your-nomics-token';

    static private $green_smile = "\xE2\x9C\x85";
    static private $red_smile = "\xF0\x9F\x94\xBB";

    //Send new message to chat
    static function trySendMessage($chatID, $text)
    {
        try {
            $info = self::$url_telegram . self::$token_telegram . "/sendMessage?chat_id=" . $chatID;
            $info = $info . "&text=" . urlencode($text);
            $ch = curl_init();

            $optArray = array(
                CURLOPT_URL => $info,
                CURLOPT_RETURNTRANSFER => true
            );

            curl_setopt_array($ch, $optArray);
            curl_exec($ch);
            curl_close($ch);

        } catch (Throwable $exception) {

        }
    }

    //Get all last actions with bot
    static function getUpdates()
    {
        $info = file_get_contents(self::$url_telegram . self::$token_telegram . '/getUpdates');
        $info = json_decode($info, true);

        return $info['result'];
    }

    //Get current rate by API
    static function getRate()
    {
        $info = file_get_contents(self::$url_btc . self::$token_btc);
        $info = json_decode($info, true);

        return stristr($info[0]['price'], '.', true);
    }

    //Add new chats in database
    static function updateMembers()
    {
        $updates = self::getUpdates();

        //Just try to insert all users in table
        //If user with this ID already exist in DB - nothing will happen
        foreach ($updates as $update) {
            $id = $update['message']['chat']['id'];
            Members::trySave($id);
        }
    }

    //Start notify all chats about new BTC rate
    static function mailing()
    {
        self::updateMembers();

        $members = Members::getUsers();

        if (!isset($members) || count($members) == 0) return;

        $current_rate = (int)self::getRate();
        $last_rate = (int)Rates::getLast();

        $smile = ($current_rate >= $last_rate) ? self::$green_smile : self::$red_smile;

        foreach ($members as $member) {
            self::trySendMessage($member['id'], $current_rate . $smile);
        }

        Rates::trySave($current_rate);
    }
}