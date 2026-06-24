$(function () {
    toastr.options.timeOut = 10 * 1000;
    window.currentUrl = ((window.location.href.split('?')[0]).split('#')[0]).replace(/\/$/, "");
    window.submitAjaxForm = function (settings, form) {
        var $this = form;
        var ajaxCallSettings = {
            url: $this.attr('action'),
            method: $this.attr('method').toUpperCase(),
            data: $this.serialize(),
            onSuccess: function (response) {
                notify(response);
            },
            onFailure: function (xhr) {
                handleHttpErrors(xhr, $this);
            },
            onComplete: function (response) {
                /*if (response['readyState'] !== 0) {}*/
                $('button[type=submit]', $this).removeAttr('disabled');
            }
        };
        $('button[type=submit]', $this).attr('disabled', true);
        ajaxCall($.extend(ajaxCallSettings, settings));
    }

    window.numberFormat = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: window.userCurrency,
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    $('a').each(function () {
        var anchor = $(this);
        if (anchor.attr('href') === window.currentUrl) {
            anchor.addClass('active');
            anchor.parent('li').addClass('active current-menu-item');
        }
    });

    /* Checkbox toggling */
    $("input.toggle-btn").on('click', function () {
        var toggleBtn = $(this);
        var checkBoxes = $(toggleBtn.data('toggle'));
        checkBoxes.prop("checked", !toggleBtn.prop("checked"));
        checkBoxes.trigger('change');
    });

    /* Ajax Form Submission */
    $('form.ajax-form').on('submit', function (e) {
        e.preventDefault();
        window.submitAjaxForm({}, $(e.target));
    });

    $('.logout-button').on('click', function () {
        window.submitAjaxForm({}, $('form#logout-form'));
    });
})
