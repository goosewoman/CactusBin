<?php

require ("common.php");
    header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html>
<!--[if IE 9 ]> <html class='ie9'> <![endif]-->
";

?>
<html>
    <head>
    <link rel="stylesheet" href="http://<?php echo "{$_SERVER['SERVER_NAME']}"; ?>/css/main.css" />
    <style type="text/css">
    </style>
    <?php echo commonJs(); ?>
    </head>
    <script type="text/javascript" src="js/sorttable.js"></script>
    <script type="text/javascript">
    var newTableObject = document.getElementById('sortable')
    sorttable.makeSortable(newTableObject);
    </script>
    <body>
    <a href="javascript:hideNotice();">
    <div id="notice">
    </div>
    </a>
    <?php include_once ("header.php"); //HEADER ?>
    <div id="maindiv">
        <div id="content" class="main">


<?php
if (empty($_SESSION['user'])) {
    header("Location: login.php");

    die("Redirecting to login.php");
}


$query = "
        SELECT
            id,
            username,
            email,
            joindate
        FROM users
    ";

try {
    $stmt = $db->prepare($query);
    $stmt->execute();
}
catch (PDOException $ex) {
    
    die("Failed to run query");
}

$rows = $stmt->fetchAll();
?>

<div id="memberlist">
<table border="0" class="sortable" id="memberlisttable"> 
    <tr>
        <th>Username</th>
        <th>Amount of pastes</th>
        <th>Join date</th>
    </tr>
    <?php foreach ($rows as $row): ?>
        <tr>
            <td><a <?php $username = $row['username']; echo "href='user.php?u=$username'>"; echo htmlentities($row['username'], ENT_QUOTES, 'UTF-8'); ?></a></td>
            <?php 
            $query = 	"
        SELECT
            * 
        FROM
            pastes 
        WHERE
            username = :username
        ";
try {
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $row['username']);
    $stmt->execute();
}
catch (PDOException $ex) {
    
    die("Failed to run query");
}
$amount_rows = $stmt->rowCount();	
                            ?>
            <td><?php echo htmlentities($amount_rows, ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlentities($row['joindate'], ENT_QUOTES, 'UTF-8'); ?> GMT</td>
        </tr>
    <?php endforeach; ?>
</table>
</div>
        </div>
        <?php include_once ("sidebar.php"); //SIDEBAR ?>
        <?php include_once ("footer.php"); //FOOTER ?>
    </div>	