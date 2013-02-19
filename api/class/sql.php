<?php
include 'db.php';

/**
 * SQL Wrapper
 */
class DB_SQL extends DB{
	
	/*
	 *　ランダムな文字列の生成
	 */
	public function makeRandomString($length)
	{
		$str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z"'));
		for ($i = 0; $i < $length; $i++){
			$r_str .= $str[rand(0, count($str)-1)];
		}
		return $r_str;
	}
	
	/*
	 * 閻魔帳アカウントを作成
	 */
	public function signup($username, $password , $question , $answer)
	{
		$rand = $this->makeRandomString(50);
		$sql = ('
		INSERT INTO Account_info
		(Username , Password , Question , Answer , Apikey)
		VALUE
		("'.$username.'","'.$password.'","'.$question.'","'.$answer.'","'.$rand.'")
		');
		$this->runQuery($sql);
	}
	
	/*
	 * 閻魔帳にログイン
	 */
	public function login($username)
	{
		$sql = ('SELECT Id , Password , Apikey FROM Account_info WHERE Username = "'.$username.'"');
		$data = $this->getQuery($sql);
		foreach ($data as $d) {
			return array( 'Id' => $d["Id"], 'Password' => $d["Password"], 'Apikey' => $d["Apikey"]);
		}
	}
	
	/*
	 * APIKeyの取り出し (パスワード使用)
	 */
	public function get_password_apikey($username,$password)
	{
		$sql = ('SELECT Id , Password FROM Account_info WHERE (Username = "'.$username.'" and Password = "'.$password.'")');
		$data = $this->getQuery($sql);
		foreach ($data as $d) {
			return array( 'Id' => $d["Id"], 'Password' => $d["Password"]);
		}
	}
	
	/*
	 * APIKeyの取り出し(内部用)
	 */
	public function get_id_apikey($Id)
	{
		$sql = ('SELECT Apikey FROM Account_info WHERE Id = '.$Id);
		$data = $this->getQuery($sql);
		foreach ($data as $d) {
			return $d["Apikey"];
		}
	}
	
	/*
	 * Twitterのアカウントを新規に記録
	 */
	public function add_twitteraccount($Userid,$Id,$Screen_name,$Access_token,$Access_secret,$Tweet,$Favorite,$Follow,$Follower)
	{
		$sql = ('
		INSERT INTO
		TwitterAccount_info
		(
		Userid,
		Id,
		Screen_name,
		Access_token,
		Access_secret,
		Tweet,
		Favorite,
		Follow,
		Follower)
		VALUE
		('.$Userid.','.$Id.',"'.$Screen_name.'","'.$Access_token.'","'.$Access_secret.'",'.$Tweet.','.$Favorite.','.$Follow.','.$Follower.')
		');
		$this->runQuery($sql);
		return $sql;
	}

	/*
	 * Twitterのアカウントを削除
	 */
	 public function delete_twitteraccount($Id,$Userid)
	 {
		 $sql = ('
		 DELETE
		 FROM
		 TwitterAccount_info
		 WHERE
		 Id = '.$Id.'
		 AND
		 Userid = '.$Userid.'
		 ');
		 $this->runQuery($sql);
		 
		 $sql = ('
		 DROP
		 TABLE
		 Follows_'.$Userid.',
		 Followers_'.$Userid.',
		 Friends_'.$Userid.',
		 Followings_'.$Userid.',
		 Fans_'.$Userid.',
		 Blocks_'.$Userid.',
		 Old_Follows_'.$Userid.',
		 Old_Followers_'.$Userid.',
		 ');
		 $this->runQuery($sql);
		 
	 }

	/*
	 * 紐付けているTwitterのアカウント数を取得
	 */
	public function get_count_twitteraccount($Id,$Userid)
	{
		$sql = ('
		SELECT count(*)
		FROM
		TwitterAccount_info
		WHERE
		(Id = '.$Id.' and Userid = '.$Userid.')
		');
		$data = $this->getQuery($sql);
		foreach ($data as $d) {
			return $d["count(*)"];
		}
	}
	
	/*
	 * TwitterAccountに記録されているアカウントを削除
	 */
	public function delete_all_twitteraccount($Id,$Userid)
	{
		$sql = ('
		DELETE
		FROM
		TwitterAccount_info
		WHERE
		(Id = '.$Id.' and Userid = '.$Userid.')
		');
		$this->getQuery($sql);
	}
	
	/*
	 *　Account_infoにデフォ垢を登録
	 */
	public function set_defaultuserid($Id,$userid)
	{
		$sql = ('
		UPDATE
		Account_info
		SET
		DefaultUserid = '.$userid.'
		WHERE
		Id = '.$Id.'
		');
		$this->runQuery($sql);
	}
	
	/*
	 * Account_infoに記録されているデフォの垢を取得
	 */
	public function get_defaultuserid($Id)
	{
		$sql = ('
		SELECT DefaultUserid FROM Account_info WHERE Id = '.$Id.'
		');
		$data = $this->getQuery($sql);
		foreach ($data as $d) {
			return $d["DefaultUserid"];
		}
	}
	
	/*
	 * TwitterAccountに記録されている全てのアカウントを取得
	 */
	 public function show_all_twitteraccount($Id,$Apikey)
	 {
	 	if( $Apikey == $this->get_id_apikey($Id) ){
	 		$sql = ('
			SELECT *
			FROM
			TwitterAccount_info
			WHERE
			Id = '.$Id.'
			');
			$data = $this->getQuery($sql);
			foreach ($data as $d) {
				$geso[] = array(
					'Userid' => $d["Userid"],
					'Screen_name'=>$d["Screen_name"],
					'Access_token' => $d["Access_token"],
					'Access_secret' => $d["Access_secret"],
					'LastSync' => $d["LastSync"],
					'Tweet' => $d["Tweet"],
					'Favorite' => $d["Favorite"],
					'Follow' => $d["Follow"],
					'Follower' => $d["Follower"],
					'Friends' => $d["Friends"],
					'Following' => $d["Following"],
					'Fans' => $d["Fans"],
					'Blocks' => $d["Blocks"],
					'Spams' => $d["Spams"]
				);
			}
			return $geso;
		}else{
			return FALSE;
		}
	 }
	
	/*
	 * TwitterAccount_infoテーブルからアクセストークンやらを取る
	 */
	public function get_loggeduser_twitteruser($Id,$Userid,$Apikey)
	{	
		if( $Apikey == $this->get_id_apikey($Id) ){
			$sql = ('
			SELECT *
			FROM
			TwitterAccount_info
			WHERE
			Id = '.$Id.'
			AND
			Userid = '.$Userid.'
			');
			$data = $this->getQuery($sql);
			foreach ($data as $d) {
				return array(
					'Screen_name'=>$d["Screen_name"],
					'Access_token' => $d["Access_token"],
					'Access_secret' => $d["Access_secret"],
					'LastSync' => $d["LastSync"],
					'Tweet' => $d["Tweet"],
					'Favorite' => $d["Favorite"],
					'Follow' => $d["Follow"],
					'Follower' => $d["Follower"],
					'Friends' => $d["Friends"],
					'Following' => $d["Following"],
					'Fans' => $d["Fans"],
					'Blocks' => $d["Blocks"],
					'Spams' => $d["Spams"]
				);
			}
		}else{
			return FALSE;
		}
	}
}