<?php
include '../class/sql.php';
session_start();

$dbsql = new DB_SQL();


//print_r( $dbsql->show_all_twitteraccount($_SESSION["LoggedUserId"],$_SESSION["Apikey"]) );