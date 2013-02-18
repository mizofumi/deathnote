<?php
include 'db.php';

/**
 * SQL Wrapper
 */
class DB_SQL extends DB{
	
	public function makeRandomString($length)
	{
		$str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z"'));
		for ($i = 0; $i < $length; $i++){
			$r_str .= $str[rand(0, count($str)-1)];
		}
		return $r_str;
	}
	
	public function signup($username, $password , $question , $answer)
	{
		$sql = ('
		INSERT INTO Account_info
		(Username , Password , Question , Answer)
		VALUE
		("'.$username.'","'.$password.'","'.$question.'","'.$answer.'")
		');
		$this->runQuery($sql);
	}
	
	public function login($username)
	{
		$sql = ('SELECT Id , Password FROM Account_info WHERE Username = "'.$username.'"');
		$data = $this->getQuery($sql);
		foreach ($data as $d) {
			return array( 'Id' => $d["Id"], 'Password' => $d["Password"]);
		}
	}
	
	public function add_twitteraccount($Userid,$Id,$Access_token,$Access_secret,$Tweet,$Favorite,$Follow,$Follower)
	{
		$rand = $this->makeRandomString(50);
		$sql = ('
		INSERT INTO
		TwitterAccount_info
		(
		Userid,
		Id,
		Apikey,
		Access_token,
		Access_secret,
		Tweet,
		Favorite,
		Follow,
		Follower)
		VALUE
		('.$Userid.','.$Id.',"'.$rand.'","'.$Access_token.'","'.$Access_secret.'",'.$Tweet.','.$Favorite.','.$Follow.','.$Follower.')
		');
		$this->runQuery($sql);
		return $sql;
	}
	
	public function get_twitteraccount_count($Id,$Userid)
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
	
	public function delete_twitteraccount($Id,$Userid)
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
	
	public function loadaccount($Id)
	{
		$sql = ('
		SELECT DefaultUserid FROM Account_info WHERE Id = '.$Id.'
		');
		$data = $this->getQuery($sql);
		foreach ($data as $d) {
			return $d["DefaultUserid"];
		}
	}
}