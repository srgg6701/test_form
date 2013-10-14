<?
class Db{
	public function getConnect(
					$host='localhost',
					$db_name='test',
					$username = 'root',
					$password = ''
				){
		// set REAL connect params:
		if(!$host) 		$host		='localhost';
		if(!$db_name) 	$db_name	='test';
		if(!$username) 	$username	='root';		
		
		$dsn = 'mysql:host='.$host.'.;dbname='.$db_name;
		$options = array(
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		);
		try {
			$dbh = new PDO($dsn, $username, $password);
			return $dbh;
		} catch (PDOException $e) {
			echo 'Connection has failed. The reason is: ' . $e->getMessage();
			return false;
		} 
	}
/**
 * for Insert, Update, Delete queries:
 */	
	public function execute($query){
		$Db=$this->getConnect();
		if(!$rows=$Db->exec($query)){
			$this->showError($Db);	
			return false;
		}else
			return $rows;
	}
	
	private function showError($Db){
		echo '<br>execute has failed: </br>';
		var_dump($Db->errorInfo());
		die();
	}
}?>