<?php
session_start();
include_once 'setting.php';
include_once 'twitteroauth.php';

class TwitterAPI {
	const APIv = '1.1';

    function __construct($consumer_key, $consumer_secret, $access_token, $access_token_secret)
    {
        $this->oauth = new TwitterOAuth(
            $consumer_key,
            $consumer_secret,
            $access_token,
            $access_token_secret
        );
    }
 
	public function account_verify_credentials()
	{
	            $ikachan = $this->oauth->oAuthRequest(
	                'https://api.twitter.com/'.self::APIv.'/account/verify_credentials.json',
	                'GET',
	                array()
	            );
		return json_decode($ikachan, true);
	}
 

	/*
	 * 自分のタイムラインを取得
	 */
	public function Get_HomeTimeline($count = 200, $sinceId = -1, $maxId = -1) {
		$param[count] = $count;
		if ($sinceId > 0) {
			$param[since_id] = $sinceId;
		}
		if ($maxId > 0) {
			$param[max_id] = $maxId;
		}
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/statuses/home_timeline.json', 'GET', $param);
		return json_decode($geso, true);
	}

	/*
	 * 指定したStatusIdのステータスを取得
	 */
	public function GetStatus($statusId) {
		$param[include_entities] = 'true';
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/statuses/show' . $statusId . '.json', 'GET', $param);
		return json_decode($geso, true);
	}

	/*
	 * 指定したStatusIdのステータスを削除
	 */
	public function DestroyStatus($statusId) {
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/statuses/destroy/' . $statusId . '.json', 'POST', array());
		return json_decode($geso, true);
	}

	/*
	 * ツイーヨの投稿
	 * $text =　投稿文
	 * $inReplyTo = リプライ先のStatusId
	 */
	public function UpdateStatus($text, $inReplyTo = null) {
		$param[status] = $text;
		if ($inReplyTo != null) {
			$param[in_reply_to_status_id] = $inReplyTo;
		}
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/statuses/update.json', 'POST', $param);
		return json_decode($geso, true);
	}

	/*
	 * メンションの取得
	 */
	public function GetMentionsTimeline($count = 200, $sinceId = -1, $maxId = -1) {
		$param[count] = $count;
		if ($sinceId > 0) {
			$param[since_id] = $sinceId;
		}
		if ($maxId > 0) {
			$param[max_id] = $maxId;
		}
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/statuses/mentions_timeline.json', 'GET', $param);
		return json_decode($geso, true);
	}

	/*
	 * 指定したスクリーンネームのタイムラインを取得
	 * 指定なしの場合、自分のツイーヨを取得
	 */
	public function GetUserTimeline($screen_name = null, $count = 200, $sinceId = -1, $maxId = -1, $includeRts = false) {
		$param[count] = $count;
		$param[include_rts] = $includeRts;
		if ($screen_name != null) {
			$param[screen_name] = $screen_name;
		}
		if ($sinceId > 0) {
			$param[since_id] = $sinceId;
		}
		if ($maxId > 0) {
			$param[max_id] = $maxId;
		}
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/statuses/user_timeline.json', 'GET', $param);
		return json_decode($geso, true);
	}

	/*
	 * 自分のタイムラインを取得
	 */
	public function GetHomeTimeline($count = 200, $sinceId = -1, $maxId = -1) {
		$param[count] = $count;
		if ($sinceId > 0) {
			$param[since_id] = $sinceId;
		}
		if ($maxId > 0) {
			$param[mac_id] = $maxId;
		}
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/statuses/home_timeline.json', 'GET', $param);
		return json_decode($geso, true);
	}

	/*
	 * ふぁぼ追加
	 */
	public function CreateFavorite($statusId) {
		$param[id] = $statusId;
		$param[include_entities] = true;
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/favorites/create.json', 'POST', $param);
		return json_decode($geso, true);
	}

	/*
	 * ふぁぼ削除
	 */
	public function DestroyFavorite($statusId) {
		$param[id] = $statusId;
		$param[include_entities] = true;
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/favorites/destroy.json', 'POST', $param);
		return json_decode($geso, true);
	}

	/*
	 * リツイート追加
	 */
	public function Retweet($statusId) {
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/statuses/destroy/' . $statusId . '.json', 'POST', array());
		return json_decode($geso, true);
	}

	/*
	 * 指定ユーザーの情報をscreen_nameにて取得
	 */
	public function GetUser_screen_name($screen_name) {
		$param[screen_name] = $screen_name;
		$param[include_entities] = true;
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/users/show.json', 'GET', $param);
		return json_decode($geso, true);
	}

	/*
	 * 複数ユーザーの情報をscreen_nameにて取得(最大100件)
	 */
	public function GetUsers_screen_name($screen_name) {
		$param[screen_name] = implode(',', $screen_name);
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/users/lookup.json', 'GET', $param);
		return json_decode($geso, true);
	}

	/*
	 * 指定ユーザーの情報をUser_IDにて取得
	 */
	public function GetUser_user_id($user_id) {
		$param[user_id] = $user_id;
		$param[include_entities] = true;
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/users/show.json', 'GET', $param);
		return json_decode($geso, true);
	}

	/*
	 * 複数ユーザーの情報をUser_IDにて取得(最大100件)
	 */
	public function GetUsers_user_id($user_id) {
		$param[user_id] = implode(',', $user_id);
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/users/lookup.json', 'GET', $param);
		return json_decode($geso, true);
	}

	/*
	 * フォロー
	 */
	public function Follow_screen_name($screen_name) {
		$param[screen_name] = $screen_name;
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/friendships/create.json', 'POST', $param);
		return json_decode($geso, true);
	}

	/*
	 * リムーブ
	 */
	public function Remove_screen_name($screen_name) {
		$param[screen_name] = $screen_name;
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/friendships/destroy.json', 'POST', $param);
		return json_decode($geso, true);
	}

	/*
	 * 指定ユーザー間の関係を取得
	 */
	public function GetRelationship_screen_name($source, $target) {
		$param[source_screen_name] = $source;
		$param[target_screen_name] = $target;
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/friendships/show.json', 'GET', $param);
		return json_decode($geso, true);
	}

	/*
	 * 指定ユーザーをscreen_nameでブロック
	 */
	public function Block($screen_name) {
		$param[screen_name] = $screen_name;
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/blocks/create.json', 'GET', $param);
		return json_decode($geso, true);
	}

	/*
	 * 指定ユーザーをscreen_nameでブロック解除
	 */
	public function UnBlock($screen_name) {
		$param[screen_name] = $screen_name;
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/blocks/destroy.json', 'GET', $param);
		return json_decode($geso, true);
	}

	/*
	 * 指定ユーザーをscreen_nameでスパム報告
	 */
	public function ReportSpam($screen_name) {
		$param[screen_name] = $screen_name;
		$geso = $this -> oauth -> oAuthRequest('https://api.twitter.com/'.self::APIv.'/report_spam.json', 'GET', $param);
		return json_decode($geso, true);
	}
	
	/*
	 * 受信したダイレクトメッセージの取得
	 */
	 public function GetRecievedDirectMessage($count = 20, $page = 1,$sinceId = -1,$maxId = -1)
	 {
		 $param[count] = $count;
		 $param[page] = $page;
		 if($sinceId > 0){
		 	$param[since_id] = $sinceId;
		 }
		 if($maxId > 0){
		 	$param[max_id] = $maxId;
		 }
		 $geso = $this->oauth->oAuthRequest('https://api.twiiter.com/'.self::APIv.'/direct_messages.json','GET',$param);
		 return json_decode($geso,true);
	 }
	 
	 /*
	  * 送信したダイレクトメッセージの取得
	  */
	  public function GetSendDirectMessage($count = 20, $page = 1,$sinceId = -1,$maxId = -1)
	  {
		  $param[count] = $count;
		  $param[page] = $page;
		 if($sinceId > 0){
		 	$param[since_id] = $sinceId;
		 }
		 if($maxId > 0){
		 	$param[max_id] = $maxId;
		 }
		 $geso = $this->oauth->oAuthRequest('https://api.twiiter.com/'.self::APIv.'/direct_messages/sent.json','GET',$param);
		 return json_decode($geso,true);
	  }
	  
	  /*
	   * ダイレクトメッセージscreen_name指定での送信
	   */
	   public function SendDirectMessage_screen_name($screen_name, $text)
	   {
		   $param[screen_name] = $screen_name;
		   $param[text] = $text;
		   $geso = $this->oauth->oAuthRequest('https://api.twitter.com/'.self::APIv.'direct_messages/new.json','POST',$param);
		   return json_decode($geso,true);
	   }
	   
	   /*
	    * ダイレクトメッセージuser_id指定での送信
	    */
	    public function SendDirectMessage_user_id($user_id, $text)
		{
		   $param[user_id] = $user_id;
		   $param[text] = $text;
		   $geso = $this->oauth->oAuthRequest('https://api.twitter.com/'.self::APIv.'direct_messages/new.json','POST',$param);
		   return json_decode($geso,true);
		}
		
		/*
		 * 全てのフォロー中のUseridを取得
		 */
		public function get_friends($cursor = "-1"){
			while ($cursor !== "0"){
				$all_friends = $this->oauth->oAuthRequest(
					'https://api.twitter.com/'.self::APIv.'/friends/ids.json',
					'GET',
					array(
						'cursor' => $cursor
					)
				);		
				$cursor_info = json_decode($all_friends, true);
				$cursor = $cursor_info["next_cursor_str"];
			}
			return json_decode($all_friends, true);
		}
		/*
		 * 全てのフォロワーのUseridを取得
		 */
		public function get_follower($cursor = "-1"){
			while ($cursor !== "0"){
				$all_follower = $this->oauth->oAuthRequest(
					'http://api.twitter.com/'.self::APIv.'/followers/ids.json',
					'GET',
					array(
						'cursor' => $cursor
					)
				);		
				$cursor_info = json_decode($all_follower, true);
				$cursor = $cursor_info["next_cursor_str"];
			}
			return json_decode($all_follower, true);
		}
		/*
		 * 全てのブロック中のUseridを取得
		 */
		public function get_blocks($cursor = "-1"){
			while ($cursor !== "0"){
				$all_blocks = $this->oauth->oAuthRequest(
					'http://api.twitter.com/'.self::APIv.'/blocks/ids.json',
					'GET',
					array(
						'cursor' => $cursor
					)
				);		
				$cursor_info = json_decode($all_blocks, true);
				$cursor = $cursor_info["next_cursor_str"];
			}
			return json_decode($all_blocks, true);
		}
		/*
		 * プロフィールバナー取得
		 */
		 public function get_profile_banner($user_id)
		 {
		 	$param[user_id] = $user_id;
			$geso = $this->oauth->oAuthRequest('http://api.twitter.com/'.self::APIv.'/users/profile_banner.json','GET',$param);
			return json_decode($geso,true);
		 }
}
