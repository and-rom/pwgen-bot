<?php
error_reporting(0);
mb_internal_encoding("UTF-8");
     ini_set("session.use_cookies", 0);
     ini_set("session.use_only_cookies", 0);
     ini_set("session.use_trans_sid", 1);
     ini_set("session.cache_limiter", "");


/*Messages*/
const START = <<<EOD
_Привет!_
Я Telegram бот, меня зовут *FlimFlamBot*. Я могу генерировать сложные и легко запоминающиеся пароли на основе парольных фраз.
Или могу просто развеселить забавными фразами и тостами.

Отправь мне /help, и я подскажу как со мной общаться.
EOD;

const HELP = <<<EOD
Если нужно придумать *пароль*, отправь мне /pw.
А если нужно придумать *забавную фразу*, тогда - /ff.
Еще могу придумать за тебя *тост*. Отправь мне /ch.

Подробнее о паролях: /help\\_about.
Подробнее о команде /pw: /help\\_pw.

Понравился бот или есть предложения?
[Оставь свой отзыв!](https://telegram.me/storebot?start=flimflambot)
EOD;

const HELP_ABOUT = <<<EOD
Бот предназначен для генерации случайных паролей любого уровня сложности, которые формируются на основе легко запоминающихся парольных фраз. 

Поскольку пароль, состоящий из набора случайных символов, запомнить довольно трудно, бот формирует парольную фразу, из которой по определенному правилу образуется сам пароль.

Парольная фраза  позволяет без труда запомнить пароль, каким бы сложным он ни был.

Под парольной фразой понимается грамматически правильно построенное предложение, составленное из чисел и слов, которые случайным образом выбираются из специальных словарей.
Количество слов в парольной фразе может быть от трех до пяти, количество букв каждого слова, используемых при формировании парольной фразы - от двух до четырех.
Также, можно выбрать регистр первых букв каждого слова.
Кроме того, для увеличения сложности пароля, в парольную фразу и пароль можно добавить однозначное, двузначное, трехзначное или четырехзначное число.

Бот может сформировать пароль длиной от 6 до 24 символов, включающий цифры и буквы в верхнем и нижнем регистрах.

Пароль, образованный из парольной фразы, начинается с числа, если оно задано, затем включает N первых букв от каждого из M слов парольной фразы без пробелов.
Параметры N, M, количество цифр в числе и регистр первых букв каджого слова задаются как аргумент команды /pw.

При вводе пароля следует использовать латинскую раскладку клавиатуры с учетом регистра, так как первая буква каждого слова парольной фразы может быть в верхнем или нижнем регистрах 

Например, если для пароля задано использование двузначного числа, четырех слов и четырех букв от каждого слова парольной фразы, а также верхний регистр первых букв каждого слова, пароль и парольная фраза могут выглядеть следующим образом:
Команда - /pw\\_4241
Пароль - 10<tksGnbxRhjvKuey
Фраза для запоминания - *10* *Белы*х *Птич*ек *Кром*сают *Лгун*а

Подробнее о команде /pw: /help\\_pw.
EOD;

const HELP_PW = <<<EOD
Команда /pw может иметь аргумент, состоящий из параметров генерации пароля. Аргумент представляет собой число и задается после символа подчеркивания. Например, /pw\\_424.

Параметры:
- *Первая цифра* - количество слов во фразе (от 3 до 5, по умолчанию - 3).
- *Вторая цифра* - количество цифр в начале фразы (от 0 до 4, по умолчанию - 0).
- *Третья цифра* - количество букв из каждого слова (от 2 до 4, по умолчанию - 3).
- *Четвертая цифра* - использовать заглавные буквы в начале слов (1 - использовать, 0 - нет, по умолчанию - 0).
- *Пятая цифра* - использовать транслит (1 - использовать, 0 - нет, по умолчанию - 0).

Ни одна из цифр не является обязательной. Важен их порядок.
Например, если ввести только две цифры (/pw\\_31), то будут заданы параметры количества слов и количества цифр.
В параметры, значения которых не заданы, будут подставлены значения по умолчанию.

Вот несколько примеров команды /pw с аргументом:
- /pw_3 (три слова, без цифр, три буквы из каждого слова фразы используются в пароле, первые буквы слов в нижнем регистре);
- /pw_42 (четыре слова, две цифры, три буквы из каждого слова фразы используются в пароле, первые буквы слов в нижнем регистре);
- /pw_532 (пять слов, три цифры, две буквы из каждого слова фразы используются в пароле, первые буквы слов в нижнем регистре);
- /pw_5431 (пять слов, четыре цифры, три буквы из каждого слова фразы используются в пароле, первые буквы слов в верхнем регистре);
- /pw_40411 (четыре слова, без цифр, четыре буквы из каждого слова фразы используются в пароле, первые буквы слов в верхнем регистре, использовать транслит).

В парольной фразе полужирным шрифтом выделены те буквы и цифры, которые используются в пароле.
EOD;

/* Formats */
define (MD,"Markdown");
define (HTML,"html");

function sendMessage($string, $chat, $token, $debug, $format) {
  $string = urlencode($string);
  $request = 'https://api.telegram.org/bot' . $token . '/sendMessage?disable_web_page_preview=1&chat_id=' . $chat . '&parse_mode=' . $format . '&text=' . $string;
  if ($debug) {
    echo $request;
    echo $string;
  } else {
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
  $chat    = $_GET['chat'];
  $user    = $_GET['user'];
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
  $del = "_";
}

session_id($chat);
session_name($chat);
session_cache_expire(1);
session_start();
if (strpos($message, $del) !== false) {
  list($command, $argument) = explode($del, $message, 2);
} else {
  $command=$message;
  $argument='';
}


switch ($command) {
    case "/start":
    case "/start@FlimFlamBot":
        sendMessage(START, $chat, $token, $debug,MD);
        break;
    case "/help":
    case "/help@FlimFlamBot":
        switch ($argument) {
            case "pw":
                $reply = HELP_PW;
                break;
            case "about":
                $reply = HELP_ABOUT;
                break;
            default:
                $reply = HELP;
                break;
        }

        sendMessage($reply, $chat, $token, $debug,MD);
        break;
    case "/pw":
    case "/pw@FlimFlamBot":
        $reply = getPwGen("format=html&pc=1&args=" . $argument . "&hl=1");
        /*$reply = explode(" ", $reply, 2);
        $reply[1] = preg_replace ("/([0-9]+)'/", "$1", $reply[1]);
        $len = strlen($reply[0]);
        $reply = implode(PHP_EOL . "*Подсказка:*" . PHP_EOL, $reply);
        sendMessage("*Пароль:*" . PHP_EOL . "`" . $reply . "`" . "*Длина:* " . $len, $chat, $token, $debug);*/
        sendMessage($reply, $chat, $token, $debug,HTML);
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
        $count = $_SESSION['count'];
        if (!$count) {$count = 1;}
        if ($count % 3 != 0) {
          $wc = rand(3,5);
          $dc = rand (0,1);
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
        $_SESSION['count'] = $count;
        echo $_SESSION['count'];
        break;
    default:
        sendMessage("Мне не понятно, что ты хотел этим сказать: " . $message, $chat, $token, $debug);
}
session_write_close();
?>
