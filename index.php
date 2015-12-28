<?php
error_reporting(0);
mb_internal_encoding("UTF-8");

/*Messages*/
$START = "_Привет!_". PHP_EOL;
$START .= "Я Telegram бот, меня зовут *FlimFlamBot*. Я могу придумывать сложные и легко запоминающиеся пароли на основе забавных фраз.";
$START .= "Или могу просто развеселить забавными фразами и тостами." . PHP_EOL;
$START .= "Отправь мне /help, и я подскажу как со мной общаться." /*. PHP_EOL*/;
//$START .= "И еще. Иногда я задумываюсь, нужно просто подождать, я всегда отвечу.";

$HELP = "Если нужно придумать *пароль*, отправь мне /pw." . PHP_EOL;
$HELP .= "А если нужно придумать *забавную фразу*, тогда - /ff." . PHP_EOL;
$HELP .= "Еще могу придумать за тебя *тост*. Отправь мне /ch" . PHP_EOL;
$HELP .= "Подробнее о паролях: /help pw";

$HELP_PW = "Команда /pw может иметь аргументы. Аргументы задаются в виде числа после пробела. Например, /pw 424" . PHP_EOL;
$HELP_PW .= "Цифр в числе может быть от 0 до 5." . PHP_EOL;
$HELP_PW .= "*Первая цифра* - количество слов во фразе (значения от 3 до 5, по умолчанию - 3)." . PHP_EOL;
$HELP_PW .= "*Вторая цифра* - количество цифр в начале фразы (значения от 0 до 4, по умолчанию - 0)." . PHP_EOL;
$HELP_PW .= "*Третья цифра* - количество букв из каждого слова (значения от 2 до 4, по умолчанию - 3)." . PHP_EOL;
$HELP_PW .= "*Четвертая цифра* - использовать заглавные буквы в начале слов (1 - использовать, 0 - нет, по умолчанию - 0)." . PHP_EOL;
$HELP_PW .= "*Пятая цифра* - использовать транслит (1 - использовать, 0 - нет, по умолчанию - 0)." . PHP_EOL;
$HELP_PW .= "Ни одна из цифр не является обязательной. Важен их порядок. ";
$HELP_PW .= "Например, если ввести только две цифры (/pw 31), то будут заданы параметры количества слов и количества цифр. ";
$HELP_PW .= "В параметры, значения которых не заданы, будут подставлены значения по умолчанию." . PHP_EOL;
$HELP_PW .= "Одинарной кавычкой отделены те символы, которые используются в пароле.";

function sendMessage($string, $chat, $token, $debug) {
  if ($debug) {
    echo $string;
  } else {
    $string = urlencode($string);
    $request = 'https://api.telegram.org/bot' . $token . '/sendMessage?chat_id=' . $chat . '&parse_mode=Markdown&text=' . $string;
    file_get_contents($request);
  }
}

function getPwGen ($params) {
  $pwgenURL="http://androm.ru/pwgen/passwordGenerator.php?";
  $curl = curl_init($pwgenURL . $params);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
  $result = curl_exec($curl);
  curl_close($curl);
  return $result;
}

if (isset($_GET['msg']) && !empty($_GET['msg'])) {
  $message = "/" . $_GET['msg'];
  $chat    = NULL;
  $user    = NULL;
  $token   = NULL;

  $debug = True;
  $del = "_";
} else {
  $json = file_get_contents('php://input');
  if (empty($json)) {
    header("Location: https://telegram.me/FlimFlamBot");
    exit;
  }
  $action = json_decode($json, true);

  $message = $action['message']['text'];
  $chat    = $action['message']['chat']['id'];
  $user    = $action['message']['from']['id'];
  $token   = '116320087:AAEkJ-wLHJE_VMYOEELKavO8162zdZScJbg';

  $debug = False;
  $del = " ";
}
list($command, $argument) = explode($del, $message, 2);

switch ($command) {
    case "/start":
    case "/start@FlimFlamBot":
        sendMessage($START, $chat, $token, $debug);
        break;
    case "/help":
    case "/help@FlimFlamBot":
        sendMessage(($argument == "pw" ? $HELP_PW : $HELP), $chat, $token, $debug);
        break;
    case "/pw":
    case "/pw@FlimFlamBot":
        $reply = getPwGen("format=pure&pc=1&args=" . $argument . "&hl='");
        $reply = explode(" ", $reply, 2);
        $reply[1] = preg_replace ("/([0-9]+)'/", "$1", $reply[1]);
        $reply = implode(PHP_EOL . "*Подсказка:*" . PHP_EOL, $reply);
        sendMessage("*Пароль:*" . PHP_EOL . $reply, $chat, $token, $debug);
        break;
    case "/ff":
    case "/ff@FlimFlamBot":
        $wc = rand(3,5);
        $dc = rand (0,2);
        $reply = getPwGen("format=sentences&pc=1&wc=" . $wc . "&dc=" .$dc);
        $reply = trim($reply);
        $reply = mb_strtoupper(mb_substr($reply, 0, 1)) . mb_substr($reply, 1, mb_strlen($reply));
        sendMessage($reply. ".", $chat, $token, $debug);
        break;
    case "/ch":
    case "/ch@FlimFlamBot":
        echo "ch";
        $count = getenv('count');
        echo $count;
        if (!$count) {$count = 1;}
        if ($count != 3 ) {
          $wc = rand(3,5);
          $dc = rand (0,2);
          $dc = rand (0,2);
          $reply = getPwGen("format=sentences&pc=1&wc=" . $wc . "&dc=" .$dc);
          if ($wc == 5) {
            $short_reply = explode(" ",$reply);
            $size = sizeof($short_reply);
            switch(rand(1,7)) {
              case 1:
                  $intro = "За ";
                  $reply = $short_reply[$size-2] . " " . $short_reply[$size-1];
                  break;
              case 2:
                  $intro = "Ну, за ";
                  $reply = $short_reply[$size-2] . " " . $short_reply[$size-1];
                  break;
              case 3:
                  $intro = "Жахнем за ";
                  $reply = $short_reply[$size-2] . " " . $short_reply[$size-1];
                  break;
              case 4:
                  $intro = "Опрокинем за ";
                  $reply = $short_reply[$size-2] . " " . $short_reply[$size-1];
                  break;
              case 5:
                  $intro = "Тяпнем по маленькой за ";
                  $reply = $short_reply[$size-2] . " " . $short_reply[$size-1];
                  break;
              case 6:
                  $intro = "Хлопнем за ";
                  $reply = $short_reply[$size-2] . " " . $short_reply[$size-1];
                  break;
              case 7:
                  $intro = "Выпьем за то, что ";
                  break;
            }
          } else {
            $intro = "Выпьем за то, что ";
          }
          $reply = $intro . trim($reply) . "!";
        } else {
          $reply = "Выпьем за любовь!";
        }
        sendMessage("_" . $reply . "_", $chat, $token, $debug);
        $count++;
        echo $count;
        putenv("count=$count");
        break;
    default:
        sendMessage("Мне не понятно, что ты хотел этим сказать: " . $message, $chat, $token, $debug);
}
?>
