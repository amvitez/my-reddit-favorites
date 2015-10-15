<?php


try {
	//$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

	//$server = $url["host"];
	//$username = $url["user"];
	//$password = $url["pass"];
	//$dbName = substr($url["path"], 1);

	$db = new PDO("mysql:host=localhost;dbname=reddit;port=3306","admin","billfish911");
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$db->exec("SET NAMES 'utf8'");
} catch(PDOException $e) {
    echo 'Exception -> '.$e->getMessage();
}
?>