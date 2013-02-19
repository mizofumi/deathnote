<?php
include '../../api/class/sql.php';
$dbsql = new DB_SQL();

session_start();

/*
 * Apikey確認
 */
$db_apikey = $dbsql->get_id_apikey($_SESSION["LoggedUserId"]);
if($db_apikey !== $_SESSION["Apikey"]){
	echo "Error";
	exit;
}

/*
 * DefaultUserの場合は削除しない
 */
$db_defaultuserid = $dbsql->get_defaultuserid($_SESSION["LoggedUserId"]);
if($db_defaultuserid == $_POST["Userid"]){
	header("Location: ./?Error=DefaultUserId");
	exit;
}

$dbsql->delete_twitteraccount($_SESSION["LoggedUserId"],$_POST["Userid"]);
header("Location: ./?Userid=".$_POST["Userid"]);