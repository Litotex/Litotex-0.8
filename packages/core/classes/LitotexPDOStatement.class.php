<?php

class LitotexPDOStatement extends PDOStatement {
    private $pdo;

    protected function __construct(LitotexPDO $pdo) {
        $this->pdo = $pdo;
    }


	
    public function execute($params = array()) {
		$this->pdo->increaseQueryCount();
		$result = parent::execute($params); 
		if (parent::errorCode() != PDO::ERR_NONE){
			$errorname = parent::errorInfo();
			$this->write_pdo_error($errorname[2],$params);
		}
    return $result;
    }
	
	function write_pdo_error ($message = false,$param=array())    {
		echo '<pre>';
		echo '<table cellpadding="5px" style="border:1px solid #ff0000;">';
		echo '<tr><td colspan="2" style="background-color: #ff0000;color:#FFFFFF">DB ERROR</td></tr>';
		echo '<tr><td colspan="2" style="border:1px solid #000000;background-color: #ccc;">Message: ' . $message . '</td></tr>';
		echo '<tr><td colspan="2" style="border:1px solid #000000;background-color: #eee;">Query: ' . $this->queryString . '</td></tr>';
		//echo '<tr><td colspan="2" style="border:1px solid #000000;background-color: #ccc;">Dump: ' . parent::debugDumpParams() . '</td></tr>';
		echo '</table>';
		echo '</pre>';
	}
}

?>