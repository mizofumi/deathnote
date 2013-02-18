<?php
require_once('twitteroauth.php');
include 'setting.php';

session_start();

$client = new TwitterOAuth($consumer_key, $consumer_secret);

$token = $client->getRequestToken($callback_addr);
$_SESSION['request_token'] = $token['oauth_token'];
$_SESSION['request_token_secret'] = $token['oauth_token_secret'];

// 認証ページのアドレス
$auth_addr = $client->authorizeURL() . '?oauth_token=' . $token['oauth_token'];

//Twitter認証ページにリダイレクト
header("Location: $auth_addr");

?>