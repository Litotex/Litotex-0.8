<?php
class LitotexPDO extends pdo {
    private $queryCount;
    
    public function __construct($dsn, $user = NULL, $pass = NULL, $options = NULL) {
        $this->queryCount = 0;
        parent::__construct($dsn, $user, $pass, $options);

        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('LitotexPDOStatement', array($this)));

    }
    public function exec($sql) {
		$this->increaseQueryCount();
        return parent::exec($sql);
    }
    public function query($sql) {
		$this->increaseQueryCount();
        $args = func_get_args();
		return call_user_func_array(array($this, 'parent::query'), $args);        
    }
    public function getQueryCount() {
        return $this->queryCount;
    }
    public function increaseQueryCount() {
        $this->queryCount++;
    }
}
?>