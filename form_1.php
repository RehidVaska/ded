<!DOCTYPE html>
<html>
<head>
    <title>Forma za Telegram</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form id="telegramForm">
        <label for="name">Ime:</label><br>
        <input type="text" id="name" name="name"><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>

        <label for="message">Poruka:</label><br>
        <textarea id="message" name="message"></textarea><br>

        <button type="submit" id="submit-btn">Pošalji</button>
        <div id="spinner" style="display:none;">Učitavanje...</div>
    </form>

    <script>
        $(document).ready(function() {
            $('#telegramForm').submit(function(event) {
                event.preventDefault();
                sendToTelegram();
            });
        });

        function sendToTelegram() {
            // Prikazivanje spinnera
            $('#submit-btn').hide();
            $('#spinner').show();

            var formData = {
                'name': $('#name').val(),
                'email': $('#email').val(),
                'message': $('#message').val()
            };

            $.ajax({
                type: 'POST',
                url: 'sendToTelegram.php', // Ovde postavite putanju do vaše PHP skripte
                data: formData,
                dataType: 'json',
                encode: true
            })
            .done(function(data) {
                console.log(data); 
                // Ovdje možete obraditi odgovor
            })
            .fail(function(data) {
                console.error(data); 
                // Obrada greške
            })
            .always(function() {
                $('#spinner').hide();
                $('#submit-btn').show();
            });
        }
    </script>
</body>
</html>
