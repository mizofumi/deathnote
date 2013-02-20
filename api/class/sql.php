<?php
include 'db.php';

/**
 * SQL Wrapper
 */
class DB_SQL extends DB {

	/*
	 *　ランダムな文字列の生成
	 */
	public function makeRandomString($length)
	{
		$str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z"'));
		for ($i = 0; $i < $length; $i++) {
			$r_str .= $str[rand(0, count($str) - 1)];
		}
		return $r_str;
	}
	
	/*
	 * 閻魔帳アカウントの削除
	 */
	public function remove_account($Id,$Username,$Apikey)
	{
		$hoge = $this->show_all_twitteraccount($Id,$Apikey);
		foreach ($hoge as $d) {
			$sql = ('DROP TABLE Follows_'.$d[Userid].',Followers_'.$d[Userid].',Blocks_'.$d[Userid].',Friends_'.$d[Userid].',Old_Follows_'.$d[Userid].',Old_Followers_'.$d[Userid]);
			$this->runQuery($sql);
			$sql =('DELETE FROM TwitterAccount_info WHERE (Id = '.$Id.' and Userid = '.$d[Userid].')');
			$this->runQuery($sql);
			$sql =('DELETE FROM Account_info WHERE Id = '.$Id);
			$this->runQuery($sql);
		}
		//$sql = ('');
		//$this->runQuery($sql);
	}
	/*
	 * 閻魔帳アカウントを作成
	 */
	public function signup($username, $password, $question, $answer)
	{
		$rand = $this -> makeRandomString(50);
		$sql = ('
		INSERT INTO Account_info
		(Username , Password , Question , Answer , Apikey)
		VALUE
		("' . $username . '","' . $password . '","' . $question . '","' . $answer . '","' . $rand . '")
		');
		$this -> runQuery($sql);
	}

	/*
	 * 閻魔帳にログイン
	 */
	public function login($username)
	{
		$sql = ('SELECT Id , Password , Apikey FROM Account_info WHERE Username = "' . $username . '"');
		$data = $this -> getQuery($sql);
		foreach ($data as $d) {
			return array('Id' => $d["Id"], 'Password' => $d["Password"], 'Apikey' => $d["Apikey"]);
		}
	}

	/*
	 * Twitterと同期(差分生成)
	 */
	public function make_diff($Id, $Userid, $Apikey)
	{
		if ($Apikey == $this -> get_id_apikey($Id)) {
			$sql = ('
			DROP
			TABLE
			Friends_' . $Userid . ',
			Blocks_' . $Userid . ',
			Old_Follows_' . $Userid . ',
			Old_Followers_' . $Userid . '
			');
			$this -> runQuery($sql);

			$sql = ('ALTER TABLE Follows_' . $Userid . ' RENAME TO Old_Follows_' . $Userid);
			$this -> runQuery($sql);
			$sql = ('ALTER TABLE Followers_' . $Userid . ' RENAME TO Old_Followers_' . $Userid);
			$this -> runQuery($sql);
		}
	}

	/*
	 * Twitterと同期(テーブル作成)
	 */
	public function make_table($Id, $Userid, $Apikey, $tablename)
	{
		if ($Apikey == $this -> get_id_apikey($Id)) {
			switch ($tablename) {
				case 'Follows':
				case 'Followers':
				case 'Blocks':
					$sql = ('
						CREATE TABLE ' . $tablename . '_' . $Userid . '
						(
							Userid INT,
							Screen_name	VARCHAR(255),
							Name VARCHAR(255),
							IconURL VARCHAR(255),
							Bio VARCHAR(255),
							Tweet INT,
							Follow INT,
							Follower INT,
							Favorite INT,
							Lang VARCHAR(16),
							Protect VARCHAR(16),
							Verified VARCHAR(16),
							Traslations VARCHAR(16),
							Category VARCHAR(255),
							Tags VARCHAR(255)
						)
				 	');
					break;
				case 'Friends':
					$sql = ('CREATE TABLE ' . $tablename . '_' . $Userid . '(Userid INT)');
					break;
			}
			$this -> runQuery($sql);
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	/*
	 * 相互関係のUseridを取得
	 */
	public function get_friends($Id, $Userid, $Apikey)
	{
		if ($Apikey == $this -> get_id_apikey($Id)) {
			$sql = ('
			SELECT
			Follows_'.$Userid.'.Userid
			FROM
			Follows_'.$Userid.', Followers_'.$Userid.'
			WHERE
			(Follows_'.$Userid.'.Userid = Followers_'.$Userid.'.Userid)
			');
			foreach ($this -> getQuery($sql) as $d) {
				$hoge[ids][] = $d["Userid"];
			}
			return $hoge;
		}else{
			return FALSE;
		}
	}
	
	/*
	 * レコード追加
	 */
	public function insert_record($Id, $Userid, $Apikey, $tablename, $array)
	{
		if ($Apikey == $this -> get_id_apikey($Id)) {
			/*** String Quote***/
			$screen_name = $this -> strQuote($array["screen_name"]);
			$description = $this -> strQuote($array["description"]);
			$name = $this -> strQuote($array["name"]);
			$profile_image_url = $this -> strQuote($array["profile_image_url"]);
			$lang = $this -> strQuote($array["lang"]);
			$protected = $this -> strQuote($array["protected"]);
			$verified = $this -> strQuote($array["verified"]);
			$is_translator = $this -> strQuote($array["is_translator"]);
			
			/*** Insert Query***/
			switch ($tablename) {
				case 'Follows':
				case 'Followers':
				case 'Blocks':
					$sql = ('
					INSERT INTO
					' . $tablename . '_' . $Userid . '(
					Userid,Screen_name,Name,IconURL,Bio,Tweet,Follow,Follower,Favorite,Lang,Protect,Verified,Traslations)
					VALUE
					('.$array["id_str"].','.$screen_name.','.$name.','.$profile_image_url.','.$description.','.$array["statuses_count"].','.$array["friends_count"].','.$array["followers_count"].','.$array["favourites_count"].','.$lang.','.$protected.','.$verified.','.$is_translator.')
					');
					break;
					
				case 'Friends':
					$sql = ('
					INSERT INTO
					' . $tablename . '_' . $Userid . '
					(Userid)
					VALUE
					('.$array["id_str"].')
					');
					break;
			}
			$this -> runQuery($sql);
			return $sql;
		}else{
			return FALSE;
		}
	}

	/*
	 * APIKeyの取り出し (パスワード使用)
	 */
	public function get_password_apikey($username, $password)
	{
		$sql = ('SELECT Id , Password FROM Account_info WHERE (Username = "' . $username . '" and Password = "' . $password . '")');
		$data = $this -> getQuery($sql);
		foreach ($data as $d) {
			return array('Id' => $d["Id"], 'Password' => $d["Password"]);
		}
	}

	/*
	 * APIKeyの取り出し(内部用)
	 */
	public function get_id_apikey($Id)
	{
		$sql = ('SELECT Apikey FROM Account_info WHERE Id = ' . $Id);
		$data = $this -> getQuery($sql);
		foreach ($data as $d) {
			return $d["Apikey"];
		}
	}
	
	/*
	 * Usernameの取り出し
	 */
	public function get_id_username($Id)
	{
		$sql = ('SELECT Username FROM Account_info WHERE Id = '.$Id);
		foreach ($this->getQuery($sql) as $d) {
			return $d["Username"];
		}
	}
	/*
	 *
	 */
	public function get_id_password($Id)
	{
		$sql = ('SELECT Password FROM Account_info WHERE Id = '.$Id);
		foreach ($this->getQuery($sql) as $d) {
			return $d["Password"];
		}
	}
	
	/*
	 * Twitterのアカウントを新規に記録
	 */
	public function add_twitteraccount($Userid, $Id, $Screen_name, $Access_token, $Access_secret, $Tweet, $Favorite, $Follow, $Follower)
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
		(' . $Userid . ',' . $Id . ',"' . $Screen_name . '","' . $Access_token . '","' . $Access_secret . '",' . $Tweet . ',' . $Favorite . ',' . $Follow . ',' . $Follower . ')
		');
		$this -> runQuery($sql);
		return $sql;
	}

	/*
	 * Twitterのアカウントを削除
	 */
	public function delete_twitteraccount($Id, $Userid)
	{
		$sql = ('
		 DELETE
		 FROM
		 TwitterAccount_info
		 WHERE
		 Id = ' . $Id . '
		 AND
		 Userid = ' . $Userid . '
		 ');
		$this -> runQuery($sql);

		$sql = ('
		 DROP
		 TABLE
		 Follows_' . $Userid . ',
		 Followers_' . $Userid . ',
		 Friends_' . $Userid . ',
		 Followings_' . $Userid . ',
		 Fans_' . $Userid . ',
		 Blocks_' . $Userid . ',
		 Old_Follows_' . $Userid . ',
		 Old_Followers_' . $Userid . '
		 ');
		$this -> runQuery($sql);

	}

	/*
	 * 紐付けているTwitterのアカウント数を取得
	 */
	public function get_count_twitteraccount($Id, $Userid)
	{
		$sql = ('
		SELECT count(*)
		FROM
		TwitterAccount_info
		WHERE
		(Id = ' . $Id . ' and Userid = ' . $Userid . ')
		');
		$data = $this -> getQuery($sql);
		foreach ($data as $d) {
			return $d["count(*)"];
		}
	}

	/*
	 * TwitterAccountに記録されているアカウントを削除
	 */
	public function delete_all_twitteraccount($Id, $Userid)
	{
		$sql = ('
		DELETE
		FROM
		TwitterAccount_info
		WHERE
		(Id = ' . $Id . ' and Userid = ' . $Userid . ')
		');
		$this -> getQuery($sql);
	}

	/*
	 *　Account_infoにデフォ垢を登録
	 */
	public function set_defaultuserid($Id, $userid)
	{
		$sql = ('
		UPDATE
		Account_info
		SET
		DefaultUserid = ' . $userid . '
		WHERE
		Id = ' . $Id . '
		');
		$this -> runQuery($sql);
	}

	/*
	 * Account_infoに記録されているデフォの垢を取得
	 */
	public function get_defaultuserid($Id)
	{
		$sql = ('
		SELECT DefaultUserid FROM Account_info WHERE Id = ' . $Id . '
		');
		$data = $this -> getQuery($sql);
		foreach ($data as $d) {
			return $d["DefaultUserid"];
		}
	}
	
	/*
	 * 相互数、片思い数、方思われ数等の情報の更新
	 */
	public function update_count($Id, $Userid, $Apikey, $colomn, $value)
	{
		$sql = ('
		UPDATE
		TwitterAccount_info
		SET
		'.$colomn.' = '.$value.'
		WHERE
		(Userid = '.$Userid.' and Id = '.$Id.')
		');
		$this->runQuery($sql);
	}

	/*
	 * TwitterAccountに記録されている全てのアカウントを取得
	 */
	public function show_all_twitteraccount($Id, $Apikey)
	{
		if ($Apikey == $this -> get_id_apikey($Id))	{
			$sql = ('
			SELECT *
			FROM
			TwitterAccount_info
			WHERE
			Id = ' . $Id . '
			');
			$data = $this -> getQuery($sql);
			foreach ($data as $d) {
				$geso[] = array('Userid' => $d["Userid"], 'Screen_name' => $d["Screen_name"], 'Access_token' => $d["Access_token"], 'Access_secret' => $d["Access_secret"], 'LastSync' => $d["LastSync"], 'Tweet' => $d["Tweet"], 'Favorite' => $d["Favorite"], 'Follow' => $d["Follow"], 'Follower' => $d["Follower"], 'Friends' => $d["Friends"], 'Following' => $d["Following"], 'Fans' => $d["Fans"], 'Blocks' => $d["Blocks"], 'Spams' => $d["Spams"]);
			}
			return $geso;
		} else {
			return FALSE;
		}
	}

	/*
	 * TwitterAccount_infoテーブルからアクセストークンやらを取る
	 */
	public function get_loggeduser_twitteruser($Id, $Userid, $Apikey)
	{
		if ($Apikey == $this -> get_id_apikey($Id)) {
			$sql = ('
			SELECT *
			FROM
			TwitterAccount_info
			WHERE
			Id = ' . $Id . '
			AND
			Userid = ' . $Userid . '
			');
			$data = $this -> getQuery($sql);
			foreach ($data as $d) {
				return array('Screen_name' => $d["Screen_name"], 'Access_token' => $d["Access_token"], 'Access_secret' => $d["Access_secret"], 'LastSync' => $d["LastSync"], 'Tweet' => $d["Tweet"], 'Favorite' => $d["Favorite"], 'Follow' => $d["Follow"], 'Follower' => $d["Follower"], 'Friends' => $d["Friends"], 'Following' => $d["Following"], 'Fans' => $d["Fans"], 'Blocks' => $d["Blocks"], 'Spams' => $d["Spams"]);
			}
		} else {
			return FALSE;
		}
	}

}
