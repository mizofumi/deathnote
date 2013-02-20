<?php
include '../api/class/sql.php';
include '../twitter/api.php';
include '../twitter/setting.php';

$dbsql = new DB_SQL();
session_start();
set_time_limit(0);

/*** DefaultUserIDを取得 ***/
$defaultuserid = $dbsql->get_defaultuserid($_SESSION["LoggedUserId"]);
/*** AccessToken等を取得 ***/
$loggedinfo = $dbsql->get_loggeduser_twitteruser($_SESSION["LoggedUserId"], $defaultuserid, $_SESSION["Apikey"]);
/*** TwitterAPIのインスタンス***/
$twitterapi = new TwitterAPI($consumer_key,$consumer_secret,$loggedinfo["Access_token"],$loggedinfo["Access_secret"]);

/*********************************************************/

echo "処理を開始します。しばらくお待ちください...<br />\n";

ob_end_flush();
ob_start('mb_output_handler');

/*
 * 差分作成
 */
echo '差分情報作成中...';
ob_flush();
flush();

$dbsql->make_diff($_SESSION["LoggedUserId"], $defaultuserid, $_SESSION["Apikey"]);

echo '[OK]' . '<br>';
ob_flush();
flush();
/******************/

/*
 * テーブル作成
 */
echo 'テーブル作成中...';
ob_flush();
flush();

$dbsql->make_table($_SESSION["LoggedUserId"], $defaultuserid, $_SESSION["Apikey"],'Follows');
$dbsql->make_table($_SESSION["LoggedUserId"], $defaultuserid, $_SESSION["Apikey"],'Followers');
$dbsql->make_table($_SESSION["LoggedUserId"], $defaultuserid, $_SESSION["Apikey"],'Friends');
$dbsql->make_table($_SESSION["LoggedUserId"], $defaultuserid, $_SESSION["Apikey"],'Blocks');

echo '[OK]' . '<br>';
ob_flush();
flush();
/******************/


/*
 * Follow取得
 */
echo 'フォロー取得中...';
ob_flush();
flush();

$follow_userid = $twitterapi->get_follow();

echo '[OK]' . '<br>';
ob_flush();
flush();
/******************/


/*
 * Follower取得
 */
echo 'フォロワー取得中...';
ob_flush();
flush();

$follower_userid = $twitterapi->get_follower();

echo '[OK]' . '<br>';
ob_flush();
flush();
/******************/

/*
 * Block取得
 */
echo 'ブロック中取得中...';
ob_flush();
flush();

$blocks_userid = $twitterapi->get_blocks();

echo '[OK]' . '<br>';
ob_flush();
flush();
/******************/

/*
 * Useridより詳細な情報を取得する
 * [プロセス]
 * 1.フォロー　と　フォロワー　のUseridを重複なしで結合する
 * 2.100件ずつ分割する
 * 3.実際にTwitterのサーバから名前等の詳細情報を取得する
 * 4.取得した詳細情報をフォロー、フォロワー、ブロックの各Useridを元に、データベースにInsertする
 */
/**** 重複するUseridを除去 ****/
$unique = array_unique(array_merge($follow_userid[ids],$follower_userid[ids],$blocks_userid[ids]));
/**** 100件ずつ分割 ****/
$chunk = array_chunk($unique,100);
$count = count($unique);
/*** 実際に取得 ***/
$cnt = 0;
echo (($cnt)).'件/'.count($unique).'件<br>';
ob_flush();
flush();
for ($a=0; $a<count($chunk); $a++) {
	$userinfo = $twitterapi->GetUsers_user_id($chunk[$a]);
	for ($b=0; $b < count($chunk[$a]); $b++) {
		$user[$userinfo[$b]["id_str"]] = array(
		'id_str' => $userinfo[$b]["id_str"],
		'screen_name' => $userinfo[$b]["screen_name"],
		'name' => $userinfo[$b]["name"],
		'profile_image_url' => $userinfo[$b]["profile_image_url"],
		'description' => $userinfo[$b]["description"],
		'statuses_count' => $userinfo[$b]["statuses_count"],
		'friends_count' => $userinfo[$b]["friends_count"],
		'followers_count' => $userinfo[$b]["followers_count"],
		'favourites_count' => $userinfo[$b]["favourites_count"],
		'lang' => $userinfo[$b]["lang"],
		'protected' => $userinfo[$b]["protected"],
		'verified' => $userinfo[$b]["verified"],
		'is_translator' => $userinfo[$b]["is_translator"]
		);
		$cnt = $cnt + 1;
	}
	echo (($cnt)).'件/'.count($unique).'件<br>';
	ob_flush();
	flush();
}

/** フォロー作成 **/
foreach ($follow_userid['ids'] as $d) {
	$dbsql->insert_record($_SESSION["LoggedUserId"], $defaultuserid, $_SESSION["Apikey"],'Follows',$user["$d"]);
}

/** フォロワー作成 **/
foreach ($follower_userid['ids'] as $d) {
	$dbsql->insert_record($_SESSION["LoggedUserId"], $defaultuserid, $_SESSION["Apikey"],'Followers',$user["$d"]); 
}

/** ブロック作成 **/
foreach ($blocks_userid['ids'] as $d) {
	$dbsql->insert_record($_SESSION["LoggedUserId"], $defaultuserid, $_SESSION["Apikey"],'Blocks',$user["$d"]);
}

/*
 * Friends取得
 */
echo '相互取得中...';
ob_flush();
flush();

$friends = $dbsql->get_friends($_SESSION["LoggedUserId"], $defaultuserid, $_SESSION["Apikey"]);
foreach ($friends['ids'] as $d) {
	$dbsql->insert_record($_SESSION["LoggedUserId"], $defaultuserid, $_SESSION["Apikey"],'Friends',$user["$d"]);
}


echo '[OK]' . '<br>';
ob_flush();
flush();
/******************/

echo '同期が完了しました<a href="../page">[戻る]</a>';
