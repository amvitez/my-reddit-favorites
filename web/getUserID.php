<?php

try{
	session_start();
	echo $_SESSION['userID'];
} catch(Exception $e) {
    echo json_encode(array(
        'error' => array(
            'msg' => $e->getMessage(),
            'code' => $e->getCode(),
        ),
    ));
}
?>