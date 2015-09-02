<?php
mb_internal_encoding("UTF-8");

/*Messages*/
$START = "Привет! Я Telegram бот, меня зовут FlimFlamBot. Я могу придумывать сложные и легко запоминающиеся пароли на основе забавных фраз. Или могу просто развеселить забавными фразами." . PHP_EOL . "Отправь мне /help, и я подскажу как со мной общаться." . PHP_EOL . "И еще. Иногда я задумываюсь, нужно просто подождать, я всегда отвечу.";

$HELP = "Если нужно придумать пароль, отправь мне /pw." . PHP_EOL . "А если нужно придумать забавную фразу, тогда - /ff." . PHP_EOL . "Еще могу придумать за тебя тост. Отправь мне /ch" . PHP_EOL . "Подробнее о паролях: /help pw";
$HELP_PW = "Комманда /pw может иметь аргументы. Аргументы задаются в виде числа после пробела. Например, /pw 424" . PHP_EOL . "Первая цифра - количество слов во фразе (значения от 3 до 5)." . PHP_EOL . "Вторая цифра - количество цифр в начале фразы (значения от 0 до 4)." . PHP_EOL . "Третья цифра - количество букв из каждого слова (значения от 2 до 4)." . PHP_EOL . "Черверая цифра - использовать заглавные буквы в начале слов (1 - использовать, 0 - нет)." . PHP_EOL . "Пятая цифра - использовать транслит (1 - использовать, 0 - нет)." . PHP_EOL . "Ни одна из цифр не является обязательной. Важен их порядок. Например, если ввести только две цифры, то будут заданы параметры количества слов и количества цифр. В незаданные параметры будут подставлены значения по умолчанию.";

function prepareString($string) {
  $string = urlencode($string);
  //$string = str_replace('+','%2B',$string);
  //$string = str_replace(' ','%20',$string);
  $string = trim($string);
  return $string;
}

function sendMessage($string,$chat,$token) {
  $request = 'https://api.telegram.org/bot' . $token . '/sendMessage?chat_id=' . $chat . '&text=' . prepareString($string);
  file_get_contents($request);
}

function getPwGen ($params) {
  $pwgenURL="http://androm.ru/pwgen/passwordGenerator.php?";
  $curl = curl_init($pwgenURL . $params);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
  $result = curl_exec($curl);
  curl_close($curl);
  return $result;
}

$json = file_get_contents('php://input');
if (empty($json)) exit;
$action = json_decode($json, true);

$message = $action['message']['text'];
$chat    = $action['message']['chat']['id'];
$user    = $action['message']['from']['id'];
$token   = '116320087:AAEkJ-wLHJE_VMYOEELKavO8162zdZScJbg';

list($command, $argument) = explode(" ", $message, 2);

switch ($command) {
    case "/start":
    case "/start@FlimFlamBot":
        sendMessage($START, $chat, $token);
        break;
    case "/help":
    case "/help@FlimFlamBot":
        sendMessage(($argument == "pw" ? $HELP_PW : $HELP), $chat, $token);
        break;
    case "/pw":
    case "/pw@FlimFlamBot":
        $reply = getPwGen("format=pure&pc=1&args=" . $argument . "&hl='");
        $reply = explode(" ", $reply, 2);
        $reply[1] = preg_replace ("/([0-9]+)'/", "$1", $reply[1]);
        $reply = implode(PHP_EOL . "Подсказка:" . PHP_EOL, $reply);
        sendMessage("Пароль:" . PHP_EOL . $reply, $chat, $token);
        break;
    case "/ff":
    case "/ff@FlimFlamBot":
        $wc = rand(3,5);
        $dc = rand (0,2);
        $reply = getPwGen("format=sentences&pc=1&wc=" . $wc . "&dc=" .$dc);
        $reply = (preg_match('/^\d/', $reply) ? trim($reply) . "." : mb_strtoupper(mb_substr(trim($reply), 0, 1)) . ".");
        sendMessage($reply, $chat, $token);
        break;
    case "/ch":
    case "/ch@FlimFlamBot":
        $wc = rand(3,5);
        $dc = rand (0,2);
        $dc = rand (0,2);
        $reply = getPwGen("format=sentences&pc=1&wc=" . $wc . "&dc=" .$dc);
        $reply = "Выпьем за то, что " . trim($reply) . "!";
        sendMessage($reply, $chat, $token);
        break;
    default:
        sendMessage("Bad request: " . $message, $chat, $token);
}
?>
