<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Čekanje Odgovora</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function proveriOdgovor() {
            $.ajax({
                url: 'proveriOdgovor.php',
                type: 'GET',
                data: {
                    'unique_id': '<?php echo $_GET['unique_id']; ?>'
                },
                success: function(response) {
                    // Vaša postojeća logika
                },
                error: function(xhr, status, error) {
                    // Ovde možete dodati logiku za obradu greške
                    console.error("Greška u AJAX zahtevu: " + status + ", " + error);
                }
            });
        }

            var provera = setInterval(proveriOdgovor, 2000);
        });
    </script>
</head>
<body>
    <div id="status">Čekanje odgovora...</div>
</body>
</html>