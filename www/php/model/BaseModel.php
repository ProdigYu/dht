<?php

class BaseModel{
	public static $db_connection;

	public function __construct(){
		if (!isset(self::$db_connection)){
			$host = Config::get('app.database.host');
			$port = Config::get('app.database.port');
			$user = Config::get('app.database.user');
			$password = Config::get('app.database.password');

			$dbname = Config::get('app.database.dbname');

			self::$db_connection = new PDO("mysql:host={$host};dbname={$dbname}", $user, $password);

			self::$db_connection->exec('set names utf8');
		}
	}

	public function select($columns, $where_prepare='', $bindParam=[]){
		if (!is_array($columns)){
			$columns = [$columns];
		}

		$sql = 'select ';
		if ($columns == ['*']){
			$sql .= ' * from ' . $this->table;
		} else {
			foreach ($columns as $v) {
				$sql .= '`$v`,';
			}
			$sql = rtrim($sql, ',');
		}
		if (!empty($where_prepare)){
			$sql .= ' where ' . $where_prepare;
		}
		$statement = self::$db_connection->prepare($sql);
		foreach ($bindParam as $k => $v) {
			$statement->bindParam($k+1, $v);
		}
		$statement->execute();
		return $statement->fetchAll(\PDO::FETCH_ASSOC);
	}
}
