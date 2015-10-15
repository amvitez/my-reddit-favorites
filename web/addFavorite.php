<?php
require 'db.php';

$userID = $_POST["userID"];
$redditID = $_POST["redditID"];
$title = $_POST["title"];
$url = $_POST["url"];
$thumbnail = $_POST["thumbnail"];

try {
	//see if redditID is already in the post table, if so use that postID, if not insert record into post table and use that new postID
	$sql = $db->prepare("SELECT postID FROM post WHERE redditID = :redditID");
	$sql->bindParam(':redditID', $redditID);
	$sql->execute();
	$postID = $sql->fetchColumn();
	
	if(!$postID){
		//insert new post record and get that new postID
		$sql = $db->prepare("INSERT INTO post (redditID, title, url, thumbnail)
			VALUES 
				(:redditID,
				:title,
				:url,
				:thumbnail)");
		$sql->bindParam(':redditID', $redditID);
		$sql->bindParam(':title', $title);
		$sql->bindParam(':url', $url);
		$sql->bindParam(':thumbnail', $thumbnail);
		$sql->execute();
		
		$sql = $db->prepare("SELECT LAST_INSERT_ID()");
		$sql->execute();
		$postID = $sql->fetchColumn();
	}

	$sql = $db->prepare("INSERT INTO favorite (userID, postID)
		VALUES 
			(:userID,
			:postID)");
		$sql->bindParam(':userID', $userID);
		$sql->bindParam(':postID', $postID);
		$sql->execute();

} catch(PDOException $e) {
    echo 'Exception -> '.$e->getMessage();
}

?>