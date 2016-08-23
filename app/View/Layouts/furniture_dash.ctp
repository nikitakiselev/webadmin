<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version());

$siteDescription = "30 Second Pitch Admin Panel";
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $siteDescription ?>-
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css(array('style.css','admin.css'));
        ?>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript">
            var webURL= "<?php echo webURL; ?>";
            var WWW_ROOT = "<?php echo WWW_ROOT; ?>";
        </script>
        <?php
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- Script for jQueryUI dialog Widget -->
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
        <?php echo $this->Html->script(array("css3-mediaqueries.js", "jquery.validate.js", "jquery.raty.js", "admin.js")); ?>
</head>
<body>
    <header class="header">
    <?php echo $this->element("admin_headernav");?>
    </header>
    <!-- Main content parent div -->
    <div class="administration wrapper row-offcanvas row-offcanvas-left" style="min-height: 598px;">
        <!-- Left nav section -->
    <?php echo $this->element("admin_leftmenu"); ?>    
        <!-- Main content section -->
        <aside class="right-side">
            <?php if ($this->Session->check('Message.flash')){ ?>
                <div id="action_msg">
                    <?php echo $this->Session->flash();?>
                </div> 
                <script>
                    $("#action_msg").delay("2000").fadeOut();
                </script>
            <?php } ?> 
            <?php echo $this->fetch('content'); ?>
        </aside>
    </div>
    <?php echo $this->element('sql_dump'); ?>
</body>
</html>
