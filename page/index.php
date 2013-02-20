<?php
session_start();
include '../api/class/sql.php';

$dbsql = new DB_SQL();

if(!strlen($_SESSION["LoggedUserId"])){
	header("Location: ../index.php");
}

if(!strlen( $dbsql->get_defaultuserid($_SESSION["LoggedUserId"]) )){
	echo "紐付けられているアカウントがありません";
	echo '<a href="../twitter/login.php">Twitter認証</a>';
	exit();
}

echo '<a href="../twitter/login.php">アカウント追加</a>'.'<br>';
echo '<a href="../logout">ログアウト</a>'.'<br>';
echo '<a href="../account/change">デフォルトアカウント変更</a>'.'<br>';
echo '<a href="../account/delete">アカウント削除</a>'.'<br>';
echo '<a href="../sync">Twitterサーバと同期</a>'.'<br><br>';
echo '<a href="../removeaccount">閻魔帳アカウントを削除</a>'.'<br><br>';
$defaultuserid = $dbsql->get_defaultuserid($_SESSION["LoggedUserId"]);
$data = $dbsql->get_loggeduser_twitteruser($_SESSION["LoggedUserId"],$defaultuserid,$_SESSION["Apikey"]);

echo "--DeathnoteInfo--".'<br>';
echo "Userid : ".$_SESSION["LoggedUserId"].'<br>';
echo "Apikey : ".$_SESSION["Apikey"].'<br><br>';

echo "--TwitterInfo--".'<br>';
echo "Userid : ".$defaultuserid.'<br>';
echo "Screen_name : ".$data["Screen_name"].'<br>';
echo "Access_token : ".$data["Access_token"].'<br>';
echo "Access_secret : ".$data["Access_secret"].'<br>';
echo "Tweet : ".$data["Tweet"].'<br>';
echo "Favorite : ".$data["Favorite"].'<br>';
echo "Follow : ".$data["Follow"].'<br>';
echo "Follower : ".$data["Follower"].'<br>';
echo "Friends : ".$data["Friends"].'<br>';
echo "Following : ".$data["Following"].'<br>';
echo "Fans : ".$data["Fans"].'<br>';
echo "Blocks : ".$data["Blocks"].'<br>';
echo "Spams : ".$data["Spams"].'<br>';
echo "LastSync : ".$data["LastSync"].'<br>';