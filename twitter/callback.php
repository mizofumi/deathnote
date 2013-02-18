<?php
require_once('twitteroauth.php');
include("setting.php");
include("api.php");

session_start();
// リクエスト・トークンをセット
$client = new TwitterOAuth(
    $consumer_key, $consumer_secret,
    $_SESSION['request_token'], $_SESSION['request_token_secret']);

// アクセス・トークンを取得
$token = $client->getAccessToken($_GET['oauth_verifier']);

if (empty($token['oauth_token'])) {
    /* アクセス・トークンがなければ、何らかの理由で取得失敗した。
     * もう一度リクエスト・トークンを生成して認証を試みる。
     */
}
//Twitter wrapperのインスタンスを作成
$twitter = new TwitterAPI($consumer_key, $consumer_secret, $token['oauth_token'], $token['oauth_token_secret']);
//ログイン中のユーザ情報取得
$get = $twitter->account_verify_credentials();

$_SESSION["Twitter_Screen_name"] = $get[screen_name];
$_SESSION["Twitter_Userid"] = $get[id_str];
$_SESSION["Twitter_Tweetcnt"] = $get[statuses_count];
$_SESSION["Twitter_Followcnt"] = $get[friends_count];
$_SESSION["Twitter_Followercnt"] = $get[followers_count];
$_SESSION["Twitter_Favcnt"] = $get[favourites_count];
$_SESSION['access_token'] = $token['oauth_token'];
$_SESSION['access_token_secret'] = $token['oauth_token_secret'];



header("Location: ../api/account/add.php");