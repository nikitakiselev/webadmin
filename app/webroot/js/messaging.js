;(function ($) {

    /*
     by Nikita Kiselev
     mailme: mail@nikitakisele.ru
     @site https://nikitakiselev.ru
     */

    var $messagePopup = $('#message-popup'),
        $popupCloseBtn = $messagePopup.find('[data-action="close"]'),
        $receiver = $messagePopup.find('#receiver'),
        $messageForm = $messagePopup.find('.message-form'),
        $sendButton = $messagePopup.find('#sendBtn'),
        canClose = true; // can close popup

    function showMessagePopup($button, username, objectid) {
        $receiver.html(username);
        $messageForm.find('[name="object_id"]').val(objectid);

        var $window = $(window),
            clientWidth = $window.outerWidth(),
            clientHeight = $window.outerHeight(),
            $item = $button.closest('.item'),
            itemOffset = $item.offset(),
            itemWidth = $item.outerWidth(),
            itemHeight = $item.outerHeight(),
            messagesHeight = $messagePopup.outerHeight(),
            messagesWidth = $messagePopup.outerWidth(),
            diffHeight = itemHeight - messagesHeight,
            messagesLeftOffset = 0,
            messagesTopOffset = 0,
            messagesLeft = itemOffset.left + itemWidth + messagesLeftOffset;

        if (itemOffset.left + itemWidth + messagesWidth > clientWidth) {
            messagesLeft = itemOffset.left - messagesWidth - messagesLeftOffset;
        }

        if (messagesLeft < 0) {
            messagesLeft = 0;
        }

        var messagesTop = diffHeight > 0
            ? itemOffset.top + Math.abs(diffHeight / 2) + messagesTopOffset
            : itemOffset.top - Math.abs(diffHeight / 2) - messagesTopOffset;

        $('html, body').animate({
            scrollTop: itemOffset.top - ((clientHeight - itemHeight) / 2)
        }, 500);

        $messagePopup.addClass('showing');

        $messagePopup.css({
            top: messagesTop,
            left: messagesLeft
        });

        $messageForm
            .trigger('reset')
            .find('.alert')
            .remove();
    }

    function showMessage(message, status) {

        status = status || 'success';

        $messageForm.find('.alert').remove();

        var $message = $('<div>').hide();

        $message
            .addClass('alert alert-'+status)
            .html(message);

        $messageForm.prepend($message);
        $message.slideDown();
    }

    $sendButton.on('click', function (event) {
        var $button = $(this);

        $button.prop("disabled", true)
            .data('old-html', $button.html())
            .html(
                '<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>' +
                $button.data('loading-text')
            );

        $messageForm.find('.alert').remove();
        $messageForm.find('input,textarea,select').prop('readonly', true);
        $popupCloseBtn.prop("disabled", true);

        canClose = false;

        $.post($messageForm.attr('action'), $messageForm.serialize(), function (response) {
            showMessage(response.message, response.status);

            if (response.status === 'success') {
                $messageForm.trigger('reset');
            }
        }, 'json')
            .always(function () {
                $button.prop("disabled", false)
                    .html($button.data('old-html'));

                $messageForm.find('input,textarea,select').prop('readonly', false);

                canClose = true;
                $popupCloseBtn.prop("disabled", false);
            })
            .error(function (error) {
                alert(error.responseText);
            });
    });

    $messagePopup.on('click', '[data-action="close"]', function (event) {
        if (canClose) {
            $messagePopup.removeClass('showing');
        }

        event.preventDefault();
    });

    $('body').on('click', '[data-toggle="collaboration"]', function (event) {
        var $button = $(this),
            username = $button.data('username'),
            objectid = $button.data('objectid');

        showMessagePopup($button, username, objectid);

        event.preventDefault();
    });

})(jQuery);