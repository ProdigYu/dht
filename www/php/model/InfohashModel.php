<?php

class InfohashModel extends BaseModel{
	public $table = 'infohash';

	public function get($where=[]){
		if (isset($where['id'])){
			$sql = "select * from {$this->table} where id=?";
			$statement = self::$db_connection->prepare($sql);
			$statement->bindParam(1, $where['id']);
		} else if (isset($where['hash'])){
			$sql = "select * from {$this->table} where hash=?";
			$statement = self::$db_connection->prepare($sql);
			$statement->bindParam(1, $where['hash']);
		}
		$statement->execute();
		return $statement->fetch(\PDO::FETCH_ASSOC);
	}
}
