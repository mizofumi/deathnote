<?php
include '../class/sql.php';
session_start();

$dbsql = new DB_SQL();

if(!strlen($_SESSION["LoggedUserId"])){
	header("Location: ../../index.php");
}


/* DefaultUserid　が無い場合に追加*/
$defaultuserid = $dbsql->get_defaultuserid($_SESSION["LoggedUserId"]);
if(!strlen($defaultuserid)){
	$dbsql->set_defaultuserid( ($_SESSION["LoggedUserId"]) , ($_SESSION["Twitter_Userid"]) );
}

/* TwitterAccount_info テーブルに追加 */
//既にレコードがある場合削除
if( $dbsql->get_count_twitteraccount( ($_SESSION["LoggedUserId"]) , ($_SESSION["Twitter_Userid"]) ) == 1){
	$dbsql->delete_all_twitteraccount( ($_SESSION["LoggedUserId"]) , ($_SESSION["Twitter_Userid"]) );
}

$dbsql->add_twitteraccount($_SESSION["Twitter_Userid"],$_SESSION["LoggedUserId"],$_SESSION["Twitter_Screen_name"],$_SESSION['access_token'],$_SESSION['access_token_secret'],$_SESSION["Twitter_Tweetcnt"],$_SESSION["Twitter_Favcnt"],$_SESSION["Twitter_Followcnt"],$_SESSION["Twitter_Followercnt"]);

/*
$obj = array('message' => 'OK');
echo json_encode($obj);
 */
header("Location: ../../page/");