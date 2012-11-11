<?php

require ("common.php");
    header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html>
<!--[if IE 9 ]> <html class='ie9'> <![endif]-->
";
?>
<html>
	<head>
		<title>Register an account.</title>
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
if (isset($_SESSION['user'])) {
    header("Location: index.php");
}
if (!empty($_POST)) {
    if (empty($_POST['username'])) {
        die("Please enter a username.");
    }

    if (empty($_POST['password'])) {
        die("Please enter a password.");
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        die("Invalid E-Mail Address");
    }

    $query = "
            SELECT
                1
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

    $row = $stmt->fetch();

    if ($row) {
        die("This username is already in use");
    }

    $query = "
            SELECT
                1
            FROM users
            WHERE
                email = :email
        ";

    try {
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $_POST['email']);
        $result = $stmt->execute();
    }
    catch (PDOException $ex) {
        
        die("Failed to run query");
    }

    $row = $stmt->fetch();

    if ($row) {
        die("This email address is already registered");
    }

    $query = "
            INSERT INTO users (
                username,
                password,
                salt,
                email,
				joindate,
				avatar
            ) VALUES (
                :username,
                :password,
                :salt,
                :email,
				:joindate,
				:avatar
            )
        ";

    $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));

    $password = hash('sha256', $_POST['password'] . $salt);

    for ($round = 0; $round < 65536; $round++) {
        $password = hash('sha256', $password . $salt);
    }

    try {
		$avatar = "http://{$_SERVER['SERVER_NAME']}/avatars/default.png";
        $time = date("Y-m-d H:i:s");
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':salt', $salt);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':joindate', $time);
        $stmt->bindParam(':avatar', $avatar);
        $result = $stmt->execute();
    }
    catch (PDOException $ex) {
        
        die("Failed to run query");
    }

    header("Location: login.php");
    die("Redirecting to login.php");
}

?>
<h1>Register</h1>
<form action="register.php" method="post">
    Username:<br />
    <input type="text" name="username" value="" />
    <br /><br />
    E-Mail:<br />
    <input type="text" name="email" value="" />
    <br /><br />
    Password:<br />
    <input type="password" name="password" value="" />
    <br /><br />
    <input type="submit" value="Register" />
</form>	
				</div>
	<?php
include ("sidebar.php");
include ("footer.php");
?>
		</div>
	</body>
</html>	