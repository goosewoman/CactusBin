<?php 

 define ("MAX_SIZE","5000");

 $errors=0;
 
	$image = $_FILES["avatar"]["name"];
	$uploadedfile = $_FILES['avatar']['tmp_name'];

	if ($image) {
		$filename = stripslashes($_FILES['avatar']['name']);
		$extension = getExtension($filename);
		$extension = strtolower($extension);
		if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
		echo ' Unknown Image extension ';
		$errors=1;
		}
		else {
			$size=filesize($_FILES['avatar']['tmp_name']);
		 
			if ($size > MAX_SIZE*1024) {
				echo "You have exceeded the size limit";
				$errors=1;
			}
			 
			if($extension=="jpg" || $extension=="jpeg" ) {
			$uploadedfile = $_FILES['avatar']['tmp_name'];
			$src = imagecreatefromjpeg($uploadedfile);
			}
			else if($extension=="png") {
			$uploadedfile = $_FILES['avatar']['tmp_name'];
			$src = imagecreatefrompng($uploadedfile);
			}
			else {
			$src = imagecreatefromgif($uploadedfile);
			}
			 
			list($width, $height)=getimagesize($uploadedfile);

			$newwidth=90;
			$newheight=90;
			$tmp=imagecreatetruecolor($newwidth, $newheight);

			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth, $newheight, $width, $height);


			$filename = "avatars/". $_SESSION['user']['username'].".jpeg";

			imagejpeg($tmp,$filename,100);

			imagedestroy($src);
			imagedestroy($tmp);
		}
	}

if(!$errors) 
{
	$filename = "avatars/". $_SESSION['user']['username'].".jpeg";

	$avatar = "http://{$_SERVER['SERVER_NAME']}/$filename";
	$query = "
		UPDATE users
		SET
			avatar = :avatar
		WHERE
			id = :user_id		
			
	";
	try {
	$stmt = $db->prepare($query);
	$stmt->bindParam(':avatar', $avatar);
	$stmt->bindParam(':user_id', $_SESSION['user']['id']);
	$result = $stmt->execute();
	}
	catch (PDOException $ex) {
		
		die("Failed to run query");
	}
	$_SESSION['user']['avatar'] = $avatar;
}
function getExtension($str) {

         $i = strrpos($str,".");
         if (!$i) { return ""; } 

         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }