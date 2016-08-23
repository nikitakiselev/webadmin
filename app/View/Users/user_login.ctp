<!DOCTYPE html>
<html>
    <head>
        <title>Facebook Login JavaScript Example</title>
        <meta charset="UTF-8">
        <script src="https://www.parse.com/downloads/javascript/parse-1.6.7.js"></script>
    </head>
    <body>    
        <script>
            // Initialize Parse
            Parse.initialize("GxT3Qbs0tWGV8ujcfmtJqwCB9QzYicdmtskmjkRd", "Wgg1OiCLDTbkIDds3K7rKfp7VjcfwHrV7T0mmcny");

            window.fbAsyncInit2 = function () {
                Parse.FacebookUtils.init({// this line replaces FB.init({
                    appId: '1682982805248581', // Facebook App ID
                    status: true, // check Facebook Login status
                    cookie: true, // enable cookies to allow Parse to access the session
                    xfbml: true, // initialize Facebook social plugins on the page
                    version: 'v2.3' // point to the latest Facebook Graph API version
                });
                Parse.FacebookUtils.logIn(null, {
                    success: function (user) {
                        console.info(user);
                        if (!user.existed()) {
                            console.info("User signed up and logged in through Facebook!");
                        } else {
                            console.info("User logged in through Facebook!");
                        }

                        FB.api('/me', function (response) {
                            if (!response.error) {
                                console.info(response);
                                // We save the data on the Parse user
                                user.set("name", response.name);
                                user.save(null, {
                                    success: function (user) {
                                        // And finally save the new score
                                        //self.saveHighScore();
                                    },
                                    error: function (user, error) {
                                        console.log("Oops, something went wrong saving your name.");
                                    }
                                });
                            } else {
                                console.log("Oops something went wrong with facebook.");
                            }
                        });

                        window.location.href = "<?php echo $this->Html->url(array("controller" => "users", "action" => "user_login")); ?>/" + user.id;
                    },
                    error: function (user, error) {
                        alert("User cancelled the Facebook login or did not fully authorize.");
                    }
                });

                // Run code after the Facebook SDK is loaded.
            };

            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {
                    return;
                }
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));



        </script>	

        <div style="text-align: center;width: 300px;" ><a href="#" onclick="window.fbAsyncInit2();"><?php echo $this->Html->image('facebook.png'); ?></a>    </div>

        <div id="status">
        </div>

        <br/>
        <div id="loginMaster">
<?php echo $this->Form->create("login", array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'login_user', 'name' => 'login_user')); ?>
            <table width="100%" style="border-collapse: separate; border-spacing: 15px; text-align: left;">
                <tr>
                    <td width="20%">Username: </td>
                    <td width="75%"><?php echo $this->Form->input('User.username', array('class' => 'form-control', 'placeholder' => __('username'), 'id' => 'username', 'div' => false, 'label' => false, 'style' => 'width:91%; font-size: 100%; padding:8px; border-radius: 5px; border: 1px solid grey;')) ?></td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td><?php echo $this->Form->input('User.password', array('class' => 'form-control', 'placeholder' => __('password'), 'id' => 'password', 'div' => false, 'label' => false, 'style' => 'width:91%; font-size: 100%; padding:8px; border-radius: 5px; border: 1px solid grey;')) ?></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right">
<?php echo $this->Form->submit('Login', array('class' => 'btn btn-default', 'div' => false, 'style' => 'background: #cddddd none repeat scroll 0% 0%;')) ?>
                    </td>
                </tr>
                <tr>
                    <td  colspan="2" style="text-align: left"><?php echo $this->Html->link("Subscriber Login", array('controller' => 'users', 'action' => 'login', 'admin' => true)) ?> <a href="<?php echo Router::url(array("controller" => "subscribes", 'action' => 'index'), true) . '/index'; ?>" style="float: right">Subscriber Registration</a></td>
                    
                </tr>
            </table>
<?php echo $this->Form->end(); ?>
        </div>

    </body>
</html>

<style>
    #login_user{margin: 0px auto; padding: 10px 0px 0px; width: 95%;}
    .btn-default{background: #dcdcdc -moz-linear-gradient(center top , #fefefe, #dcdcdc) repeat scroll 0 0;
                 border: 1px solid #bbb;
                 border-radius: 4px; box-shadow: 0 1px 0 rgba(255, 255, 255, 0.3) inset, 0 1px 1px rgba(0, 0, 0, 0.2);
                 color: #333; padding: 4px 8px; font-size: 100% !important;
    }
</style>