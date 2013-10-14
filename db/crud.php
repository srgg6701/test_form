<?	require_once 'connect_db.php';
/**
 * Prototypes 
 */
interface Actions{
    function create();
	function read();
    function update();
    function delete();
}
/**
 * override the interface's methods 
 */
class CRUD implements Actions{
    
	public $action;
	public $content;
	public $no_params_mess = '<div>No params for query to implement</div>';
	
    public function __construct($keep_submit=false) {
		// check a command to do something
		// Only if got the URL param 'do'
		if($this->action=$_GET['do'])
			$this->handleRequest($keep_submit);
    }
/**
 * Only if got the URL param 'do'
 */
	public function handleRequest($keep_submit=false){
		// assign a method (post/get)
		$method=$this->action;
		if ($_SERVER['REQUEST_METHOD']=='POST')
			$params=$_POST;
		elseif ($_SERVER['REQUEST_METHOD']=='GET')
			$params=$_GET;
		if($params){
			if(!$keep_submit)
				unset($params['submit']);
			$this->$method($params);
		}
	}

    public function create($table,$data) {
        //var_dump($data);
		/*	array
  			  'name' => string 'Сержик' (length=12)
			  'contacts' => string 'на лавочке' (length=19)
			  'submit' => string 'Отправить' (length=18)
			  */
			$Db=new Db();
			$keys=$vals=array();
			foreach($data as $key=>$value){
				$keys[]=$key;
				$vals[]=$value;
			}
			$fields='`'.implode('`, `',$keys).'`';
			$values="'".implode("', '",$vals)."'";
			$query="INSERT INTO ".$table." (".$fields.", `datetime`) VALUES (".$values.", '".date('Y-m-d H:i:s')."')";
			if($rows=$Db->execute($query)){
				echo "<div>Tne number of added values: ".$rows."</div>";
				return true;
			}else{
				echo $this->no_params_mess;
				return false;
			}
    }

	public function read($query){
		$Db=new Db();
		$Db->getConnect();
		// returns an associative array:
		if(!$result=$Db->query($query)->fetchAll(PDO::FETCH_ASSOC)){
			$this->showError($Db);	
			return false;
		}else
			return $result;
	}
	
    public function update($table, $data, $where=false) {
		$Db=new Db();
		$query="UPDATE ". $table ." SET " . $data;
		if($where)
			$query.=" WHERE " . $where;
		if($rows=$Db->execute($query)){
			echo "<div>Table $table was updated. Affected rows count: ".$rows."</div>";
			return true;
		}else{
			echo $this->no_params_mess;
			return false;
		}
    }

    public function delete($table, $where=false) {
		$Db=new Db();
		$query="DELETE FROM ". $table;
		if($where)
			$query.=" WHERE " . $where;
		if($rows=$Db->execute($query)){
			echo "<div>Data from table $table was deleted. Affected rows count: ".$rows."</div>";
			return true;
		}else{
			echo $this->no_params_mess;
			return false;
		}
    }

}
