<?php
include '../class/http.php';
include '../class/sql.php';

$http_status_code = new HTTP_STATUS_CODE();
$dbsql = new DB_SQL();

/*Password MD5 HASH Create*/
$inputpassword = md5($_POST["passwd"]);

$login = $dbsql->login($_POST[username]);

if($login["Password"] == null){
	$obj = array('errors' => array('message' => 'LoginUsername', 'code' => '200' ) );
	echo json_encode($obj);
}elseif($login["Password"] !== $inputpassword){
	$obj = array('errors' => array('message' => 'LoginPassword', 'code' => '210' ) );
	echo json_encode($obj);
} else{
	$obj = array('message' => 'OK', 'LoggedUserId' => $login["Id"]);
	echo json_encode($obj);
}

/*
$http_status_code->Code_200();
$dbsql->signup( $_POST["username"] ,$password, $_POST["question"], $_POST["answer"]);

$obj = array('message' => 'OK');
echo json_encode($obj);
 */