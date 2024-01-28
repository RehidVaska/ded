<?php
$botToken = "6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U";
$website = "https://api.telegram.org/bot" . $botToken;
$webhookUrl = "https://gardenadental.shop/bot.php"; // URL vašeg PHP skripta

$ch = curl_init($website . "/setWebhook?url=" . $webhookUrl);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>