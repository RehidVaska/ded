<?php
// Provera PHP verzije
$phpVersion = phpversion();
echo "PHP verzija: $phpVersion\n";

// Provera da li je PDO SQLite instaliran
if (extension_loaded('pdo_sqlite')) {
    echo "PDO SQLite je instaliran na serveru.\n";
} else {
    echo "PDO SQLite nije instaliran na serveru.\n";
}

// Provera Python verzije
$pythonVersion = shell_exec('python --version 2>&1');
echo "Python verzija: $pythonVersion\n";

// Provera MySQL verzije (pretpostavljamo da koristite MySQLi ekstenziju)
if (extension_loaded('mysqli')) {
    $mysqli = new mysqli("localhost", "korisnik", "sifra", "baza");
    if ($mysqli->connect_error) {
        die("Greška pri konekciji: " . $mysqli->connect_error);
    }
    $mysqlVersion = $mysqli->server_info;
    echo "MySQL verzija: $mysqlVersion\n";
    $mysqli->close();
} else {
    echo "MySQLi ekstenzija nije instalirana.\n";
}
?>