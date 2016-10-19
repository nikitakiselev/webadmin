;(function ($) {

    window.showChat = function (sender, receiver, pitchId) {
        var $chatWindow = $('#investor-chat'),
            $chatForm = $('#chat-form'),
            $messages = $('#chat-messages'),
            $submitMessage = $('#submit-message');

        // reset chat
        $chatForm.off('submit').trigger('reset');

        $chatWindow.find('[name="object_id"]').val(pitchId);

        $chatWindow.addClass('showing');

        // fetch messages
        $chatForm.find('input, textarea, select').prop('readonly', true);
        $submitMessage.data('old', $submitMessage.html())
            .html($submitMessage.data('loading-text'))
            .prop('disabled', true);
        $messages.html('');

        $.post('/message/load/' + pitchId, [], function (response) {

            $messages.html('<div class="no-messages">No messages</div>');

            if (response.messages.length > 0) {
                var html = '';

                $.each(response.messages, function (index, message) {
                    var messageClass = message.sender === sender ? ' message-sender' : ' message-receiver';
                    var styles = message.color ? ` style="background-color:${message.color};"` : '';
                    html += `
                        <div class="message-row message-sender${messageClass}">
                            <div class="message-item"${styles}>
                                <div class="username">${message.sender}</div>
                                <div class="message-content">${message.content}</div>
                            </div>
                        </div>
                    `
                });

                $messages.html(html);
                $messages.animate({scrollTop: $messages.prop("scrollHeight")}, 200);
            }
        })
            .always(function () {
                $chatForm.find('input, textarea, select').prop('readonly', false);
                $submitMessage.html($submitMessage.data('old'))
                    .prop('disabled', false);
            });

        $chatForm.on('submit', function (event) {
            $chatWindow.find('no-messages').remove();

            var message = $chatForm.find('.message-input').val();

            if (message.length) {

                $chatForm.find('input, textarea, select').prop('readonly', true);
                $submitMessage.data('old', $submitMessage.html())
                    .html($submitMessage.data('loading-text'))
                    .prop('disabled', true);

                $.post($chatForm.attr('action'), $chatForm.serialize(), function (response) {

                    var styles = response.color ? ` style="background-color:${response.color};"` : '';

                    $messages.append(`
                        <div class="message-row message-sender">
                            <div class="message-item"${styles}>
                                <div class="username">${sender}</div>
                                <div class="message-content">${response.message_text}</div>
                            </div>
                        </div>
                    `);

                    $chatForm.find('.message-input').val('');
                    $messages.animate({scrollTop: $messages.prop("scrollHeight")}, 200);
                })
                    .always(function () {
                        $chatForm.find('input, textarea, select').prop('readonly', false);
                        $submitMessage.html($submitMessage.data('old'))
                            .prop('disabled', false);
                    });
            }

            event.preventDefault();
        });
    };
})(jQuery);