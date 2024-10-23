<!DOCTYPE html>
<html>
<head>
    <title>Stripe Payment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>

<div class="container">
    <h2 class="my-4 text-center">Stripe Payment</h2>
    
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="/process-payment" method="POST" id="payment-form">
        @csrf
        <div class="form-group">
            <label for="amount">Amount to Pay ($)</label>
            <input type="text" class="form-control" name="amount" required>
        </div>
        <div class="form-group">
            <label for="card-element">Credit or Debit Card</label>
            <div id="card-element" class="form-control">
                <!-- Stripe element will be inserted here -->
            </div>
            <div id="card-errors" role="alert"></div>
        </div>
        <button class="btn btn-primary mt-3">Submit Payment</button>
    </form>
</div>

<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    card.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        form.submit();
    }
</script>

</body>
</html>
