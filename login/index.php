<?php
	
?>

<h1>ログイン</h1>
<form method="post" action="./post.php">
	<div class="row box">
		<div class="span3">
			<h2>ユーザ名</h2>
		</div>
		<div class="span9">
			<p>
				<input type="text" name="username" placeholder="Username">
			</p>
		</div>
		
		<div class="span3">
			<h2>パスワード</h2>
		</div>
		<div class="span9">
			<p>
				<input type="password" name="passwd" placeholder="Password"><br>
		</div>
	</div>
	<input class="btn btn-large btn-primary" type="submit">登録</input>
</form>
