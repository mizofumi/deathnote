<?php

class DB
{
	/*** mysql hostname ***/
	private $hostname = 'localhost';

	/*** mysql username ***/
	private $username = 'test';
	
	/*** mysql password ***/
	private $password = 'test';

	/*** mysql password ***/
	private $dbName = 'test';

	public $dbh = NULL;

	public function __construct()
	{
		try
		{
			$this->dbh = new PDO("mysql:host=$this->hostname;dbname=$this->dbName", $this->username, $this->password);
		}
		catch(PDOException $e)
		{
			echo __LINE__.$e->getMessage();
		}
	}

	public function __destruct()
	{
		$this->dbh = NULL;
	}
	
	public function strQuote($string)
	{
		return $this->dbh->quote($string);
	}
	
	public function runQuery($sql)
	{
		/*
		try
		{
			//echo $sql;
			$count = $this->dbh->exec($sql) or print_r($this->dbh->errorInfo());
		}
		catch(PDOException $e)
		{
			echo __LINE__.$e->getMessage();
		}
		 */
		try
		{
			$value = $this->dbh->prepare($sql);
			$value->execute();
			//$count = $this->dbh->execute($value) or $count = $this->dbh->errorInfo();
		}
		catch(PDOException $e)
		{
			echo __LINE__.$e->getMessage();
		}
	}

	public function getQuery($sql)
	{
		$stmt = $this->dbh->query($sql);

	    $stmt->setFetchMode(PDO::FETCH_ASSOC);

		return $stmt;
	}

}
?>