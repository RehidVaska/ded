<?php
try {
    $db = new PDO('sqlite:dental.db');
    $result = $db->query('SELECT * FROM messages');

    foreach($result as $row) {
        print_r($row);
    }
} catch (PDOException $e) {
    echo "Greška pri pristupu bazi podataka: " . $e->getMessage();
}
?>