<?php
require ("common.php");
    header('Content-Type: text/html; charset=utf-8');
	echo "<!DOCTYPE html>
<!--[if IE 9 ]> <html class='ie9'> <![endif]-->
";
if (!empty($_POST)) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        die("Invalid E-Mail Address");
    }

    if ($_POST['email'] != $_SESSION['user']['email']) {
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
            die("This E-Mail address is already in use");
        }
    }

    if (!empty($_POST['password'])) {
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
        $password = hash('sha256', $_POST['password'] . $salt);
        for ($round = 0; $round < 65536; $round++) {
            $password = hash('sha256', $password . $salt);
        }
    } else {
        $password = null;
        $salt = null;
    }

    $query = "
            UPDATE users
            SET
                email = :email,
				occupation = :occupation,
				location = :location,
				website = :website
				
        ";

    if ($password !== null) {
        $query .= "
                , password = :password
                , salt = :salt
            ";
    }


    $query .= "
            WHERE
                id = :user_id
        ";

    try {
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':occupation', $_POST['occupation']);
        $stmt->bindParam(':location', $_POST['location']);
        $stmt->bindParam(':website', $_POST['website']);
        $stmt->bindParam(':user_id', $_SESSION['user']['id']);
        if ($password !== null) {
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':salt', $salt);
        }
        $result = $stmt->execute();
    }
    catch (PDOException $ex) {
        
        die("Failed to run query");
    }
    $_SESSION['user']['email'] = $_POST['email'];
    $_SESSION['user']['occupation'] = $_POST['occupation'];
    $_SESSION['user']['location'] = $_POST['location'];
    $_SESSION['user']['website'] = $_POST['website'];
	
	if (is_uploaded_file($_FILES['avatar']['tmp_name'])) {
	include("avatarprocess.php");
	}
}

?>
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
<h1>Edit Account</h1>
<form action="edit_account.php" method="post" enctype="multipart/form-data">
    Username:<br />
    <b><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></b>
    <br /><br />
    E-Mail Address:<br />
    <input type="text" name="email" value="<?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>" />
    <br /><br />
    Password:<br />
    <input type="password" name="password" value="" /><br />
    <i>(leave blank if you do not want to change your password)</i>
    <br /><br />
	Occupation:<br />
	<input type="text" name="occupation" maxlength="30" value="<?php echo htmlentities($_SESSION['user']['occupation'], ENT_QUOTES, 'UTF-8'); ?>" />
    <br /><br />
	location:<br />
	<input type="text" name="location" maxlength="30" value="<?php echo htmlentities($_SESSION['user']['location'], ENT_QUOTES, 'UTF-8'); ?>" />
    <br /><br />
	Website:<br />
	<input type="text" name="website" maxlength="30" value="<?php echo htmlentities($_SESSION['user']['website'], ENT_QUOTES, 'UTF-8'); ?>" />    
	<br /><br />
	Avatar:<br />
	<input type="file" name="avatar" id="file" /><br />
	Current avatar:<br />
	<div id="avatar"><img src="<?php echo $_SESSION['user']['avatar']; ?>" alt="" /></div>
	<input type="submit" value="Update Account" />
</form>
</div>
		<?php include_once("sidebar.php"); //SIDEBAR ?>
		<?php include_once("footer.php"); //FOOTER ?>
	</div>	
  </body>
</html>  