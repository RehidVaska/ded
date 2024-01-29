<?php
try {
    $db = new PDO('sqlite:dental.db');
    $result = $db->query('SELECT * FROM messages');

    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Unique ID</th>";
    echo "<th>Name</th>";
    echo "<th>Email</th>";
    echo "<th>Message</th>";
    echo "<th>Response</th>";
    echo "</tr>";

    foreach($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['unique_id'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['message'] . "</td>";
        echo "<td>" . $row['response'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} catch (PDOException $e) {
    echo "Greška pri pristupu bazi podataka: " . $e->getMessage();
}
?>
