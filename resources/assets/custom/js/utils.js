/**
 * jQuery Extensions
 * @param {object} $
 * @returns {mixed}
 */
(function ($) {
    var GEXT = {
        clearForm: function () {
            return this.each(function () {
                var type = this.type;
                var tag = this.tagName.toLowerCase();
                if (tag === 'form') {
                    return $(':input', this)
                        .clearForm();
                }
                if (type === 'text' || type === 'password' || tag === 'textarea') {
                    this.value = '';
                } else if (type === 'checkbox' || type === 'radio') {
                    this.checked = false;
                } else if (tag === 'select') {
                    this.selectedIndex = -1;
                }
            });
        },
        currencyFormat: function () {
            this.each(function (i) {
                $(this).change(function (e) {
                    if (isNaN(parseFloat(this.value))) return;
                    this.value = parseFloat(this.value).toFixed(2);
                });
            });
            return this; //for chaining
        }
    };

    var BEXT = {
        isNumber: function (n) {
            return !isNaN(n);
        },
        isInt: function (n) {
            return $.isNumber(n) && n % 1 === 0;
        },
        /**
         * Determine if a value is a String
         *
         * @param {Object} val The value to test
         * @returns {boolean} True if value is a String, otherwise false
         */
        isString: function (val) {
            return typeof val === 'string';
        }
    };
    $.extend(BEXT);
    $.extend(GEXT);
    $.fn.extend(GEXT);
}(jQuery));

function notify(response) {
    //Remove text-* classes
    var message, mode;
    if (typeof (response['message']) !== 'undefined') {
        message = response['message'];
        mode = (typeof (response['mode']) !== 'undefined') ? response.mode : (response['status'] === true ? 'success' : 'error');
    } else {
        message = toString(response);
        mode = 'info';
    }
    toastr[mode](message);

    if (typeof response['redirect'] === 'string') {
        setTimeout(function () {
            window.location = response['redirect'];
        }, 1500);
    }
}

/**
 * Value or default
 * @param {type} value
 * @param {type} defaultValue
 * @returns {type} value
 */
function vd(value, defaultValue) {
    return (typeof (value) !== 'undefined') ? value : defaultValue;
}

function handleHttpErrors(xhr, form) {
    var response = null;
    try {
        response = $.parseJSON(xhr.responseText);
    } catch (e) {
        response = xhr.responseText;
    }
    if (xhr.status === 422) {
        if (typeof (response) !== 'object') {
            notify({'status': false, 'message': response});
        } else {
            handle422ErrorObject(form, response);
        }
    } else if (xhr.status >= 400 && xhr.status < 500) {
        if (typeof (response) === 'object') {
            notify(response);
        } else {
            notify({'status': false, 'message': response});
        }
    } else if (xhr.status >= 500 && xhr.status < 600) {
        notify({'status': false, 'message': 'Something snapped, please try again shortly.'});
    }
}

function handle422ErrorObject(form, response) {
    $(form).find('.is-invalid').removeClass('is-invalid');
    var textArr = [];
    for (var field in response['errors']) {
        if (field in response['errors']) {
            $(form).find('[name="' + field + '"]').addClass('is-invalid').focus();
            var data = $(response['errors']).prop(field);
            if (!!data && data.constructor === Array) {
                textArr.push(data.join("<br/>"));
            } else {
                textArr.push(data);
            }
        }
    }
    var notification = {
        'message': textArr.join('<br/>'),
        'status': false
    };
    notify(notification);
}

/**
 * Make ajax request
 * @param {object} settings This should contain the regular JQuery.ajax settings
 * and optionally <code>onSuccess</code>, <code>onFailure</code> and <code>onComplete</code>
 * closures which are aliases for jqXHR's <code>done</code>, <code>fail</code> and
 * <code>always</code> functions respectively.<br/><br/>
 * <b>Extra configurations</b><br/>
 * ajaxCall takes extra configurations. Add <code>extraConfig</code> object to the settings.<br/>
 * <code>extraConfig</code> properties include:<br/>
 * <b>retry</b> - Set true to resend request if fails due to connectivity, else false.<br/>
 * Note: By default, onComplete will be called after the last trial, to change this,
 * set <code>extraConfig.completeAfterRetry</code> to false.<br/>
 * <b>trials</b> - Maximum number of trials. Set value to 0 for infinite trials<br/>
 * <b>retryInterval</b> - Delay before each retry<br/><br/>
 * <b>Default Values for ajaxCall</b><br/>
 * <code>dataType: 'json'</code><br/>
 * <code>extraConfig: {retry: true,trials: 1,retryInterval: 0,completeAfterRetry: true}</code><br/>
 * @example <code>
 * var jqXHR = ajaxCall({</span><br/>
 * &nbsp;url: 'http://example.com',<br/>
 * &nbsp;data: {p: 'param'},<br/>
 * &nbsp;extraData:{<br/>
 * &nbsp;&nbsp;retry: true,<br/>
 * &nbsp;&nbsp;trials: 2<br/>
 * &nbsp;},<br/>
 * &nbsp;onSuccess: function (data) {<br/>
 * &nbsp;&nbsp;//success<br/>
 * &nbsp;},<br/>
 * &nbsp;onComplete: function () {<br/>
 * &nbsp;&nbsp;//Request complete<br/>
 * &nbsp;},<br/>
 * &nbsp;onFailure: function(){<br/>
 * &nbsp;&nbsp;//Request failed<br/>
 * &nbsp;}<br/>
 *  });
 *</code>
 * @returns {jqXHR} Returns first jqXHR object created
 */
function ajaxCall(settings) {
    var ajaxSettings = {
        dataType: 'json',
        cache: true,
        headers: {
            'Cache-Control': 'max-age=200',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    };
    var extraConfig = {
        retry: true,
        trials: 1,
        retryInterval: 5000,
        completeAfterRetry: true,
        trialCount: 0
    };
    extraConfig = $.extend(extraConfig, settings['extraConfig']);

    //Merge settings
    Object.keys(settings).forEach(function (key) {
        if (key !== 'onSuccess'
            && key !== 'onFailure'
            && key !== 'onComplete'
            && key !== 'extraConfig') {
            var s = [];
            s[key] = settings[key];
            ajaxSettings = $.extend(ajaxSettings, s);
        }
    });

    var r = $.ajax(ajaxSettings);
    if (typeof (settings['onSuccess']) === 'function') {
        r.done(settings['onSuccess']);
    }
    if (typeof (settings['onComplete']) === 'function') {
        (function (completeAfterRetry) {
            r.always(function (jqXHR, status, statusText) {
                if (jqXHR['readyState'] !== 0 || !completeAfterRetry) {
                    settings['onComplete'](jqXHR, status, statusText);
                }
            });
        }(extraConfig.completeAfterRetry));
    }
    r.fail(function (response, status, statusText) {
        if (response['status'] === 401) {
            window.location = window.loginUrl;
        } else {
            if (response['readyState'] === 0) {
                if (extraConfig['retry']) {
                    extraConfig['trialCount']++;
                    if (extraConfig['trialCount'] === extraConfig['trials']) {
                        extraConfig['retry'] = false;
                        extraConfig['completeAfterRetry'] = false;
                    }
                    //Repeat request
                    setTimeout(function () {
                        ajaxCall($.extend(settings, {extraConfig: extraConfig}));
                    }, extraConfig['retryInterval']);
                    return;
                } else {
                    toastr.error('Connection error!');
                }
            }

            if (typeof (settings['onFailure']) === 'function') {
                settings['onFailure'](response, status, statusText);
            }
        }

    });
    return r;
}

function isAndroidStockBrowser(userAgent) {
    return (
        userAgent.indexOf('Mozilla/5.0') > -1 &&
        userAgent.indexOf('Android ') > -1 &&
        userAgent.indexOf('AppleWebKit') > -1 &&
        userAgent.indexOf('Chrome') === -1
    );
}
