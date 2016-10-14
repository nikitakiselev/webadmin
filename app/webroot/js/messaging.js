;(function ($) {

    /*
     by Nikita Kiselev
     mailme: mail@nikitakisele.ru
     @site https://nikitakiselev.ru
     */

    var $messagePopup = $('#message-popup'),
        $receiver = $messagePopup.find('#receiver'),
        $message = $messagePopup.find('#message'),
        $messageForm = $messagePopup.find('.message-form'),
        $sendButton = $messagePopup.find('#sendBtn');

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
    }

    $sendButton.on('click', function (event) {
        var $button = $(this);

        $button.prop("disabled", true)
            .data('old-html', $button.html())
            .html($button.data('loading-text'));

        $messageForm.find('input,textarea,select').prop('readonly', true);

        $.post($messageForm.attr('action'), $messageForm.serialize(), function (response) {
            console.log(response);
        }, 'json')
            .always(function () {
                $button.prop("disabled", false)
                    .html($button.data('old-html'));

                $messageForm.find('input,textarea,select').prop('readonly', false);
            })
            .error(function (error) {
                alert(error.responseText);
            });
    });

    $messagePopup.on('click', '[data-action="close"]', function (event) {
        $messagePopup.removeClass('showing');
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