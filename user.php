<?php require ("common.php");
    header('Content-Type: text/html; charset=utf-8');
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}
if (isset($_GET['page'])) {
    $pagenum = $_GET['page'];
}
if (isset($_GET['u']) && $_GET['u'] == 'guest') {
    header("Location: user.php");
}
if (isset($_GET['u']) && $_GET['u'] != '') {
    $username = $_GET['u'];
        $query = "
            SELECT
                1
            FROM users
            WHERE
                username = :username
        ";

    try {
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $result = $stmt->execute();
    }
    catch (PDOException $ex) {
        
        die("Failed to run query");
    }

    $row = $stmt->fetch();

    if (!$row) {
        header("Location: user.php");
    }
} else if (isset($_SESSION['user'])){
    $sessionusername = $_SESSION['user']['username'];
    header("Location: user.php?u=$sessionusername");
}
if (!(isset($pagenum))) {
    $pagenum = 1;
}
if (isset($_SESSION['user']) && $_SESSION['user']['username'] == $_GET['u']) {
    $query = 	"
        SELECT
            *
        FROM
            pastes 
        WHERE
            username = :username
        ";
} else if (isset($_SESSION['user']) && $_SESSION['user']['username'] != $_GET['u']) {
    $query = 	"
        SELECT
            *
        FROM
            pastes 
        WHERE
            username = :username 
        AND 
            password = ''
        ";
}
try {
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username',$_GET['u']);
    $stmt->execute();
}
catch (PDOException $ex) {
    
    die("Failed to run query");
}
$rows = $stmt->rowCount();
$row_amount = $rows;

$page_rows = 20;
$last = ceil($rows / $page_rows);
if ($pagenum < 1) {
    $pagenum = 1;
} elseif ($pagenum > $last && $last > 0) {
    $pagenum = $last;
}
$max = 'LIMIT ' . ($pagenum - 1) * $page_rows . ',' . $page_rows;
if (isset($_SESSION['user']) && $_SESSION['user']['username'] == $_GET['u']) {
$query = "
                SELECT 
                    title,
                    timestamp,
                    username,
                    id,
                    password
                FROM 
                    pastes 
                WHERE
                    username = :username
                ORDER BY id DESC	
                $max
                ";
} else if (isset($_SESSION['user']) && $_SESSION['user']['username'] != $_GET['u']) {
$query = "
                SELECT 
                    title,
                    timestamp,
                    username,
                    id,
                    password
                FROM 
                    pastes 
                WHERE
                    password = '' AND username = :username
                ORDER BY id DESC	
                $max
                ";
}
try {
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username',$_GET['u']);	
    $stmt->execute();
}

catch (PDOException $ex) {
    
    die("Failed to run query");
}
$query = "
                SELECT 
                    id,
                    username,
                    joindate,
                    avatar,
                    occupation,
                    location,
                    website
                FROM 
                    users 
                WHERE
                    username = :username
                ";
try {
    $user = $db->prepare($query);
    $user->bindParam(':username',$_GET['u']);	
    $user->execute();
}

catch (PDOException $ex) {
    
    die("Failed to run query");
}

$user = $user->fetch();

echo "<!DOCTYPE html>
<!--[if IE 9 ]> <html class='ie9'> <![endif]-->
"; ?>
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
    <?php include_once ("header.php"); //HEADER ?>
    <div id="maindiv">
        <div id="content" class="main">
        <div id="userinfo">
            <div id="title"><?php echo $username; ?></div>
            <div id="userdata">
            <?php
                $query = 	"
                        SELECT
                            title,
                            content,
                            password,
                            id,
                            username,
                            timestamp
                        FROM
                            pastes 
                        WHERE
                            username = :username
                        ";
                        try {
                        $meep = $db->prepare($query);
                        $meep->bindParam(':username',$_GET['u']);	
                        $meep->execute();
                    }

                    catch (PDOException $ex) {
                        
                        die("Failed to run query");
                    }
                    
                    $paste_amount = $meep->rowCount();
                    $joindate = $user['joindate'];
                    $timediff = time_diff(strtotime($joindate));
                    echo "TOTAL PASTES: $paste_amount | JOINDATE: $joindate | $timediff <br />";
                    if ($user['occupation'] == "") {
                    $occupation = "N/A";
                    } else {
                    $occupation = $user['occupation'];
                    }
                    if ($user['location'] == "") {
                    $location = "N/A";
                    } else {
                    $location = $user['location'];
                    }
                    if ($user['website'] == "") {
                    $website = "N/A";
                    } else {
                        if (substr($user['website'], 0, 6 ) == 'http://') {
                            $user['website'] = "http://".$user['website'];
                        }
                        $website = "<a href='{$user['website']}' target='_blank'>{$user['website']}</a>";
    
                    }
                    echo "OCCUPATION: $occupation | LOCATION: $location | WEBSITE: $website";
            
            ?>
            </div>
            <div id="avatar"><img src="<?php echo $user['avatar'];  ?>" /></div>
            
        </div>
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
                <td class="username"><?php echo htmlentities($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <?php endforeach;
    echo "</table>";
}
if ($row_amount < 1) {
    echo "This user hasn't pasted anything yet";
} else if ($row_amount < 20) {
    echo "--Page 1 of 1--";
} else {
    echo " --Page $pagenum of $last-- <br />";
    if ($pagenum == 1) {
    } else {
        echo " <a href='{$_SERVER['PHP_SELF']}?u=$username&page=1'> <<-First</a> ";
        echo " ";
        $previous = $pagenum - 1;
        echo " <a href='{$_SERVER['PHP_SELF']}?u=$username&page=$previous'> <-Previous</a> ";
    }
    echo " ---- ";
    if ($pagenum == $last) {
    } else {
        $next = $pagenum + 1;
        echo " <a href='{$_SERVER['PHP_SELF']}?u=$username&page=$next'>Next -></a> ";
        echo " ";
        echo " <a href='{$_SERVER['PHP_SELF']}?u=$username&page=$last'>Last ->></a> ";
    }
}
?> 

        </div>
        <?php include_once ("sidebar.php"); //SIDEBAR ?>
        <?php include_once ("footer.php"); //FOOTER ?>
    </div>	
  </body>
</html>