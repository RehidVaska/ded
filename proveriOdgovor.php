<?php
// Preuzimanje jedinstvenog identifikatora iz GET zahteva
$uniqueId = isset($_GET['unique_id']) ? $_GET['unique_id'] : '';

if ($status === 'SMS') {
    echo 'Odgovor je SMS';
} else {
    echo 'Čekanje na odgovor'; // Ispisuje se u svim drugim slučajevima
}
?>