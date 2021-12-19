<?php
require_once __DIR__ . '/vendor/autoload.php';

// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
    ->setUsername('testmailer123456@mail.ru')
    ->setPassword('Q8wFFADBiVgstDpzbBZa');// пароль для внешнего приложения Q8wFFADBiVgstDpzbBZa для почты gfhjkmlkzlp123

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// Create a message
$message = (new Swift_Message('Wonderful Subject'))
    ->setFrom(['testmailer123456@mail.ru' => 'Dima'])
    ->setTo(['jefake4272@mykcloud.com'])
    ->setBody('Here is the message itself')
;

// Send the message
$result = $mailer->send($message);
var_dump($result);