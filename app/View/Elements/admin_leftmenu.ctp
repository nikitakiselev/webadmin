<?php
$userImage = "avatar5.png";
?>
<aside class="left-side sidebar-offcanvas" style="min-height: 715px;">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
       <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="image" style="text-align: center">
                <?php echo $this->Html->image($userImage, array("alt"=>"User Image", "class"=>"img-circle")); ?>
            </div>  <?php  if (!empty($logged_in)) { ?>
            <!--div class="pull-right info">
                <p>Hello! <?php echo ucfirst($userInfo['User']['username']);?><br></p>
            </div--><?php }?>
        </div>
          <?php  if (!empty($logged_in)) { ?>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li>
                <?php echo $this->Html->link("Pitch Feed", array("controller"=>"users", "action"=>"home"), array("class"=>"menu_links", "rel"=>"table_box"));?>
            </li>
        </ul>
        <?php }?>
    </section>
    <!-- /.sidebar -->
</aside>