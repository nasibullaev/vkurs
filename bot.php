// by Fayzullo Nasibullaev 2019
<?php
error_reporting(0);
ob_start();
define('API_KEY', '1025601817:AAE8H_k5Of85hlp6ae-76Qif6AsIgZ8q0Hg');
$admin = 1062436669;
$link = "http://cbu.uz/uzc/arkhiv-kursov-valyut/xml/";

function typing()
{
    global $userid;
    return bot('sendChatAction', [
        'chat_id' => $userid,
        'action' => 'typing',
    ]);
}

function bot($method, $datas = [])
{
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}
// begin writing functions
function get_important()
{
    global $link;
    $xml = file_get_contents($link);
    $rates = new SimpleXMLElement($xml);
    $response = "";
    foreach ($rates as $val) {
        if ($val->Ccy == 'USD') {
            $response .= "1 Dollar = " . $val->Rate . " so'm\n";
        }
        if ($val->Ccy == 'EUR') {
            $response .= "1 Yevro - " . $val->Rate . " so'm\n";
        }
        if ($val->Ccy == 'RUB') {
            $response .= "1 RUB - " . $val->Rate . " so'm\n";
        }
    }
    $response .= "O'zgartirilgan sana $val->date";
    return $response;
}

function get_all()
{
    global $link;
    $xml      = file_get_contents($link);
    $rates    = new SimpleXMLElement($xml);
    $response = "";
    foreach ($rates as $val) {
        $response .= "1 " . $val->CcyNm_UZ . " = " . $val->Rate . " so'm \n";
    }
    $response .= "O'zgartirilgan sana $val->date";
    return $response;
}

function get_usd()
{
    global $link;
    $xml = file_get_contents($link);
    $rates = new SimpleXMLElement($xml);
    $response = "";
    foreach ($rates as $val) {
        if ($val->Ccy == 'USD') {
            $response = "1 Dollar = " . $val->Rate . " so'm\n";
        }
    }
    $response .= "O'zgartirilgan sana $val->date";
    return $response;
}

function get_dollar()
{
    $xml = file_get_contents("http://cbu.uz/uzc/arkhiv-kursov-valyut/xml/");
    $rates = new SimpleXMLElement($xml);
    $response = "";
    foreach ($rates as $val) {
        if ($val->Ccy == 'USD') {
            $response = $val->Rate;
        }
    }
    $response = trim($response);
    return $response;
}

function sm($text, $menu = 0, $userid = 0, $parse_mode = 'markdown')
{
    if ($userid) {
    } else {
        global $userid;
    }
    if ($menu) {
        bot('sendMessage', [
            'chat_id'                  => $userid,
            'text'                     => $text,
            'parse_mode'               => $parse_mode,
            'reply_markup'             => $menu,
            'disable_web_page_preview' => true,
        ]);
    } else {
        bot('sendMessage', [
            'chat_id'    => $userid,
            'text'       => $text,
            'parse_mode' => $parse_mode
        ]);
    }
}

function sp($photo, $caption, $menu = 0, $parse_mode = 'markdown')
{
    global $userid;
    bot('sendPhoto', [
        'chat_id'      => $userid,
        'photo'        => $photo,
        'caption'      => $caption,
        'parse_mode'   => $parse_mode,
        'reply_markup' => $menu,

    ]);
}

function del()
{
    global $userid;
    global $msgid;
    bot('deleteMessage', [
        'chat_id'    => $userid,
        'message_id' => $msgid
    ]);
}

function emt($text, $menu = 0, $chatid = 0, $parse_mode = 'markdown')
{
    global $msgid;
    if ($chatid) {
    } else {
        global $chatid;
    }
    if ($menu) {
        bot('editMessageText', [
            'chat_id'                  => $chatid,
            'text'                     => $text,
            'parse_mode'               => $parse_mode,
            'message_id'               => $msgid,
            'reply_markup'             => $menu,
            'disable_web_page_preview' => true,
        ]);
    } else {
        bot('editMessageText', [
            'chat_id'    => $chatid,
            'message_id' => $msgid,
            'text'       => $text,
            'parse_mode' => $parse_mode
        ]);
    }
}

function acl($msg)
{
    global $callid;
    bot('answerCallbackQuery', [
        'callback_query_id' => $callid,
        'text'              => $msg,
        'show_alert'        => false
    ]);
}

function emc($caption, $menu)
{
    global $userid;
    global $msgid;
    bot('editMessageCaption', [
        'chat_id'      => $userid,
        'message_id'   => $msgid,
        'caption'      => $caption,
        'reply_markup' => $menu,
        'parse_mode'   => "markdown",
    ]);
}

function menu($text)
{
    global $menu;
    file_put_contents($menu, $text);
}

// Keyboards
$menyu_k = json_encode([
    'inline_keyboard' => [
        [['text' => "Kurslarni ko'rish", 'callback_data' => "kursi"], ['text' => "Biz haqimizda", 'callback_data' => "about"]],
        [['text' => "Dollar konverter", 'callback_data' => "hisoblash"]]
    ]
]);

$kurs_k = json_encode([
    'inline_keyboard' => [
        [['text' => "Dollar Kursini ko'rish", 'callback_data' => "dollar"]],
        [['text' => "Muhim Kurslarni Ko'rish", 'callback_data' => "need"]],
        [['text' => "Barcha Kurslarni Ko'rish", 'callback_data' => "all"]],
        [['text' => "Bosh Menyuga Qaytish", 'callback_data' => "menu"]]
    ]
]);

$about_k    = json_encode([
    'inline_keyboard' => [
        [['text' => "Bosh menyuga qaytish", 'callback_data' => "menu"]],
        [['text' => "Admin", 'url' => "t.me/vluestar"]]
    ]
]);

$back_k = json_encode([
    'inline_keyboard' => [
        [['text' => "Bosh menyuga qaytish", 'callback_data' => "menu"]]
    ]
]);
// end of keyboards
$content = file_get_contents('php://input');
$update  = json_decode($content, true);

if ($update["message"]) {
    $chatid   = $update["message"]["chat"]["id"];
    $userid   = $update["message"]["from"]["id"];
    $name     = $update["message"]["from"]["first_name"];
    $lastname = $update["message"]["from"]["last_name"];
    $username = $update["message"]["from"]["username"];
    $msg      = $update["message"]["text"];
    $reply    = $update["message"]["reply_to_message"]["text"];
    $msgid    = $update["message"]["message_id"];
    if ($update["message"]["contact"]) {
        $c_number = $update["message"]["contact"]["phone_number"];
        $c_name   = $update["message"]["contact"]["first_name"];
        $c_id     = $update["message"]["contact"]["user_id"];
    }
} else if ($update["callback_query"]["data"]) {
    $chatid     = $update["callback_query"]["message"]["chat"]["id"];
    $userid     = $update["callback_query"]["from"]["id"];
    $msgid      = $update["callback_query"]["message"]["message_id"];
    $name       = $update["callback_query"]["from"]["first_name"];
    $username   = $update["callback_query"]["from"]["username"];
    $callid     = $update["callback_query"]["id"];
    $data       = $update["callback_query"]["data"];
} else if ($update["inline_query"]["id"]) {
    $msg      = $update["inline_query"]["query"];
    $userid   = $update["inline_query"]["from"]["id"];
    $username = $update["inline_query"]["from"]["username"];
    $name     = $update["inline_query"]["from"]["first_name"];
}

$time = date('H:i', strtotime('5 hour'));
$date = date('d.m.Y', strtotime('5 hour'));
$wait = "Ma'lumotlar yuklanmoqda. Iltimos Kuting!";

if ($msg == "/start") {
    $answer = "Assalomu alaykum $name. Botimizga Xush Kelibsiz.\n Bizning bot orqali dollarning va dunyoning 74 ta davlatining valyuta kursini bilishingiz mumkin";
    sm($answer, $menyu_k);
}

if ($data == "kursi") {
    emt("Tanlang ", $kurs_k);
}

if ($data == "dollar") {
    acl($wait);
    $dollar = get_usd();
    emt("$dollar\n\n Hozirgi sana: $date ", $kurs_k);
}

if ($data == "all") {
    acl($wait);
    $all = get_all();
    emt("$all\n\n Hozirgi sana: $date", $kurs_k);
}

if ($data == "need") {
    acl($wait);
    $need = get_important();
    emt("$need\n\n Hozirgi sana: $date", $kurs_k);
}

if ($data == "about") {
    $answer = "Bot yaratuvchisi: [Admin](t.me/vluestar)\n Sizga ham universal bot kerakmi?\n Mizga murojaat qiling!\n Har qanday turdagi botlarni dasturlaymiz.";
    emt($answer, $about_k);
}
if ($data == "menu") {
    $answer = "$name siz bosh menyuga qaytdingiz";
    emt($answer, $menyu_k);
}
if ($data == "hisoblash") {
    sm("Konvert qilish uchun kerakli summani jo'nating", $back_k);
}

if ($msg) {
    if (is_numeric($msg)) {
        $dollar = get_dollar();
        $som = $msg / $dollar;
        $usd = $dollar * $msg;
        sm("$msg dollar = $usd so'm\n$msg so'm = $som dollar", $back_k);
    }
}

if ($chatid) {
    $users = file_get_contents("users.txt");
    if (mb_stripos($users, $chatid) !== false) {
    } else {
        $users = file_get_contents("users.txt");
        file_put_contents("users.txt", "$users\n$chatid");
        $count = file_get_contents("users.txt");
        $count = substr_count($count, "\n");
        sm("Yangi user: Ismi: $name\n Useri: $username\n Id: [$chatid](tg://user?id=$chatid)\n Qo'shilgan sana: $date $time\n Bu a'zo botning: $count-chi foydalanuvchisi", null, $admin);
    }
}

$rpl = json_encode([
    'resize_keyboard' => false,
    'force_reply'    => true,
    'selective'      => true
]);
if ($msg == "/send" and $chatid == $admin) {
    sm("Yozing...", $rpl);
}

if ($reply == "Yozing...") {
    $users = file_get_contents("users.txt");
    $users = explode("\n", $users);
    $users_id = '';

    foreach ($users as $uid) {
        $sent = file_get_contents("sent.txt");
        if (mb_stripos($sent, $uid) !== false) {
        } else {

            sm($msg, null, $uid);

            // to save users id
            $users_id .= " $uid";
            if (!empty($sent)) {
                file_put_contents("sent.txt", "$sent\n$uid");
            } else {
                file_put_contents("sent.txt", $uid);
            }
        }
    }





    $count = file_get_contents("users.txt");
    $count = substr_count($count, "\n");
    sm("Habar $count ta foydalanuvchiga jo'natildi!\n$users_id", null, $admin);
    unlink("sent.txt");
}

if ($msg == "/stats" and $chatid == $admin) {
    $users = file_get_contents("users.txt");
    $users   = substr_count($users, "\n");
    sm("All $users \n $time");
}
