<?php

require_once 'models/Members.php';
require_once 'models/Rates.php';

final class  Telegram
{
    static private string $url_telegram   = 'https://api.telegram.org/bot';
    static private string $token_telegram = '1676214376:AAFPrR7Y8y0mJzvltQt0SQZKs-QOjiFzzLE';

    static private string $url_btc = 'https://blockchain.info/ticker';

    static private string $green_smile = "\xE2\x9C\x85";
    static private string $red_smile   = "\xF0\x9F\x94\xBB";


    static public function getUpdates()
    {
        $json = file_get_contents(self::$url_telegram . self::$token_telegram . '/getUpdates');
        $data = json_decode($json, true);

        return $data['result'];
    }

    static public function getRate()
    {
        $info = file_get_contents(self::$url_btc);
        $info = json_decode($info, true);

        $rate = $info['USD']['last'];

        return stristr($rate, '.', true);
    }

    static public function updateMembers()
    {
        $updates = self::getUpdates();

        foreach ($updates as $update)
        {
            try
            {
                $member = [];

                $member['id']         = $update['message']['chat']['id'];
                $member['first_name'] = $update['message']['chat']['first_name'];
                $member['last_name']  = $update['message']['chat']['last_name'];
                $member['username']   = $update['message']['chat']['username'];
                $member['language']   = $update['message']['from']['language_code'];
                $member['created']    = date('Y-m-d H:i:s', $update['message']['date']);

                Members::save($member);

            } catch (Throwable $exception) {
            }
        }
    }

    static public function mailing()
    {
        self::updateMembers();

        $members = Members::getUsers();

        if (empty($members)) return;

        $current_rate = (int)self::getRate();
        $last_rate = (int)Rates::getLast();

        $smile = ($current_rate >= $last_rate) ? self::$green_smile : self::$red_smile;
        $message = $current_rate . $smile;

        foreach ($members as $member)
        {
            $text = ($member['is_admin'] == 1)
                ? $message . " " . count($members)
                : $message;

            self::senMessage($member['id'], $text);
        }

        Rates::save($current_rate);
    }

    static public function senMessage(int $chatID, string $text)
    {
        try {
            $info = self::$url_telegram . self::$token_telegram . "/sendMessage?chat_id=" . $chatID;
            $info = $info . "&text=" . urlencode($text);
            $ch = curl_init();

            $optArray = [
                CURLOPT_URL => $info,
                CURLOPT_RETURNTRANSFER => true
            ];

            curl_setopt_array($ch, $optArray);
            curl_exec($ch);
            curl_close($ch);

        } catch (Throwable $exception) {
        }
    }
}