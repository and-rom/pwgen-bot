<?php
/*Messages*/
$START = "Привет! Я Telegram бот, меня зовут FlimFlamBot. Я могу придумывать сложные и легко запоминающиеся пароли на основе забавных фраз. Или могу просто развеселить забавными фразами." . PHP_EOL . "Отправь мне /help, и я подскажу как со мной общаться." . PHP_EOL . "И еще. Иногда я задумываюсь, нужно просто подождать, я всегда отвечу.";

$HELP = "Если нужно придумать пароль, отправь мне /pw." . PHP_EOL . "А если нужно придумать забавную фразу, тогда - /ff.";

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

switch ($message) {
    case "/start":
    case "/start@FlimFlamBot":
        sendMessage($START,$chat,$token);
        break;
    case "/help":
    case "/help@FlimFlamBot":
        sendMessage($HELP,$chat,$token);
        break;
    case "/pw":
    case "/pw@FlimFlamBot":
        $reply = getPwGen("format=pure&pc=1&args=423");
        sendMessage($reply,$chat,$token);
        break;
    case "/ff":
    case "/ff@FlimFlamBot":
        $wc = rand(3,5);
        $dc = rand (0,2);
        $reply = getPwGen("format=sentences&pc=1&wc=" . $wc . "&dc=" .$dc);
        sendMessage($reply,$chat,$token);
        break;
    default:
        sendMessage("Bad request:" . $message,$chat,$token);
}
?>