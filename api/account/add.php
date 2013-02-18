<?php
include '../class/sql.php';
session_start();

$dbsql = new DB_SQL();

if(!strlen($_SESSION["LoggedUserId"])){
	header("Location: ../../index.php");
}


/* DefaultUserid　が無い場合に追加*/
$defaultuserid = $dbsql->loadaccount($_SESSION["LoggedUserId"]);
if(!strlen($defaultuserid)){
	$dbsql->set_defaultuserid( ($_SESSION["LoggedUserId"]) , ($_SESSION["Twitter_Userid"]) );
}

/* TwitterAccount_info テーブルに追加 */
//既にレコードがある場合削除
if( $dbsql->get_twitteraccount_count( ($_SESSION["LoggedUserId"]) , ($_SESSION["Twitter_Userid"]) ) == 1){
	$dbsql->delete_twitteraccount( ($_SESSION["LoggedUserId"]) , ($_SESSION["Twitter_Userid"]) );
}

print $_SESSION["Twitter_Screen_name"].'<br>';
print $_SESSION["Twitter_Userid"].'<br>';
print $_SESSION["Twitter_Tweetcnt"].'<br>';
print $_SESSION["Twitter_Followcnt"].'<br>';
print $_SESSION["Twitter_Followercnt"].'<br>';
print $_SESSION["Twitter_Favcnt"].'<br>';

print $dbsql->add_twitteraccount($_SESSION["Twitter_Userid"],$_SESSION["LoggedUserId"],$_SESSION['access_token'],$_SESSION['access_token_secret'],$_SESSION["Twitter_Tweetcnt"],$_SESSION["Twitter_Favcnt"],$_SESSION["Twitter_Followcnt"],$_SESSION["Twitter_Followercnt"]);
//print $dbsql->add_twitteraccount(1,1,1,1,1,1,1,1);