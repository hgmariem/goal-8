$(function(){
    // Set public access token. Replace this with our own.
    BAPIjs.setPublicToken('891451_puZw2H22X7Wcf5ErHxDmOmr1XlnlG6OhZn');

    $("#payment-form").submit(function(event) {
      var pan = $("#payment-form .card-pan").val().trim();
              var expMonth = $("#payment-form .card-exp-month").val().trim();
              var expYear = $("#payment-form .card-exp-year").val().trim();
              var cvc = $("#payment-form .card-cvc").val().trim();
        var hasErrors = false;
        $("div.error").hide();
        $("ul.error-message").html('');

        // Preemptive input validation.
        if (BAPIjs.isValidCardNumber(pan) === false) {
            $("ul.error-message").append('<li>Invalid card number</li>');
            hasErrors = true;
        }
        if (BAPIjs.isValidExpDate(expMonth, expYear) === false) {
            $("ul.error-message").append('<li>Invalid expiration date</li>');
            hasErrors = true;
        }
        if (BAPIjs.isValidCVC(cvc) === false) {
            $("ul.error-message").append('<li>Invalid cvc number</li>');
            hasErrors = true;
        }

        if (hasErrors) {
            $("div.error").show();
        } else {
            // The function that parses the response from SaltPay.
            var borgunResponseHandler = function(status, data) {
                if (status.statusCode === 201) {
                    // OK
                    $("#payment-form").append($('<input type="hidden" name="singleToken" />').val(data.Token));
                    $("#payment-form").get(0).submit();
                } else if (status.statusCode === 401) {
                    // Unauthorized
                    $("ul.error-message").append('<li>Unauthorized received from BorgunPaymentAPI</li>');
                    $("div.error").show();
                } else if (status.statusCode) {
                    $("ul.error-message").append('<li>Error received from server ' + status.statusCode + ' - ' + status.message + '.</li>');
                    $("div.error").show();
                } else {
                    $("ul.error-message").append('<li>Unable to connect to server ' + status.message + '.</li>');
                    $("div.error").show();
                }
                // Enable Pay button again.
                $('.submit-button').prop("disabled", false);
            };

            // Disable Pay button.
            $('.submit-button').prop("disabled", true);
            // Request single use token from SaltPay.
            BAPIjs.getToken({
                'pan': pan,
                'expMonth': expMonth,
                'expYear': expYear
            }, borgunResponseHandler);
        }

        event.preventDefault();
        return false;
    });
});