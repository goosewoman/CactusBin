<?php require("common.php");
if (isset($_GET["id"])) {
    $pasteId = htmlspecialchars($_GET["id"]);
    if ($pasteId != "") {
        $query = "
            SELECT 
                content,
                password,
                id
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
            }
        catch (PDOException $ex) {       
            die("Failed to run query");
            }
            $rows = $stmt->fetchAll();
            if ($rows) {
                foreach ($rows as $row):
                    $password = htmlentities($row['password'], ENT_QUOTES, 'UTF-8');
                    $ID = htmlentities($row['id'], ENT_QUOTES, 'UTF-8');
                    $content = htmlentities($row['content'], ENT_QUOTES, 'UTF-8');
                    $content = str_replace("\n", "\r\n", $content);
                endforeach;
                if ($password == "") {
header('Content-Type: text/plain; charset=utf-8');
$filename = "Paste_" . $ID;
header("Content-disposition: attachment; filename=" . $filename . ".txt");
ob_start();
echo $content;
exit;

                } else {
                    if (!isset($_POST['password'])) {
?>
                            <div id="passwordarea">
                                <form action="download.php?id=<?php echo $pasteId; ?>" method="post" >
                                    Password: <input type="password" name="password" autocomplete="off" value="" />
                                    <input type="submit" value="submit" />
                                </form>
                            </div>
                            <?php
                    } else {
                        if ($password == $_POST['password']) {
header('Content-Type: text/plain; charset=utf-8');
$filename = "Paste_" . $ID;
header("Content-disposition: attachment; filename=" . $filename . ".txt");
ob_start();
echo $content;
exit;
                        } else {
                            echo notice("Invalid Password.");
                            ?>
                            <div id="passwordarea">
                                <form action="download.php?id=<?php echo $pasteId; ?>" method="post" >
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
}
?>