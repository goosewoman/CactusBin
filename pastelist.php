<?php
require ("common.php");
    header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html>
<!--[if IE 9 ]> <html class='ie9'> <![endif]-->
";
if (isset($_GET['page'])) {
    $pagenum = $_GET['page'];
}
if (!(isset($pagenum))) {
    $pagenum = 1;
}
$query = 	"
        SELECT
            * 
        FROM
            pastes 
        WHERE
            password = ''
        ";
try {
    $stmt = $db->prepare($query);
    $stmt->execute();
}

catch (PDOException $ex) {
    
    die("Failed to run query");
}
$rows = $stmt->rowCount();
$page_rows = 20;
$last = ceil($rows / $page_rows);
if ($pagenum < 1) {
    $pagenum = 1;
} elseif ($pagenum > $last && $last > 0) {
    $pagenum = $last;
}
$max = 'LIMIT ' . ($pagenum - 1) * $page_rows . ',' . $page_rows;

$query = "
                SELECT 
                    title,
                    timestamp,
                    username,
                    id 
                FROM 
                    pastes 
                WHERE
                    password = ''
                ORDER BY id DESC	
                $max
                ";
try {
    $stmt = $db->prepare($query);
    $stmt->execute();
}

catch (PDOException $ex) {
    
    die("Failed to run query");
}
?>
<html>
    <head>
        <title> Paste List </title>
        <link rel="stylesheet" href="http://<?php echo "{$_SERVER['SERVER_NAME']}"; ?>/css/main.css" />
        <?php echo commonJs(); ?>
        <style type="text/css">
        #sidebar {
        height: 1279px;
        }
        #content {
        height: 1260px; 
        }
        </style>
    </head>
    <body>
    <a href="javascript:hideNotice();">
    <div id="notice">
    </div>
    </a>
    <?php
include ("header.php");
?>
        <div id="maindiv">
            <div id="content"  class="main">
            <?php

$rows = $stmt->fetchAll();
if ($rows) {
?>
        <table class="pastelist"> 
            <tr>
                <th>Paste Title</th>
                <th>Date Added</th>
                <th>By</th>
            </tr>
        <?php
    foreach ($rows as $row): ?>
            <tr>
                <td class="title"><a href='paste.php?id=<?php echo $row['id']; ?>'><?php echo htmlentities($row['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                <td class="time"><?php echo htmlentities(time_diff($row['timestamp']), ENT_QUOTES, 'UTF-8'); ?></td>
                <?php
                if ($row['username'] == 'guest') {
                ?>
                <td class="username"> <?php echo htmlentities($row['username']); ?></td>
                <?php
                } else {
                ?>
                <td class="username"><a <?php $username = $row['username']; echo "href='user.php?u=$username'>"; echo htmlentities($row['username'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                <?php 
                } 
                ?>
            </tr>
            <?php endforeach;
    echo "</table>";
}

echo " --Page $pagenum of $last-- <br />";
if ($pagenum == 1) {
} else {
    echo " <a href='{$_SERVER['PHP_SELF']}?page=1'> <<-First</a> ";
    echo " ";
    $previous = $pagenum - 1;
    echo " <a href='{$_SERVER['PHP_SELF']}?page=$previous'> <-Previous</a> ";
}
echo " ---- ";
if ($pagenum == $last) {
} else {
    $next = $pagenum + 1;
    echo " <a href='{$_SERVER['PHP_SELF']}?page=$next'>Next -></a> ";
    echo " ";
    echo " <a href='{$_SERVER['PHP_SELF']}?page=$last'>Last ->></a> ";
}
?> 
            
            </div>
    <?php
include ("sidebar.php");
include ("footer.php");
?>
        </div>
    </body>
</html>