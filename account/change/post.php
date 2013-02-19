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

$dbsql->set_defaultuserid($_SESSION["LoggedUserId"],$_POST["Userid"]);
header("Location: ./?Userid=".$_POST["Userid"]);