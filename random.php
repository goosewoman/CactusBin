<?php
require ("common.php");
$query = "
        SELECT id, password
		  FROM pastes AS r1 JOIN
			   (SELECT (RAND() *
							 (SELECT MAX(id)
								FROM pastes)) AS rawr)
				AS r2
		 WHERE r1.id >= r2.rawr && password = ''
		 ORDER BY r1.id ASC
		 LIMIT 1
		 ";
try {
    $stmt = $db->prepare($query);
    $stmt->execute();
}

catch (PDOException $ex) {
    
    die("Failed to run query");
}
$rows = $stmt->fetch();

if ($rows) {
    $id = $rows['id'];
    header("location: paste.php?id=$id");
}
