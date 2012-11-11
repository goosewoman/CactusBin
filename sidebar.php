
<div id="sidebar" class="main">
<?php
if (!empty($_SESSION['user'])) {
    $query = "
        SELECT
			title,
			timestamp,
			username,
			password,
			id
        FROM pastes
		WHERE
                username = :username && password = ''
		ORDER BY id DESC
		LIMIT 9
		";

    try {
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $_SESSION['user']['username']);
        $stmt->execute();
    }

    catch (PDOException $ex) {
        
        die("Failed to run query");
    }
    $rows = $stmt->fetchAll();
    if ($rows) {
        echo '<span id="h2">Your pastes:</span>';
        $i = 0;
        foreach ($rows as $row):
            if ($i > 7) {
                break;
            }
            if ($row['password'] != "") {
                continue;
            } else {
                $i++;
            }
            $time = time_diff($row['timestamp']);
            //$row['username'];
            $id = $row['id'];
            $title = htmlentities($row['title'], ENT_QUOTES, 'UTF-8');
?>
                    <div class="sidebarPaste">
					<?php echo "<a href='paste.php?id=$id'>$title</a><br />$time";
?>
					</div>
                <?php
        endforeach;
    }
}
$query = "
        SELECT
			title,
			timestamp,
			username,
			password,
			id
        FROM pastes
		WHERE password = ''
		ORDER BY id DESC
		LIMIT 9
    ";

try {
    $stmt = $db->prepare($query);
    $stmt->execute();
}

catch (PDOException $ex) {
    
    die("Failed to run query");
}
$rows = $stmt->fetchAll();
if ($rows) {
    echo '<span id="h2">Recent pastes:</span>';
    $i = 0;
    foreach ($rows as $row):
        if ($i > 7) {
            break;
        }
        if ($row['password'] != "") {
            continue;
        } else {
            $i++;
        }
        $time = time_diff($row['timestamp']);
        //$row['username'];
        $id = $row['id'];
        $title = htmlentities($row['title'], ENT_QUOTES, 'UTF-8');
?>
                    <div class="sidebarPaste">
					<?php echo "<a href='paste.php?id=$id'>$title</a><br />$time";
?>
					</div>
                <?php
    endforeach;
}

?>
</div>
