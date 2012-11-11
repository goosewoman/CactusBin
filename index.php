<?php require ("common.php"); 
    header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html>";
echo "<!--[if IE 9 ]> <html class='ie9'> <![endif]-->"; ?>
<html>
  <head>
    <title>Cactusbin</title>
    <link rel="stylesheet" href="http://<?php echo "{$_SERVER['SERVER_NAME']}"; ?>/css/main.css" />
    <style type="text/css">
    </style>
	<?php echo commonJs(); ?>
  </head>
  <body>
	<a href="javascript:hideNotice();">
	<div id="notice">
	</div>
	</a>
		<?php include_once ("header.php"); //HEADER ?>
	<div id="maindiv">
		<div id="content" class="main">
			<form id="notepad" action="pasteprocess.php" method="post" onsubmit='return validateForm("notepad","paste","The paste can not be empty.");'>
				<textarea form="notepad" id="notepadarea" class="main" name="paste"></textarea>
				<div id="passwordarea">
				Password: <input type="password" name="password" autocomplete="off"/>
				Title: <input class="title" type="text" maxlength="25" name="title" autocomplete="off" />
				<input type="submit" value="Submit" id="notepadbutton" form="notepad" /> 
				</div>
			</form>
		</div>
		<?php include_once ("sidebar.php"); //SIDEBAR ?>
		<?php include_once ("footer.php"); //FOOTER ?>
	<?php
if (isset($_POST['notice'])) {
    echo notice($_POST['notice']);
}
?>
	</div>
  </body>
</html>  