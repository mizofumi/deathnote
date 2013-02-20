<?php
include '../class/sql.php';
session_start();

$dbsql = new DB_SQL();

$password = $dbsql->get_id_password($_SESSION["LoggedUserId"]);
/*Username Check*/
if((!preg_match("/^[0-9a-z_]{1,15}$/i", $_POST["username"]))){
	$obj = array('errors' => array('message' => 'Username', 'code' => '100' ) );
	echo json_encode($obj);
	break;
}

/*Password Check*/
if( md5($_POST["passwd"]) !== $password){
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

if( ($_POST["passwd"] == null) ){
	$obj = array('errors' => array('message' => 'Password Null', 'code' => '111' ) );
	echo json_encode($obj);
	break;
}




$dbsql->remove_account($_SESSION["LoggedUserId"],$_POST["username"],$_SESSION["Apikey"]);

/*** ログアウト***/
// セッション変数を全て解除する
$_SESSION = array();

// セッションを切断するにはセッションクッキーも削除する。
// Note: セッション情報だけでなくセッションを破壊する。
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// 最終的に、セッションを破壊する
session_destroy();

$obj = array('message' => 'OK');
echo json_encode($obj);