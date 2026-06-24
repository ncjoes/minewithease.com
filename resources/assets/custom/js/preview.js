$(function () {
    var input = $('input.cropper-source');
    var modalWindow = $('#image-editor');
    var previewer = $('img.cropper-preview', modalWindow);
    var source, handlerUrl, attribute, prefWidth, prefHeight, initialized = false;
    var msgTimer;
    var canvasSupported = [];

    function isCanvasSupported(method) {
        if (typeof (canvasSupported[method]) !== 'undefined') {
            return canvasSupported[method];
        } else if (typeof (method) === 'undefined' && typeof (canvasSupported[0]) !== 'undefined') {
            return canvasSupported[0];
        }

        var elem = document.createElement('canvas');
        canvasSupported[0] = !!((elem.getContext) && elem.getContext('2d'));
        if (canvasSupported[0] && !!method) {
            return canvasSupported[method] = typeof (elem[method]) !== 'undefined';
        } else {
            return canvasSupported[0];
        }
    }

    function previewImage(input, image) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                image.prop('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearMessage(msgTimer) {
        clearTimeout(msgTimer);
        $('#up-loader, #up-loader .msg').slideUp();
    }

    function saveImage(previewer, saveHandler, modalWindow, formData, button) {
        if (typeof saveHandler === 'function') {
            saveHandler(previewer, modalWindow, formData);
            button.removeAttr('disabled');
            clearMessage(msgTimer);
        } else {
            ajaxCall({
                url: saveHandler,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                onSuccess: function (response) {
                    if (response.status) {
                        previewer.cropper('destroy');
                        notify(response);
                        previewer.prop('src', response.data.url);
                        $('.btn', modalWindow).prop('disabled', true);
                        $('img.cropper-destination.' + response.data['attribute']).prop('src', response.data['url']);
                        setTimeout(function () {
                            modalWindow.modal('hide');
                        }, 2000);
                    } else {
                        notify(response);
                    }
                },
                onFailure: function (xhr) {
                    handleHttpErrors(xhr, modalWindow);
                },
                onComplete: function () {
                    button.removeAttr('disabled');
                    clearMessage(msgTimer);
                }
            });
        }
    }

    function initControls(previewer, prefWidth, prefHeight, saveHandler, modalWindow, source, formDataModifier) {
        $('#zoom-in', modalWindow).on('click', function () {
            previewer.cropper('zoom', +0.1);
        });

        $('#zoom-out', modalWindow).on('click', function () {
            previewer.cropper('zoom', -0.1);
        });

        $('#reset', modalWindow).on('click', function () {
            previewer.cropper('reset');
        });

        $('#ml', modalWindow).on('click', function () {
            previewer.cropper('move', -10, 0);
        });
        $('#mr', modalWindow).on('click', function () {
            previewer.cropper('move', +10, 0);
        });
        $('#mu', modalWindow).on('click', function () {
            previewer.cropper('move', 0, -10);
        });
        $('#md', modalWindow).on('click', function () {
            previewer.cropper('move', 0, 10);
        });

        $('#save', modalWindow).on('click', function () {
            var button = $(this).attr('disabled', true);
            $('#up-loader').slideDown();
            msgTimer = setTimeout(function () {
                $('#up-loader .msg').slideDown();
            }, 7000);

            var formData = new FormData();
            //Check if HTMLCanvasElement.toBlob is supported
            if (isCanvasSupported('toBlob')) {
                //Client side cropping
                var canvas = previewer.cropper('getCroppedCanvas', {width: prefWidth, height: prefHeight});
                canvas.toBlob(function (blob) {
                    formData.append('image', blob);
                    if (typeof formDataModifier === 'function') {
                        formDataModifier(formData);
                    }
                    saveImage(previewer, saveHandler, modalWindow, formData, button);
                });
            } else {
                //Server side cropping
                var image = source.files[0];
                formData.append('image', image);
                var data = previewer.cropper('getData');
                formData.append('crop', JSON.stringify(data));
                if (typeof formDataModifier === 'function') {
                    formDataModifier(formData);
                }
                saveImage(previewer, saveHandler, modalWindow, formData, button);
            }
        });


        modalWindow.on('shown.bs.modal', function () {
            previewer.cropper({
                aspectRatio: prefWidth / prefHeight,
                viewMode: 0,
                setDragMode: 'move',
                minCropBoxWidth: prefWidth,
                autoCropArea: 1
            });
        });

        modalWindow.on('hidden.bs.modal', function () {
            clearMessage(msgTimer);
            previewer.cropper('destroy');
        });
    }

    /* Actual Processing */
    input.change(function () {
        source = $(this);
        handlerUrl = source.data('handler');
        attribute = source.data('attribute');
        prefWidth = source.data('width');
        prefHeight = source.data('height');

        previewer.prop('src', source.data('preview'));
        previewImage(this, previewer);
        $('.btn', modalWindow).prop('disabled', false);
        modalWindow.modal('show');

        if (!initialized) {
            initControls(previewer, prefWidth, prefHeight, handlerUrl, modalWindow, source, function (formData) {
                formData.append('attribute', attribute);
            });
            initialized = true;
        }
    });
});
