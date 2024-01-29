<?php

$dsn = 'sqlite:dental.db';

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Greška pri povezivanju sa bazom: " . $e->getMessage());
}
?>
