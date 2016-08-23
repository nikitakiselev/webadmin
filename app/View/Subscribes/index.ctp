<style>
    #subscribeIndexForm{margin: 0px auto; padding: 10px; width: 95%;}
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
        width: 50%;
    }
    #subscribeMaster .row{margin-top: 25px;}
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
<div class="overlay">
    <div class="img"><?php echo $this->Html->image('ajax-loader.gif'); ?><br>Loading...</div>
</div>
<div id="subscribeMaster">

    <?php echo $this->Form->create("subscribe", array('class' => 'form-horizontal')); ?>
    <h3>Subscribe to monthly plan $99/month</h3>
    <div class="row">
        <fieldset class="col-xs-6">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" placeholder="Enter first name" name="first_name" required>           
        </fieldset>
        <fieldset class="col-xs-6">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" placeholder="Enter last name" name="last_name" required>           
        </fieldset>       
    </div>
    <div class="row">
        <fieldset class="col-xs-6">
            <label for="company">Company</label>
            <input type="text" class="form-control" id="company" placeholder="Enter company name" name="company">           
        </fieldset>
        <fieldset class="col-xs-6">
            <label for="mobile_number">Mobile Number</label>
            <input type="text" class="form-control" id="mobile_number" placeholder="Enter mobile number" name="mobile_number">           
        </fieldset>       
    </div>
    <div class="row">
        <fieldset class="col-xs-12">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
            <small class="text-muted">We'll never share your email with anyone else.</small>
        </fieldset>
    </div>
    <div class="row">        
        <fieldset class="col-xs-6">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" name="password">
        </fieldset>
        <fieldset class="col-xs-6">
            <label for="confirm_password">Re-type Password</label>
            <input type="password" class="form-control" id="confirm_password" placeholder="Re-type Password" name="confirm_password">
        </fieldset>
    </div>
    <div class="row">   
        <div class="checkbox col-xs-12">
            <label>
                <input type="checkbox" id="agree" name="agree" value="1"> I agree to terms and conditions.<br><label id="agree-error" class="error" for="agree" style="left: -38px;position: relative;"></label>
            </label>
        </div>
    </div>

    <input type="submit" class="btn btn-primary" value="Click Next">

    <?php echo $this->Form->end(); ?>
    <script>
        $.validator.addMethod(
                "regex",
                function (value, element, regexp) {
                    var re = new RegExp(regexp);
                    return re.test(value);
                },
                "Please check your input."
                );

        $("#subscribeIndexForm").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                mobile_number: {
                    regex: '^[0-9]{8,12}$'
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                },
                agree: "required"
            },
            messages: {
                first_name: "Please enter your firstname",
                last_name: "Please enter your lastname",
                mobile_number: {
                    regex: 'Please enter number between 8 and 12'
                },
                email: "Please enter a valid email address",
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                confirm_password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "Please enter the same password as above"
                },
                agree: "Please agree to terms and conditions"
            },
            submitHandler: function (form) {
                
                jQuery('.overlay').show();
                form.submit();
            }
        });</script>
</div>
