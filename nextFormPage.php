<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    Reservation Payment Details
                </div>
                <div class="card-body">
                    <form action="/processing" method="post">
                        <div class="form-group">
                            <label for="cardHolderName">Card Holder Name</label>
                            <input type="text" class="form-control" id="cardHolderName" name="cardHolderName" value="{{ session.get('cardHolderName', '') }}" placeholder="Card Holder Name" required>
                        </div>

                        <div class="form-group">
                            <label for="cardNumber">Card Number</label>
                            <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="Card Number" inputmode="numeric" maxlength="19">
                            <span id="cardNumberError" style="color: red;"></span>
                        </div>

                        <div class="form-row">
                             <div class="col">
                                 <label for="expiryDate">Expiry Date</label>
                                 <input type="text" class="form-control" id="expiryDate" name="expiryDate" placeholder="MM/YY" pattern="(0[1-9]|1[0-2])\/\d{2}" required>
                                 <span id="expiryDateError" style="color: red;"></span>
                             </div>
                             <div class="col">
                                 <label for="cvv">CVV/CVC</label>
                                 <input type="text" class="form-control" id="cvv" name="cvv" placeholder="CVV" pattern="\d{3,4}" maxlength="4" required>
                             </div>
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" value="25.00" min="25" step="5" required readonly>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-lock"></i> Pay $25.00
                        </button>
                    </form>
                </div>
                <div class="card-footer text-muted text-center">
                    <img src="{{url_for('static', filename='img/visa.png')}}" alt="Visa" class="img-fluid" style="max-width: 80px;">
                    <img src="{{url_for('static', filename='img/mastercard.png')}}" alt="MasterCard" class="img-fluid" style="max-width: 80px;">
                    <img src="{{url_for('static', filename='img/american_express.png')}}" alt="American Express" class="img-fluid" style="max-width: 80px;">
                    <img src="{{url_for('static', filename='img/discover.jpg')}}" alt="Discover Network" class="img-fluid" style="max-width: 80px;">
                    <img src="{{url_for('static', filename='img/JCB_logo.svg')}}" alt="JCB" class="img-fluid" style="max-width: 80px;">
                    <div>
                        <img src="{{url_for('static', filename='img/piccomplient.png')}}" alt="PCI Compliance" class="img-fluid" style="max-width: 80px;">
                        <img src="{{url_for('static', filename='img/SSL-Encryption.png')}}" alt="SSL Encryption" class="img-fluid" style="max-width: 80px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('cardNumber').addEventListener('input', function (e) {
    var target = e.target;
    var value = target.value.replace(/\D/g, '');
    var expectedLength = (value.startsWith('3') ? 15 : 16);
    if (value.length < expectedLength) {
        target.value = value.replace(/(.{4})/g, '$1 ').trim();
    } else {
        var formattedValue = value
            .padEnd(expectedLength, '_') 
            .replace(/(.{4})/g, '$1 ')   
            .trim();
        target.value = formattedValue;
    }
    if (value.length === expectedLength && !luhnCheck(value.replace(/_/g, ''))) {
        document.getElementById('cardNumberError').textContent = 'Invalid card number';
    } else {
        document.getElementById('cardNumberError').textContent = '';
    }
});

function luhnCheck(cardNo) {
    var sum = 0;
    var alternate = false;
    for (var i = cardNo.length - 1; i >= 0; i--) {
        var n = parseInt(cardNo[i], 10);
        if (alternate) {
            n *= 2;
            if (n > 9) {
                n = (n % 10) + 1;
            }
        }
        sum += n;
        alternate = !alternate;
    }
    return (sum % 10 === 0);
}
document.getElementById('expiryDate').addEventListener('input', function (e) {
    var target = e.target;
    var value = target.value;
    value = value.replace(/\D/g, '');
    if (value.length > 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    target.value = value;
    validateExpiryDate(target.value);
});
function validateExpiryDate(expiryDate) {
    var errorSpan = document.getElementById('expiryDateError');
    errorSpan.textContent = '';
    if (expiryDate.length === 5) {
        var currentYear = new Date().getFullYear() % 100;
        var currentMonth = new Date().getMonth() + 1;
        var [expMonth, expYear] = expiryDate.split('/').map(Number);
        if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) {
            errorSpan.textContent = 'The expiry date cannot be in the past.';
            document.getElementById('expiryDate').value = '';
        }
    }
}
document.getElementById('cvv').addEventListener('input', function (e) {
    var target = e.target;
    target.value = target.value.replace(/\D/g, '');
});
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>