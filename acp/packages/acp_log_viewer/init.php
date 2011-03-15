<?php
class package_acp_log_viewer extends acpPackage{
	
	protected $_availableActions = array('main', 'show_log_database','show_log','database','del_logs');
	
	
	public static $dependency = array('acp_config');
	
	protected $_packageName = 'acp_log_viewer';
	
	protected $_theme = 'main.tpl';
	
	public function __action_main(){

		self::addJsFile('i18n/grid.locale-en.js', 'acp_log_viewer');
		self::addJsFile('jquery.jqGrid.min.js', 'acp_log_viewer');

		self::addCssFile('ui.jqgrid.css', 'acp_log_viewer');

			
			
			return true;
	}

	public function __action_show_log(){

		$this->_theme = 'empty.tpl';
	
		$page = isset($_GET['page'])?$_GET['page']:1;	
		$limit = isset($_GET['rows'])?$_GET['rows']:200;	
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:"ID";
		$sord = isset($_GET['sord'])?$_GET['sord']:"DESC";
		
	
		$result =  package::$db->Execute("SELECT COUNT(*) AS count FROM lttx1_log where log_type ='0' "); 
		$count = $result->fields['count'];
		$limit=$count;
		
		
		if( $count >0 ) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) 	$page=$total_pages; 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1) 
	
		$SQL = "SELECT lttx1_log.*, lttx1_users.username  FROM lttx1_log LEFT OUTER JOIN lttx1_users ON (lttx1_log.userid=lttx1_users.ID)  where log_type ='0' ORDER BY $sidx $sord LIMIT $start , $limit"; 
		$result =  package::$db->Execute($SQL );
		
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0; 
		while(!$result->EOF){
			$responce->rows[$i]['id']=$result->fields['ID']; 
			$responce->rows[$i]['cell']=array($result->fields['ID'],$result->fields['username'],$result->fields['logdate'],$result->fields['message']); 
			$i++; 
			$result->MoveNext();
		} 
		
		 echo json_encode($responce);
		return true;
	}
	public function __action_show_log_database(){
		
		self::addJsFile('i18n/grid.locale-en.js', 'acp_log_viewer');
		self::addJsFile('jquery.jqGrid.min.js', 'acp_log_viewer');
		self::addCssFile('ui.jqgrid.css', 'acp_log_viewer');
		$this->_theme = 'database.tpl';
			return true;
	}
	
	public function __action_database(){
		$this->_theme = 'empty.tpl';
	
		$page = isset($_GET['page'])?$_GET['page']:1;	
		$limit = isset($_GET['rows'])?$_GET['rows']:200;	
		$sidx = isset($_GET['sidx'])?$_GET['sidx']:"ID";
		$sord = isset($_GET['sord'])?$_GET['sord']:"DESC";
		
	
		$result =  package::$db->Execute("SELECT COUNT(*) AS count FROM lttx1_log where log_type ='1' "); 
		$count = $result->fields['count'];
		$limit=$count;
		
		
		if( $count >0 ) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) 	$page=$total_pages; 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1) 
	
		$SQL = "SELECT *  FROM lttx1_log  where log_type ='1' ORDER BY $sidx $sord LIMIT $start , $limit"; 
		$result =  package::$db->Execute($SQL );
		
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0; 
		while(!$result->EOF){
			$responce->rows[$i]['id']=$result->fields['ID']; 
			$responce->rows[$i]['cell']=array($result->fields['ID'],$result->fields['logdate'],$result->fields['message']); 
			$i++; 
			$result->MoveNext();
		} 
		
		 echo json_encode($responce);
		return true;

	}

	
	
	public static function registerHooks(){
		return true;
	}
}