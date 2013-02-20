<h1>閻魔帳アカウント削除</h1>
<form method="post" action="../api/removeaccount/index.php">
	<div class="row box">
		
		<div class="span3">
			<h2>パスワード確認</h2>
			<?php
			include '../api/class/sql.php';
			$dbsql = new DB_SQL();
			
			session_start();
			
			print $dbsql->get_id_username($_SESSION["LoggedUserId"]).'を削除しますか？<br>';
			print '削除する場合は下記にパスワードを入力してください。';
			
			print '<input type="hidden" name="username" value="'.$dbsql->get_id_username($_SESSION["LoggedUserId"]).'">';
			?>
		</div>
		<div class="span9">
			<p>
				<input type="password" name="passwd" placeholder="Password"><br>
			</p>
		</div>
	</div>
	<input class="btn btn-large btn-primary" type="submit"></input>
</form>
