<?php

require 'vendor/autoload.php';

use Telegram\Bot\Api;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$telegramToken = $_ENV['TELEGRAM_BOT_TOKEN'];
$telegram = new Api($telegramToken);
$telegram->addCommand(\Telegram\Bot\Commands\StartCommand::class);
$telegram->commandsHandler(true);
$telegram->run();

$update = json_decode(file_get_contents('php://input'), true);

if (isset($update['callback_query'])) {
    $callbackQuery = $update['callback_query'];
    $callbackData = $callbackQuery['data'];
    $chatId = $callbackQuery['message']['chat']['id'];

    if ($callbackData === 'sms') {
        // Ovde možete implementirati kod za slanje SMS poruke ili odgovarajuće akcije
        // Na primer, možete poslati poruku korisniku sa obaveštenjem o slanju SMS-a
        $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'Sending SMS...'
        ]);

        // Nakon toga, možete poslati dodatne poruke i koristiti Telegram bot za interakciju sa korisnikom

    } elseif ($callbackData === 'reject') {
        // Ovde možete implementirati kod za slučaj da korisnik odabere "reject"
        $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => 'You rejected the request.'
        ]);
    }
}