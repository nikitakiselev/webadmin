<h4>30 Second Pitch</h4>
<section class="admin-content">
    <div id="player">
        <video id='myfileplayer' src='<?php echo @$datalist[0]["video_url"]; ?>' controls></video>
    </div>
    <!--table id="videoDetails">
        <tr>
            <th id="username"><?php echo @$datalist[0]["user_name"]; ?></th>
            <th id="duration"><?php echo @$datalist[0]["timeDiff"]; ?></th>
        </tr>
        <tr>
            <th colspan="2" align="center">
                <div id="prd"></div>
                <input type="submit" name="sbmRating" value="Submit"/>
            </th>
        </tr>
    </table-->
    <div style="overflow: hidden; clear: both " class="form">
        <table>
            <tr>
                <th colspan="2"></th>
            </tr>
        
    <?php
        if(!empty($datalist))
        {
            /*$i = 1;
            foreach($datalist as $list)
            {
    ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><a href="javascript:void(0)" onclick="doNav('<?php echo $list['video_url'] ?>', '<?php echo $list['user_name'] ?>', '<?php echo $list['timeDiff'] ?>')">
                    <?php echo $list['video_name']?></a>
                </td>
            </tr>
    <?php
                $i++;
            }*/
        }
        else
        {
            echo "<tr><td colspan='2'>No List found!</td></tr>";
        }
    ?>
        </table>
    </div>
</section>
<div style="text-align: center;margin-top: 10px;"><a href="http://your30secondpitch.com" target="_top">Create Your 30 Second Pitch</a></div>
<script>
function doNav(theUrl, thename, thediff)
{
    var myDiv = $("#player");
    var usernameContainer = $("#username");
    var timediffContainer = $("#duration");
    var myvideo = $("<video id='myfileplayer' src='"+ theUrl +"' controls></video>");

    myDiv.empty();
    myDiv.append(myvideo);

    usernameContainer.empty();
    usernameContainer.append(thename);
    
    timediffContainer.empty();
    timediffContainer.append(thediff);

    $("#myfileplayer").on("click", function(){
        $(this).play();
    });
}

$(function() {
    $('#prd').raty({
        number: 10, 
        starOff: webURL+'<?php echo IMAGES_URL."star-off.png"; ?>', 
        starOn: webURL+'<?php echo IMAGES_URL."star-on.png"; ?>', 
        width: 180, 
        scoreName: "score",
        score: 5
    });
});
</script>
