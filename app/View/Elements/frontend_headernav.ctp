<?php
if (isset($userInfo)) {
    $organisationName = (!empty($userInfo['User']['username'])) ? $userInfo['User']['username'] : "";
}

echo $this->Html->link(
        //$this->Html->image("logo.jpeg", array("alt"=>"EyeStylist", "style"=>"width: 72%")),
        "30 Second Pitch", "javascript:void(0)", array("title" => "30 Second Pitch", "escape" => false, "class" => "logo")
);
?>

<nav role="navigation" class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <?php 
    echo $this->Html->link(
            $this->Html->image("menu.png", array("alt" => "Menu", "style" => "width: 54%")), "#", array("role" => "button", "data-toggle" => "offcanvas", "class" => "navbar-btn sidebar-toggle", "escape" => false)
    );
    ?>
    <?php if (!empty($logged_in) || ($email != "")) { ?>
    <?php if(@$userInfo['User']['type']=='facebook'){?>
    <div style="float: left; margin-top: 15px;">
        <form action="" method="post">
            <input type="text" name="web_user_name"  ><input type="submit" value="Create User Name">   <span><?php if(@$userInfo['User']['web_user_name']!=''){ echo "User Name :".$userInfo['User']['web_user_name']   ;} ?></span>
        </form>
    </div>
    <?php }?>
    <div class="navbar-right">
        <ul class="nav navbar-nav"> <!-- Parent menu -->
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <i class="glyphicon glyphicon-user"></i>
                    <span><?php echo ucfirst($organisationName); ?> <i class="caret"></i></span>
                </a>
                <ul class="dropdown-menu" style="background: #eee none repeat scroll 0% 0%; border: 0px none; min-width: 142px;"> 
                    <!-- Menu Footer-->


                    <li class="user-footer pull-right" style="background: transparent none repeat scroll 0% 0%; border: 0px none; padding: 0px 22px;">
                        <?php echo $this->Html->link("Sign out", array("controller" => "users", "action" => "logout"), array("class" => "btn btn-default btn-flat")); ?>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <?php }?>
</nav>
