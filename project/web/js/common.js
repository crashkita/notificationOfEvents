(function () {
    'use strict';
    $('body').on('submit', '.ajax-form', function (event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
            'url': form.attr('action'),
            'data': form.serialize(),
            'method': form.attr('method'),
            'dataType': 'json',
            'success': function (response) {
                form.find('input').closest('div').removeClass('error');
                form.find('.help-block, .text-danger').html('');

                if (!response.success) {
                    Object.keys(response).map(function (field, index) {
                        var fieldElement = form.find('#' + field);

                        fieldElement.closest('div').addClass('error');
                        var helpBlock = fieldElement.closest('div').find('.help-block');
                        var errorBlock = fieldElement.closest('div').find('.text-danger');
                        if (helpBlock.length > 0) {
                            helpBlock.html(response[field].join());
                        } else {
                            if (errorBlock.length > 0) {
                                errorBlock.html(response[field].join());
                            }
                        }
                    });

                    return;
                }

                form.replaceWith('<h5 class="alert alert-success">'+response.text+'</h5>');
            },
            'error': function () {
                form.html('<h5 class="alert alert-danger">В работе сервиса произошёл сбой. Приносим свои извинения.</h5>');
            }
        });


        return false;
    });
}());

(function () {
    'use strict';
    $('body').on('click', '[data-toggle="link-modal"]', function (event) {
        event.preventDefault();
        var link = $(this);
        var modalId = '#global-modal';
        $.ajax({
            url: link.attr('href'),
            dataType: 'json',
            type: "GET",
            success: function (data) {
                $(modalId).find('.modal-title').html(data.title);
                $(modalId).find('.modal-body').html(data.body);
                $(modalId).modal('show');
            }
        });
        return false;
    });
}());