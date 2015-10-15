<?php


try {
	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

	$server = $url["host"];
	$user = $url["user"];
	$pass = $url["pass"];
	$dbName = substr($url["path"], 1);

	$db = new PDO("mysql:host=".$server.";dbname=".$dbName.";port=3306",$user,$pass);
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$db->exec("SET NAMES 'utf8'");
} catch(PDOException $e) {
    echo 'Exception -> '.$e->getMessage();
}
?>