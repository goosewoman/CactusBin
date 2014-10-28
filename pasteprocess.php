<?php
require ("common.php");

if (!empty($_POST)) {
    if (empty($_POST['paste'])) {
        header("Location: index.php");
        die("Please insert text in the paste.");
    }

    if (empty($_POST['password'])) {
        $password = "";
    } else {
        $password = $_POST['password'];
    }
    if (empty($_POST['title'])) {
        $title = "Untitled";
    } else {
        $title = $_POST['title'];
    }
    $username = "guest";
    if (isset($_SESSION['user'])) {
        $query = "
                SELECT
                    username
                FROM 
                    users
                WHERE
                    username = :username
            ";

        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':username', $_SESSION['user']['username']);
            $stmt->execute();
        }
        catch (PDOException $ex) {
            
            die("Failed to run query");
        }

        $row = $stmt->fetch();
        if ($row) {
            $username = $row['username'];
        }
    }
    $query = "
            INSERT INTO pastes (
                username,
                password,
                content,
                timestamp,
                title
            ) VALUES (
                :username,
                :password,
                :content,
                :timestamp,
                :title
            )
        ";

    try {
        $time = time();
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':content', $_POST['paste']);
        $stmt->bindParam(':timestamp', $time);
        $stmt->bindParam(':title', $title);
        $result = $stmt->execute();
        $id = $db->lastInsertId();
        header("Location: paste.php?id=$id");
        die("Paste created, redirecting to paste.");
    }
    catch (PDOException $ex) {
        
        die("Failed to run query");
    }
}
