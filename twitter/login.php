<?php
require_once('twitteroauth.php');
include 'setting.php';

session_start();

if(!strlen($_SESSION["LoggedUserId"])){
	header("Location: ../index.php");
}else{
	$client = new TwitterOAuth($consumer_key, $consumer_secret);
	
	$token = $client->getRequestToken($callback_addr);
	$_SESSION['request_token'] = $token['oauth_token'];
	$_SESSION['request_token_secret'] = $token['oauth_token_secret'];

	$auth_addr = $client->authorizeURL() . '?oauth_token=' . $token['oauth_token'];
	
	header("Location: $auth_addr");	
}
