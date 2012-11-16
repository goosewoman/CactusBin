<?php 
require("common.php");
if (isset($_GET["id"])) {
    $pasteId = htmlspecialchars($_GET["id"]);
    if ($pasteId != "") {
        $query = "
            SELECT 
				content,
				password
			FROM 
				pastes 
			WHERE 
				`id` 
			IN 
				($pasteId)
        ";
        try {
            $stmt = $db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            if ($rows) {
                foreach ($rows as $row):
                    $GLOBALS['content'] = htmlentities($row['content'], ENT_QUOTES, 'UTF-8');
                    $GLOBALS['password'] = htmlentities($row['password'], ENT_QUOTES, 'UTF-8');
                endforeach;
				
				if ($GLOBALS['password'] == "") {
header('Content-Type: text/plain; charset=utf-8');				
echo $GLOBALS['content'];

                } else {
                    if (!isset($_POST['password'])) {
?>
							<div id="passwordarea">
								<form action="<?php echo $pasteId; ?>.txt" method="post" >
									Password: <input type="password" name="password" autocomplete="off" value="" />
									<input type="submit" value="submit" />
								</form>
							</div>
							<?php
                    } else {
                        if ($GLOBALS['password'] == $_POST['password']) {
header('Content-Type: text/plain; charset=utf-8');						
echo $GLOBALS['content'];
                        } else {
                            echo notice("Invalid Password.");
							?>
							<div id="passwordarea">
								<form action="<?php echo $pasteId; ?>.txt" method="post" >
									Password: <input type="password" name="password" autocomplete="off" value="" />
									<input type="submit" value="submit" />
								</form>
							</div>
							<?php
                        }
                    }
                }
            }
        }

        catch (PDOException $ex) {
            
            die("Failed to run query");
        }
    }
}