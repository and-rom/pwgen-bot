<?php
/*Settings*/
error_reporting(0);
mb_internal_encoding("UTF-8");
ini_set("session.use_cookies", 0);
ini_set("session.use_only_cookies", 0);
ini_set("session.use_trans_sid", 1);
ini_set("session.cache_limiter", "");
ini_set('session.gc_max_lifetime', 600);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);

define(TOKEN, "116320087:AAEkJ-wLHJE_VMYOEELKavO8162zdZScJbg");
define(BOTID, "116320087");
define(DEVID, "62434569");

/*Messages*/
const START = <<<EOD
_Привет!_
Я Telegram бот, меня зовут *FlimFlamBot*. Я могу генерировать сложные и легко запоминающиеся пароли на основе парольных фраз.
Или могу просто развеселить забавными фразами и тостами.

Отправь мне /help, и я подскажу как со мной общаться.

Понравился бот или есть предложения?
[Оставь свой отзыв!](https://telegram.me/storebot?start=flimflambot)
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
*Пароль*:
`10<tksGnbxRhjvKuey`
*Фраза* для запоминания:
(10) (Белы)х (Птич)ек (Кром)сают (Лгун)а

*Длина:*
18

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
- /pw\\_3 (три слова, без цифр, три буквы из каждого слова фразы используются в пароле, первые буквы слов в нижнем регистре);
- /pw\\_42 (четыре слова, две цифры, три буквы из каждого слова фразы используются в пароле, первые буквы слов в нижнем регистре);
- /pw\\_532 (пять слов, три цифры, две буквы из каждого слова фразы используются в пароле, первые буквы слов в нижнем регистре);
- /pw\\_5431 (пять слов, четыре цифры, три буквы из каждого слова фразы используются в пароле, первые буквы слов в верхнем регистре);
- /pw\\_40411 (четыре слова, без цифр, четыре буквы из каждого слова фразы используются в пароле, первые буквы слов в верхнем регистре, использовать транслит).

В парольной фразе круглыми скобками выделены те буквы и цифры, которые используются в пароле.
EOD;

/* Formats */
define (MD,"Markdown");
define (HTML,"HTML");

function processUpdate ($json) {
  $update = json_decode($json, true);
  if (!empty($json)) {
    $update_id = $update['update_id'];
   
    $user_id = $update['message']['from']['id'];
    $user_username = isset($update['message']['from']['username']) ? $update['message']['from']['username'] : "";
    $user_fname = isset($update['message']['from']['first_name']) ? $update['message']['from']['first_name'] : "";
    $user_lname = isset($update['message']['from']['last_name']) ?  $update['message']['from']['last_name'] : "";
   
    $chat_id = $update['message']['chat']['id'];
    $chat_type = $update['message']['chat']['type'];
    $chat_title = $update['message']['chat']['title'];
   
    $date = date('Y-m-d H:i:s', $update['message']['date']);
   
    if ($update['message']['new_chat_member']) {
      if ($update['message']['new_chat_member']['id'] == BOTID) {
        $event = "bot added to group";
      }
      $event = "bot is a member of group";
    }
   
    if ($update['message']['left_chat_member']) {
      if ($update['message']['new_chat_member']['id'] == "116320087") {
        $event = "bot deleted from group";
      }
      //bot is a member of group
    }
   
    if ($update['message']['new_chat_title'] ||
        $update['message']['new_chat_photo'] ||
        $update['message']['delete_chat_photo']) {
      $event = "bot is a member of group";
    }

    
    if (intval($chat_id) < 0) {
      $event = "bot is a member of group";
    }
    
   
    $text = isset($update['message']['text']) ? $update['message']['text'] : "";
  //isset(VAR) ? VAR : ''
   
    $entities_type = $update['message']['entities'][0]['type'];
   
    if ($entities_type = 'bot_command') {
      $event = "bot command";
      $debug = False;
      $del = "_";
      processCommand($chat_id, $user_id, $text, $del, $debug);
    }
   
    if (isset($event)) {
      $data = array (
                    "TEL",
                    $date,
                    $user_id,
                    $user_username,
                    $user_fname . " " . $user_lname,
                    $chat_id,
                    $chat_type,
                    $chat_title,
                    $event,
                    $text
                  );
      logData($data);
    } else {
      logData($update,False);
    }
file_put_contents("./log.txt","\n".var_export($update,true)."\n",FILE_APPEND);
  } else {
    exit;
  }
}

function processGET ($GET) {
  debugEcho("Processing GET");
  header("Content-Type: text/plain");
  if (isset($_GET['msg']) && !empty($_GET['msg'])) {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    if ($_GET['msg'] == "sess") {
      header("Content-Type: text/plain");
      echo "Session Save Path: " . ini_get( 'session.save_path');
      echo "\nSession gc_maxlifetime: " . ini_get( 'session.gc_maxlifetime');
      echo "\nSession gc_probability: " . ini_get( 'session.gc_probability');
      echo "\nSession gc_divisor: " . ini_get( 'session.gc_divisor');
      echo "\n";
      print_r(scandir(session_save_path()));
      print_r (gc_collect_cycles());
    } else {
      $message = "/" . $_GET['msg'];
      $chat    = isset($_GET['chat']) ? $_GET['chat'] : "";
      $user    = isset($_GET['user']) ? $_GET['user'] : "";

      $debug = True;
      $del = "_";
      processCommand($chat, $user, $message, $del, $debug);
    }
    $data = array (
                    "GET",
                    date('Y-m-d H:i:s'),
                    $ip,
                    $_GET['msg'],
                    $chat,
                    $user,
                  );
    logData($data);
file_put_contents("./log.txt","\n".var_export($update,true)."\n",FILE_APPEND);
  } else {
    exit;
  }
}

function processCommand ($chat_id, $user_id, $text, $del, $debug) {
  debugEcho("Processing message");
  debugEcho(($debug ? "it's debug" : "it's not debug"));
  session_id($chat_id);
  session_name($chat_id);
  session_cache_expire(1);
  session_start();

  if (strpos($text, $del) !== false) {
    list($command, $argument) = explode($del, $text, 2);
  } else {
    $command=$text;
    $argument='';
  }

  switch ($command) {
    case "/start":
    case "/start@FlimFlamBot":
        sendMessage(START, $chat_id, $debug, MD);
        break;
    case "/help":
    case "/help@FlimFlamBot":
      $reply = prepareHelp($argument);
      sendMessage($reply, $chat_id, $debug, MD);
      break;
    case "/pw":
    case "/pw@FlimFlamBot":
      $reply = preparePw($argument);
      sendMessage($reply, $chat_id, $debug, MD);
      break;
    case "/ff":
    case "/ff@FlimFlamBot":
      $reply = prepareFf($argument);
      sendMessage($reply. ".", $chat_id, $debug, MD);
      break;
    case "/ch":
    case "/ch@FlimFlamBot":
      $reply = prepareCh($argument, $debug);
      sendMessage("_" . $reply . "_", $chat_id, $debug, MD, $_SESSION['count']);
      break;
    default:
        sendMessage("Мне не понятно, что ты хотел этим сказать: " . $message, $chat_id, $debug, MD);
  }
  session_write_close();
}

function prepareHelp ($argument) {
  debugEcho("Preparing help");
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
  return $reply;
}

function preparePw ($argument) {
  debugEcho("Preparing pw");
  $tmp_reply = getPwGen("format=pure&pc=1&args=" . $argument . "&hl=()");
  $tmp_reply = explode(" ", $tmp_reply, 2);
  $len = strlen($tmp_reply[0]);
  $reply = "*Пароль:*" . PHP_EOL;
  $reply .= "`" . $tmp_reply[0] . "`" . PHP_EOL;
  $reply .= "*Фраза:*" . PHP_EOL;
  $reply .= $tmp_reply[1] . PHP_EOL;
  $reply .= "*Длина:*" . PHP_EOL;
  $reply .= $len;
  return $reply;
}

function prepareFf ($argument) {
  debugEcho("Preparing ff");
  $wc = rand(3,5);
  $dc = rand (0,2);
  $reply = getPwGen("format=sentences&pc=1&wc=" . $wc . "&dc=" .$dc);
  $reply = trim($reply);
  $reply = mb_strtoupper(mb_substr($reply, 0, 1)) . mb_substr($reply, 1, mb_strlen($reply));
  return $reply;
}

function prepareCh ($argument,$debug) {
  debugEcho("Preparing ch");
  debugEcho(($debug ? "it's debug" : "it's not debug"));
  if (isset($_SESSION['count'])) {
    $count = $_SESSION['count'];
  } else {
    $count = 1;
  }
   
  if ($debug) {debugEcho("It's debug");echo "( " . $count . " )\n";}
   
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
  $count++;
  $_SESSION['count'] = $count;
  return $reply;
}

function sendMessage($string, $chat, $debug, $format, $extra = "") {
debugEcho("Sendig message");
  if ($debug) {
    echo $string."\n\n";
    echo urlencode($string)."\n\n";
    $request = 'https://api.telegram.org/botTOKEN/sendMessage?disable_web_page_preview=1&chat_id=' . $chat . '&parse_mode=' . $format . '&text=' . $string;
    echo $request;
    echo $extra;
  } else {
    $string = urlencode($string);
    $request = 'https://api.telegram.org/bot' . TOKEN . '/sendMessage?disable_web_page_preview=1&chat_id=' . $chat . '&parse_mode=' . $format . '&text=' . $string;
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

function debugEcho ($string) {
  echo $string . "\n";
}

function logData ($data, $flag = True) {
  if ($flag) {
    $string = implode(" | ",$data);
    file_put_contents('./log.txt',"\n".$string,FILE_APPEND);
  } else {
    file_put_contents("./log.txt","\n".var_export($data,true)."\n",FILE_APPEND);
  }
  /*
  GET string
  file_put_contents('./log.txt',"\n".var_export($_GET,true)."\n".var_export($ip,true)."\n".date('Y-m-d H:i:s')."\n",FILE_APPEND);
  JSON string
  file_put_contents("./log.txt","\n".var_export($update,true)."\n",FILE_APPEND);
  */
}

debugEcho("Starting");

if ($_GET) {
  debugEcho("It's GET");
  processGET($_GET);
} elseif ($json = file_get_contents("php://input")) {
  processUpdate($json);
} else {
  header("Location: https://telegram.me/FlimFlamBot");
  exit;
}


/*
function NAME () {
}
*/
?>