<?php

try{
	session_start();
	echo $_SESSION['userID'];
} catch(Exception $e) {
    echo 'Exception -> '.$e->getMessage();
}
?>