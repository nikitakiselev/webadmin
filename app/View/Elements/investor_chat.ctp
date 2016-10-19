<div id="investor-chat" class="investor-chat">

    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Collaborate with user</h3>

<!--            <button class="btn btn-xs btn-danger pull-right" title="Close chat" data-action="close">-->
<!--                <i class="fa fa-times"></i>-->
<!--            </button>-->
        </div>

        <div class="panel-body">
            <div id="chat-messages" class="messages"></div>

            <div class="input-wrapper">
                <form action="/message/send" method="post" id="chat-form">
                    <input type="hidden" name="object_id" value=""/>
                    <div class="form-group">
                        <textarea name="message" id="message" class="form-control message-input" placeholder="Type your message here..."></textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" id="submit-message" class="btn btn-primary" data-loading-text="Loading...">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>