function setupPaystackInline(config, $form) {
    config.callback = function (response) {
        validatePayment(response, $form);
    };

    var Paystack = PaystackPop.setup(config);
    Paystack.openIframe();
}

function validatePayment(response, $form) {
    if ($.type(response) === 'object' && 'reference' in response) {
        notify({'message': 'Verifying payment, please wait...', 'status': true});

        ajaxCall({
            url: window.PaystackValidationURI,
            method: "POST",
            data: {'ref': response['reference']},
            onSuccess: function (response) {
                notify(response);
                if (typeof response['redirect'] === 'string') {
                    window.location = response['redirect'];
                }
            },
            onFailure: function (xhr) {
                handleHttpErrors(xhr, $form);
            },
            onComplete: function () {
                $('button[type=submit]', $form).removeAttr('disabled');
            }
        });
    } else {
        notify({
            'message': 'Error: payment could not be verified. Please contact support team.',
            'status': false
        });
    }
}
