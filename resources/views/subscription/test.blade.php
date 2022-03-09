<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <title>BorgunPayment.js example</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script type="text/javascript" src="https://test.borgun.is/resources/js/borgunpayment-js/borgunpayment.v1.min.js"></script>
</head>
<body>
     <div class="container">
    <div class='row'>
        <div class='col-md-4'></div>
        <div class='col-md-4'>
          <form action="" id="payment-form" method="post">
            <div class='form-row'>
              <div class='col-xs-12 form-group required'>
                <label class='control-label'>Cardholder name</label>
                <input class='form-control' id="card-name" size='4' type='text'>
              </div>
            </div>
            <div class='form-row'>
              <div class='col-xs-12 form-group required'>
                <label class='control-label'>Card Number</label>
                <input autocomplete='off' class='form-control card-pan' size='19' type='text'>
              </div>
            </div>
            <div class='form-row'>
              <div class='col-xs-4 form-group required'>
                <label class='control-label'>CVC</label>
                <input autocomplete='off' class='form-control card-cvc' placeholder='XXX' size='4' type='text'>
              </div>
              <div class='col-xs-4 form-group required'>
                <label class='control-label'>Expiration</label>
                <input class='form-control card-exp-month' placeholder='MM' size='2' type='text'>
              </div>
              <div class='col-xs-4 form-group required'>
                <label class='control-label'> </label>
                <input class='form-control card-exp-year' placeholder='YYYY' size='2' type='text'>
              </div>
            </div>
            <div class='form-row'>
              <div class='col-md-12 form-group'>
                <button class='form-control btn btn-primary submit-button' type='submit'>Pay »</button>
              </div>
            </div>
            <div class='form-row'>
              <div class='col-md-12 error form-group' style="display: none">
                <div class='alert-danger alert'>
                  Please correct the errors and try again.
                  <ul class="error-message" style="font-weight: bold"></ul>
                </div>

              </div>
            </div>
          </form>
        </div>
        <div class='col-md-4'></div>
    </div>
</div>
<script type="text/javascript" src="{{ URL::asset('/js/payment.js') }}"></script>
</body>
</html>