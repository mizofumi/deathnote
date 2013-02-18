<?php
session_start();

$url = 'http://twitter.mizofumi.net/deathnote/deathnote/0_06/api/login/index.php';
$data = array(
	'username' => $_POST["username"],
	'passwd' => $_POST["passwd"],
);
$options = array('http' => array(
	'method' => 'POST',
	'content' => http_build_query($data),
));
$contents = file_get_contents($url, false, stream_context_create($options));

$json = json_decode($contents,true);

if($json["errors"]["code"] == 200){
	$redirect_url = "./index.php?error=username";
}

if($json["errors"]["code"] == 210){
	$redirect_url = "./index.php?error=password";
}

if ($json["message"] == 'OK'){
	$_SESSION["LoggedUserId"] = $json["LoggedUserId"];
	$redirect_url = "../page";
}

header("Location:".$redirect_url);