<?php
require 'db.php';

$userID = $_POST["userID"];
$redditID = $_POST["redditID"];

try {
	$sql = $db->prepare("DELETE f FROM favorite AS f JOIN post AS p ON f.postID = p.postID WHERE f.userID = :userID AND p.redditID = :redditID");
	$sql->bindParam(':userID', $userID);
	$sql->bindParam(':redditID', $redditID);
	$sql->execute();

} catch(Exception $e) {
    echo 'Exception -> '.$e->getMessage();
}

?>