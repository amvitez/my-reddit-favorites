<?php
require 'db.php';

$userID = $_POST['userID'];

try {
	$sql = $db->prepare("SELECT p.postID, p.redditID, p.title, p.url, p.thumbnail, f.favoriteID FROM post AS p INNER JOIN favorite AS f ON p.postID = f.postID WHERE userID = :userID");
	$sql->bindParam(':userID', $userID);
	$sql->execute();
	echo json_encode($sql->fetchAll(PDO::FETCH_ASSOC));

} catch(PDOException $e) {
    echo 'Exception -> '.$e->getMessage();
}

?>