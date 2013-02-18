<?php
session_start();
include '../api/class/sql.php';

$dbsql = new DB_SQL();

if(!strlen($_SESSION["LoggedUserId"])){
	header("Location: ../index.php");
}

if(!strlen( $dbsql->loadaccount($_SESSION["LoggedUserId"]) )){
	echo "紐付けられているアカウントがありません";
	echo '<a href="../twitter/login.php">Twitter認証</a>';
}