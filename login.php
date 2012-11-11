<?php

require ("common.php");
    header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html>
<!--[if IE 9 ]> <html class='ie9'> <![endif]-->
";
?>
<html>
	<head>
		<title> Log in </title>
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
<?php
$submitted_username = '';
if (!empty($_SESSION['user'])) {
?>
	<form name="notice" action="index.php" method="post">
	<input type="hidden" name="notice" value="You are already logged in.">
	</form>

	<script type="text/javascript">
	document.notice.submit();
	</script>
	<?php
}
if (!empty($_POST)) {

    $query = "
            SELECT
                id,
                username,
                password,
                salt,
                email,
				avatar,
				occupation,
				location,
				website
            FROM users
            WHERE
                username = :username
        ";

    try {

        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $_POST['username']);
        $result = $stmt->execute();
    }
    catch (PDOException $ex) {
        
        die("Failed to run query");
    }

    $login_ok = false;

    $row = $stmt->fetch();
    if ($row) {

        $check_password = hash('sha256', $_POST['password'] . $row['salt']);
        for ($round = 0; $round < 65536; $round++) {
            $check_password = hash('sha256', $check_password . $row['salt']);
        }

        if ($check_password === $row['password']) {
            $login_ok = true;
        }
    }

    if ($login_ok) {
        unset($row['salt']);
        unset($row['password']);

        $_SESSION['user'] = $row;
        echo <<< EOT
<form name="notice" action="index.php" method="post">
<input type="hidden" name="notice" value="You have been logged in.">
</form>

<script type="text/javascript">
document.notice.submit();
</script>
EOT;

    } else {
        print ("Login Failed.");

        $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
    }
}
?>
<h1>Login</h1>
<div id="logindiv">
<form action="login.php" method="post">
    Username:<br />
    <input type="text" name="username" value="<?php echo $submitted_username; ?>" />
    <br /><br />
    Password:<br />
    <input type="password" name="password" value="" />
    <br /><br />
    <input type="submit" value="Login" />
</form>
<a href="register.php">Register</a>
</div>
		</div>
		<?php include_once ("sidebar.php"); //SIDEBAR ?>
		<?php include_once ("footer.php"); //FOOTER ?>
	</div>	