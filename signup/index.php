<h1>アカウント新規作成</h1>
<form method="post" action="../api/signup/index.php">
	<div class="row box">
		<div class="span3">
			<h2>ユーザ名</h2>
		</div>
		<div class="span9">
			<p>
				半角英数1~15文字 (記号はハイフン「-」アンダースコア「_」のみ)<br>
				<input type="text" name="username" placeholder="Username">
			</p>
		</div>
		
		<div class="span3">
			<h2>パスワード</h2>
		</div>
		<div class="span9">
			<p>
				半角英数<br>
				<input type="password" name="passwd1" placeholder="Password"><br>
				確認の為にもう一度入力して下さい<br>
				<input type="password" name="passwd2" placeholder="Confirm Password">
			</p>
		</div>
		
		<div class="span3">
			<h2>パスワード紛失時の質問設定</h2>
		</div>
		<div class="span9">
			<p>
				パスワードを紛失した際に本人確認を行うための質問を入力して下さい<br>
				<input type="text" name="question" placeholder="質問"><br>
				質問の回答を入力してください<br>
				<input type="text" name="answer" placeholder="回答">
			</p>
		</div>
	</div>
	<input class="btn btn-large btn-primary" type="submit"></input>
</form>
