<?php
include '../class/sql.php';

$dbsql = new DB_SQL();

/*Username Check*/
if((!preg_match("/^[0-9a-z_]{1,15}$/i", $_POST["username"]))){
	$obj = array('errors' => array('message' => 'Username', 'code' => '100' ) );
	echo json_encode($obj);
	break;
}

/*Password Check*/
if( $_POST["passwd1"] !== $_POST["passwd2"]){
	$obj = array('errors' => array('message' => 'Password', 'code' => '110' ) );
	echo json_encode($obj);
	break;
}

 
/*Null Check*/
if( ($_POST["username"] == null) ){
	$obj = array('errors' => array('message' => 'Username Null', 'code' => '101' ) );
	echo json_encode($obj);
	break;
}

if( ($_POST["passwd1"] == null) || ($_POST["passwd2"] == null) ){
	$obj = array('errors' => array('message' => 'Password Null', 'code' => '111' ) );
	echo json_encode($obj);
	break;
}

if( ($_POST["question"] == null) ){
	$obj = array('errors' => array('message' => 'Question Null', 'code' => '121' ) );
	echo json_encode($obj);
	break;
}

if( ($_POST["answer"] == null) ){
	$obj = array('errors' => array('message' => 'Answer', 'code' => '131' ) );
	echo json_encode($obj);
	break;
}


/*Password MD5 HASH Create*/
$password = md5($_POST["passwd1"]);

$dbsql->signup( $_POST["username"] ,$password, $_POST["question"], $_POST["answer"]);
$obj = array('message' => 'OK');
echo json_encode($obj);