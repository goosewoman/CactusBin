<?php require("common.php"); 
    header('Content-Type: text/html; charset=utf-8');
	echo "<!DOCTYPE html>
<!--[if IE 9 ]> <html class='ie9'> <![endif]-->
";?>
<html>
  <head>
  <link rel="stylesheet" href="http://<?php echo "{$_SERVER['SERVER_NAME']}"; ?>/css/main.css" />
  <?php echo commonJs(); ?>
  </head>
  <body>
  <a href="javascript:hideNotice();">
	<div id="notice">
	</div>
	</a>
	<?php include_once("header.php"); //HEADER ?>
	<div id="maindiv">
		<div id="content" class="main">
		</div>
		<?php include_once("sidebar.php"); //SIDEBAR ?>
		<?php include_once("footer.php"); //FOOTER ?>
	</div>	
  </body>
</html>  