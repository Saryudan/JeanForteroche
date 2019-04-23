<?php 

/**
 * An abstract class to manage the connection to the database
 * 1. "$connection": to store a previous connection and avoid duplicates
 * 2. "checkConnection()": returns the result of the old connection or produces a new one
 * 3. "getConnection()": make the connection
 * 4. "createRequest()" : for specials requests (prepare/execute) / "protected" for inheritance
 */

namespace App\Src\Managers;

use PDO;
use Exception;


abstract class DataBaseManager
{
	private $connection;


	private function checkConnection()
	{
		if ($this->connection === null)
		{
			return $this->getConnection();
		} 
		else 
		{
			return $this->connection;
		}
	}


	private function getConnection()
	{
		try
		{
			$this->connection = new PDO(DB_HOST, DB_USER, DB_PASSWORD);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $this->connection;
		}

		catch(Exception $errorConnect)
		{
			"Erreur: " . $errorConnect->getMessage();
		}
	}


	protected function createRequest($sql, $parameters = null)
	{
		if ($parameters)
		{
			$result = $this->checkConnection()->prepare($sql);
			$result->setFetchMode(PDO::FETCH_CLASS, static::class);
			$result->execute($parameters);
			return $result;
		} 
		else 
		{
			$result= $this->checkConnection()->query($sql);
			$result->setFetchMode(PDO::FETCH_CLASS, static::class);
			return $result;
		}
	}
}