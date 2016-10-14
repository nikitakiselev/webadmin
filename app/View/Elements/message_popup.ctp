<div id="message-popup" class="message-popup">
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Collaborate with user</h3>

            <button class="btn btn-xs btn-danger pull-right" title="Close popup" data-action="close">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="panel-body">
            <p>Message receiver: <span id="receiver"></span></p>

            <form action="/message/send" method="post" class="message-form">
                <input type="hidden" name="object_id" value="">

                <div class="form-group">
                    <textarea name="message"
                              id="message"
                              placeholder="Enter your message here..."
                              class="form-control message-input"
                    ></textarea>
                </div>

                <div class="form-group">
                    <button id="sendBtn" class="btn btn-primary" data-loading-text="Sending message...">
                        <i class="fa fa-paper-plane-o"></i> Send message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>