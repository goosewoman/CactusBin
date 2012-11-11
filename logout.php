<?php
require ("common.php");
    header('Content-Type: text/html; charset=utf-8');
session_destroy();
?>
<html>
	<head>
		<title> Notepad Logout </title>
		<link rel="stylesheet" href="http://<?php echo "{$_SERVER['SERVER_NAME']}"; ?>/css/main.css" />
		<style type="text/css">
		</style>
		<?php echo commonJs(); ?>
	</head>
	<body>
		<form name="notice" action="index.php" method="post">
		<input type="hidden" name="notice" value="You have been logged out.">
		</form>
		<script type="text/javascript">
		document.notice.submit();
		</script>
	</body>
</html>  