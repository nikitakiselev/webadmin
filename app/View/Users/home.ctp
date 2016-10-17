<script type="text/javascript">
    var webURL = "<?php echo $this->webroot; ?>";
</script>
<style>
    .glyphicon { margin-right:5px; }
    .thumbnail
    {
        margin-bottom: 20px;
        padding: 0px;
        -webkit-border-radius: 0px;
        -moz-border-radius: 0px;
        border-radius: 0px;
    }

    .item.list-group-item
    {
        float: none;
        width: 100%;
        background-color: #fff;
        margin-bottom: 10px;
    }
    .item.list-group-item:nth-of-type(odd):hover,.item.list-group-item:hover
    {
        background: #428bca;
    }

    .item.list-group-item .list-group-image
    {
        margin-right: 10px;
    }
    .item.list-group-item .thumbnail
    {
        margin-bottom: 0px;
    }
    .item.list-group-item .caption
    {
        padding: 9px 9px 0px 9px;
    }
    .item.list-group-item:nth-of-type(odd)
    {
        background: #eeeeee;
    }

    .item.list-group-item:before, .item.list-group-item:after
    {
        display: table;
        content: " ";
    }

    .item.list-group-item img
    {
        float: left;
    }
    .item.list-group-item:after
    {
        clear: both;
    }
    .list-group-item-text
    {
        margin: 0 0 11px;
    }

    #chatbar{
        display: block; width: auto; position: fixed; right: 0px; bottom: 0px; float: right;
    }

    #chatbar .welcome {
        float: left; padding: 5px; width: 100%; border-bottom: 1px solid #000;
        margin: -9px auto 12px;
    }

    #chatbar .messageBox
    {
        position: relative;
        float: right;
        text-align:left;
        margin:0 13px 3px 0;
        padding:10px;
        background: #f0d459;
        height:269px;
        width:235px;
        border:1px solid #ACD8F0;
    }
    .msgbox{
        border: 1px solid rgb(0, 0, 0); width: 100%; overflow: auto; height: 74%; padding: 10px; text-align: left; background: rgb(255, 255, 255) none repeat scroll 0% 0%; font-size: 12px;
    }
    .grid-group-item .loading{display:none;  

                              border: 1px solid #DDDDDD;

                              height: 403px;
                              margin: 0 auto;
                              padding-top: 50%;
                              text-align: center;
                              width: 100%;}
    .list-group-item .loading{display:none;  

                              border: 1px solid #DDDDDD;

                              height: 250px;
                              margin: 0 auto;
                              padding-top: 10%;
                              text-align: center;
                              width: 100%;}
    .loading{display: none;}
    .loading2{display: none;}
</style>

<h4>30 second pitches</h4>
<section class="admin-content">
    <div id="player">
        <video id='myfileplayer' src='<?php echo @$datalist[0]["video_url"]; ?>' controls></video>
    </div>
    <table id="videoDetails">
        <tr>
            <th id="username"><?php echo @$datalist[0]["user_name"]; ?></th>
            <th id="duration"><?php echo @$datalist[0]["timeDiff"]; ?></th>
        </tr>
        <tr>
            <th colspan="2" align="center">
        <div style="float: left"><div id="prd"></div>
            <input type="submit" name="sbmRating" value="Submit"/>
        </div>
        <div style="float: right"> <a href="<?php echo @$datalist[0]["pitch_deck"]; ?>" id="pitch_deck" <?php
            if ($datalist[0]["pitch_deck"] == '') {
                echo "style='display:none'";
            }
            ?>><?php echo $this->Html->image('defult_doc.png', array('width' => "40", 'style' => "float:right")); ?></a></div>

        </th>
        </tr>
    </table>
    <div class="container">
        <div class="well well-sm">
            <strong>30 second pitches</strong>
            <div class="btn-group">
                <a href="#" id="list" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-th-list">
                    </span>List</a> <a href="#" id="grid" class="btn btn-default btn-sm"><span
                        class="glyphicon glyphicon-th"></span>Grid</a>
            </div>
        </div>
        <div id="products" class="row list-group products-row">

            <?php
            if (!empty($datalist)) {
                $i = 1;
                foreach ($datalist as $list) {
                    ?>
                    <div class="item  col-xs-4 col-lg-4" id="<?php echo $list['objectId'] ?>">
                        <div class="thumbnail">
                            <a href="#" onclick="doNav('<?php echo $list['video_url'] ?>', '<?php echo $list['user_name'] ?>', '<?php echo $list['timeDiff'] ?>', '<?php echo $list['pitch_deck'] ?>')">
                                <img class="group list-group-image" src="<?php echo $list['thumb']; ?>" alt="" style="height: 250px" />
                            </a>
                            <div class="caption">
                                <h4 class="group inner list-group-item-heading">
                                    <?php //echo $list['video_name']   ?></h4>
                                <p class="group inner list-group-item-text">
                                    Publish by : <?php echo $list["user_name"]; ?> 
                                    <br>
                                    <span class="prd"></span>
                                    <input type="submit" name="sbmRating" value="Submit"/>
                                </p>
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <p class="lead">
                                            Duration : <?php echo $list["timeDiff"]; ?></p>
                                    </div>
                                    <div class="col-xs-12 col-md-7">
                                        <a class="btn btn-success" href="#" onclick="doNav('<?php echo $list['video_url'] ?>', '<?php echo $list['user_name'] ?>', '<?php echo $list['timeDiff'] ?>', '<?php echo $list['pitch_deck'] ?>')">View Video</a>
                                   
                                            <?php if(isset($AuthUser['User']['type']) && $AuthUser['User']['type']!='subscriber'){?>
                                        <a class="btn btn-warning" href="javascript:void(0);" onclick="doDelete('<?php echo $list['objectId'] ?>')">Delete</a>
                                        <?php }?>


                                    </div>
                                    <div class="col-xs-6 col-md-5" <?php if(isset($AuthUser['User']['type']) && $AuthUser['User']['type']!='subscriber'){?>style="margin: 10px 0 0 44px;"<?php }else{?>style="margin: 10px 0 0 0px;"<?php }?>>
                                        <a class="btn btn-info" href="javascript:void(0);" onclick="doDownload('<?php echo $list['objectId'] ?>','<?php echo $list['video_url'] ?>')">Download</a>

                    <?php if(isset($AuthUser['User']['type']) && $AuthUser['User']['type'] === 'investor'){?>
                                        <a href="#"
                                           class="btn btn-danger"
                                           data-toggle="collaboration"
                                           data-objectid="<?php echo $list['objectId'] ?>"
                                           data-username="<?php echo $list["user_name"]; ?>"
                                        >
                                            Collaboration
                                        </a>
                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="loading">Deleting...</div>
                        <div class="loading2">Downloading...</div>
                    </div>
                    <?php
                    $i++;
                }
            } else {
                ?>

                <p style="margin: 0 0 10px 15px;">No record found!</p>


                <?php
            }
            ?>
        </div>
    </div>
    <div id="targetDiv"></div>
    <div id="chatbar" style="overflow-y: hidden; height: 288px; display: inline; overflow-x: scroll; width: auto;"></div>

    <?php if(isset($AuthUser['User']['type']) && $AuthUser['User']['type'] === 'investor'){?>
    <?php print $this->element('message_popup'); ?>
    <?php } ?>
</section>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script>
                                    $(document).ready(function () {
                                        $('#list').click(function (event) {
                                            event.preventDefault();
                                            $('#products .item').addClass('list-group-item');
                                        });
                                        $('#grid').click(function (event) {
                                            event.preventDefault();
                                            $('#products .item').removeClass('list-group-item');
                                            $('#products .item').addClass('grid-group-item');
                                        });
                                    });
</script>
<script>
    function doDelete(objectId) {
        if (confirm("Are you sure want to delete this pitch")) {
            jQuery('#' + objectId).find('.thumbnail').hide()
            jQuery('#' + objectId).find('.loading').show()
            jQuery.ajax({
                url: webURL + "users/removePitch", data: {
                    objId: objectId,
                    async: true,
                },
                type: 'POST',
                success: function (result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.status == 'success') {
                        jQuery('#' + objectId).remove();
                        alert('Pitch deleted successfully')
                    } else {
                        alert(obj.msg)
                        jQuery('#' + objectId).find('.thumbnail').show()
                        jQuery('#' + objectId).find('.loading').hide()
                    }
                }});
        }
    }
    function doDownload(objectId,file) {
        jQuery('#' + objectId).find('.thumbnail').hide()
        jQuery('#' + objectId).find('.loading2').show()
        setInterval(function () {
            if ($.cookie("fileLoading")) {
                // clean the cookie for future downoads
                $.removeCookie('fileLoading', {path: '/'});                
                setTimeout(function () {
                    jQuery('#' + objectId).find('.thumbnail').show()
                    jQuery('#' + objectId).find('.loading2').hide()
                }, 1000)

            }

        }, 1000);
        var iframe = $('<iframe>');
        iframe.attr('src', webURL + "users/download?video=" + file);
        $('#targetDiv').append(iframe);

    }

    function doNav(theUrl, thename, thediff, pitch_deck)
    {
        var myDiv = $("#player");
        var usernameContainer = $("#username");
        var timediffContainer = $("#duration");
        var myvideo = $("<video id='myfileplayer' src='" + theUrl + "' controls></video>");
        if (pitch_deck != '') {
            $("#pitch_deck").show();
            $("#pitch_deck").attr('href', pitch_deck);
        } else {
            $("#pitch_deck").hide();
        }

        myDiv.empty();
        myDiv.append(myvideo);

        usernameContainer.empty();
        usernameContainer.append(thename);

        timediffContainer.empty();
        timediffContainer.append(thediff);

        $("#myfileplayer").on("click", function () {
            $(this).play();
        });
    }

    $(function () {
        $('#prd').raty({
            number: 10,
            starOff: webURL + '<?php echo IMAGES_URL . "star-off.png"; ?>',
            starOn: webURL + '<?php echo IMAGES_URL . "star-on.png"; ?>',
            width: 180,
            scoreName: "score",
            score: 5
        });
        $('.prd').raty({
            number: 10,
            starOff: webURL + '<?php echo IMAGES_URL . "star-off.png"; ?>',
            starOn: webURL + '<?php echo IMAGES_URL . "star-on.png"; ?>',
            width: 180,
            scoreName: "score",
            score: 5
        });
    });

    /**
     * Function to check for any new message thread started
     * */
    function checkMessage()
    {
        //Ajax to check chat message
        $.ajax({
            url: webURL + "/users/check_message/",
            type: 'POST',
            dataType: 'json',
            cache: false,
            async: false,
            success: function (response) {
                if (response['status'] == "success")
                {
                    var totaldiv = response['data'];
                    var c;
                    for (c = 0; c < totaldiv.length; c++)
                    {
                        var chat_section = response['data'][c];
                        if (c == 0) {
                            // $('#chatbar').html(chat_section);
                        }
                        else {
                            // $('#chatbar').append(chat_section);
                        }
                    }
                }
            }
        });
    }

    /**
     * Function to save message send by admin
     * */
    function postMsg(msgSec)
    {
        var message = $('#userMessage' + msgSec).val();
        var sendto = $('#txtsendTo' + msgSec).val();
        var msgthrd = $('#txtChat' + msgSec).val();
        var receiver = $('#txtreceiver' + msgSec).val();

        var Imgobj = document.getElementById('senderImg' + msgSec);
        var receiverImg = Imgobj.src;

        //save message data 
        $.ajax({
            url: webURL + "/users/send_message/",
            type: 'POST',
            data: {"message": message, "sendto": sendto, "msgthrd": msgthrd, "receiver": receiver, "receiverImg": receiverImg},
            dataType: 'json',
            cache: false,
            success: function (response)
            {
                if (response['status'] == 'success')
                {
                    $('#msgbox' + msgSec).append(response['data']);
                    $('#userMessage' + msgSec).val("");
                    return false;
                }
            }
        });

        return false;
    }

    /**
     * Function to check message of the ongoing chat
     * */
    //function 

    //check for new chat in every 15 seconds
    var chatInterval = setInterval(function () {
        //   checkMessage();
    }, 10000);
</script>
