<style>
    #paymentPaymentForm{margin: 0px auto; padding: 10px; width: 95%;}
    .btn-default{background: #dcdcdc -moz-linear-gradient(center top , #fefefe, #dcdcdc) repeat scroll 0 0;
                 border: 1px solid #bbb;
                 border-radius: 4px; box-shadow: 0 1px 0 rgba(255, 255, 255, 0.3) inset, 0 1px 1px rgba(0, 0, 0, 0.2);
                 color: #333; padding: 4px 8px; font-size: 100% !important;
    }
    #subscribeMaster {
        background: rgb(255, 255, 255) none repeat scroll 0 0;
        border: medium none;
        border-radius: 6px;
        font-size: 100%;       
        margin: 0 auto;       
        width: 30%;
    }
    #subscribeMaster .row{margin-top: 25px;}
    .payment-errors{color: red;}
    .overlay {
        background: #000 none repeat scroll 0 0;
        display: none;
        height: 100%;
        opacity: 0.38;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 999999;
    }
    .overlay .img {
        color: #fff;
        left: 0;
        margin: auto;
        opacity: 1;
        position: absolute;
        right: 0;
        text-align: center;
        top: 46%;
    }
</style>



<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    Stripe.setPublishableKey('pk_test_taf2wRrRDddBXXPLGsN034pG');
</script>
<script>
    $(function () {
        var $form = $('#paymentPaymentForm');
        $form.submit(function (event) {
            // Disable the submit button to prevent repeated clicks:
            $form.find('.submit').prop('disabled', true);

            // Request a token from Stripe:
            Stripe.card.createToken($form, stripeResponseHandler);

            // Prevent the form from being submitted:
            return false;
        });
    });

    function stripeResponseHandler(status, response) {
        // Grab the form:
        var $form = $('#paymentPaymentForm');

        if (response.error) { // Problem!

            // Show the errors on the form:
            $form.find('.payment-errors').text(response.error.message);
            $form.find('.submit').prop('disabled', false); // Re-enable submission

        } else { // Token was created!

            if ($('#zip').val() == '') {
                $form.find('.payment-errors').text("Please enter zipcode");
                $form.find('.submit').prop('disabled', false); // Re-enable submission
                return false;
            } else {
                jQuery('.overlay').show();
                // Get the token ID:
                var token = response.id;
              //  alert(token);
                // Insert the token ID into the form so it gets submitted to the server:
                $form.append($('<input type="hidden" name="stripeToken">').val(token));

                // Submit the form:
                $form.get(0).submit();
            }
        }
    }
    ;
</script>

<div class="overlay">
    <div class="img"><?php echo $this->Html->image('ajax-loader.gif'); ?><br>Loading...</div>
</div>
<div id="subscribeMaster">

    <?php echo $this->Form->create("payment", array('class' => 'form-horizontal', 'url' => array('action' => 'process', 'controller' => 'subscribes'))); ?>
    <?php echo $this->Session->flash(); ?>

    <h3>Subscribe to monthly plan $99/month</h3>
    <span class="payment-errors"></span>
    <div class="row">
        <fieldset class="col-xs-12">
            <label for="first_name">Card Number</label>
            <input type="text" class="form-control" id="cc" placeholder="Credit Card"  required size="20" data-stripe="number">           
        </fieldset>             
    </div>
    <div class="row">
        <fieldset class="col-xs-6">
            <label for="company" style="clear:both;display: block">Expiration (MM/YY)</label>

            <input type="text" class="form-control" data-stripe="exp_month"  size="2" maxlength="2" style="display: inline;float: left;width: 40%;" >
            <span style=" display: inline; float: left;padding: 8px;">/</span>
            <input type="text" class="form-control" data-stripe="exp_year"  size="2" maxlength="2" style="display: inline;float: left;width: 40%;" > 
        </fieldset>
        <fieldset class="col-xs-6">
            <label for="mobile_number">CVC</label>
            <input type="text" class="form-control"  size="4" data-stripe="cvc">           
        </fieldset>       
    </div>
    <div class="row">
        <fieldset class="col-xs-12">
            <label for="zipcode">Zip Code</label>
            <input type="text" class="form-control" id="zip" placeholder="Zip Code"   size="20" name="zipcode" >           
        </fieldset>             
    </div>
    <div class="row">
        <input type="submit" class="btn btn-primary" value="Click Next">
    </div>

    <?php echo $this->Form->end(); ?>
    <script>
    </script>
</div>
