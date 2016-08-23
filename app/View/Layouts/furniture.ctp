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
        
$siteDescription = '30 Second Pitch Admin Panel';
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $siteDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0;" />
        <script type="text/javascript">
            var webURL= "<?php echo webURL; ?>";
            var WWW_ROOT = "<?php echo WWW_ROOT; ?>";
        </script>
	<?php
            echo $this->Html->meta('icon');
        ?>            
<!--        <link rel="apple-touch-icon" sizes="57x57" href="/img/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/img/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/img/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/img/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/img/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/img/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/img/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/img/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/img/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
        <link rel="manifest" href="/img/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/img/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">-->
        <?php
            //echo $this->Html->css('cake.generic');
            echo $this->Html->css(array('style.css','admin.css', 'responsive.css'));

            echo $this->fetch('meta');
            echo $this->fetch('css');
            echo $this->fetch('script');
            //echo $this->Html->script(array("jquery-1.11.1.min.js","css3-mediaqueries.js", "jquery.validate.js", "admin.js"));
	?>
</head>
<body style="background: rgba(0, 0, 0, 0) linear-gradient(128deg, #cddddd, #cddddd) repeat fixed 0 0;">
    <div id="container">
        <div id="header" style="text-align: center;">
            <?php echo $this->Html->image('30_second_pitch_app_icon.png', array('alt' => "30 Second Pitch logo", 'border' => '0', "title"=>"30 Second Pitch logo", "class"=>"logo")) ?>   
            <!--<h1 style="font-size: 30px; font-weight: normal; color: rgb(255, 255, 255); font-family: Helvetica,sans-serif; margin: 60px 0px 30px;">30SecondPitch</h1>-->
        </div>
        <div id="content">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->fetch('content'); ?>
        </div>
    </div>
    <?php //echo $this->element('sql_dump'); ?>
</body>
</html>
