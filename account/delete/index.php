<?php
include '../../api/class/sql.php';
$dbsql = new DB_SQL();

session_start();

$json = $dbsql->show_all_twitteraccount($_SESSION["LoggedUserId"],$_SESSION["Apikey"]);

print '<h1>削除するアカウントを選択してください</h1>';

print '
<form action="post.php" method="post">
<select name="Userid">';

foreach ($json as $d) {
	if($dbsql->get_defaultuserid($_SESSION["LoggedUserId"]) == $d["Userid"]){
		print '<option value="'.$d["Userid"].'" selected>'.$d["Screen_name"].'</option>';
	}else{
		print '<option value="'.$d["Userid"].'">'.$d["Screen_name"].'</option>';
	}
}

print'
</section>
<input type="hidden" name="Screen_name" value="'.$d["Screen_name"].'">
<input type="submit">
</form>
';

print '<a href="../../page">[戻る]</a>';
