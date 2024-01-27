<?php

$telegramBotToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
$url = "https://api.telegram.org/bot$telegramBotToken/getUpdates";

$lastUpdateId = 0;

while (true) {
    $updateUrl = $url . "?offset=" . ($lastUpdateId + 1);
    $response = file_get_contents($updateUrl);
    $updates = json_decode($response, true);

    if ($updates["ok"] && !empty($updates["result"])) {
        foreach ($updates["result"] as $update) {
            $lastUpdateId = $update["update_id"];

            if (isset($update["callback_query"])) {
                processCallbackQuery($update["callback_query"]);
            }
            // Ovde možete dodati dodatnu logiku za obradu drugih vrsta update-ova
        }
    }

    sleep(1);
}

function processCallbackQuery($callbackQuery) {
    global $telegramBotToken;
    $callbackQueryId = $callbackQuery["id"];
    $callbackData = $callbackQuery["data"];

    $answerUrl = "https://api.telegram.org/bot$telegramBotToken/answerCallbackQuery?callback_query_id=$callbackQueryId";

    // Obrada callback podataka
    if (strpos($callbackData, 'SMS_') === 0) {
        // Logika za "SMS" dugme
        $text = "Izabrali ste SMS opciju.";
    } elseif (strpos($callbackData, 'Reject_') === 0) {
        // Logika za "Reject" dugme
        $text = "Izabrali ste Reject opciju.";
    } else {
        $text = "Nepoznata opcija.";
    }

    $answerData = [
        'callback_query_id' => $callbackQueryId,
        'text' => $text,
        'show_alert' => true // ili false, u zavisnosti od toga kako želite da se prikaže odgovor
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($answerData)
        ]
    ];

    $context = stream_context_create($options);
    file_get_contents($answerUrl, false, $context);
}

?>