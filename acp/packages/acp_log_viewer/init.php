<?php
/*
 * Copyright (c) 2010 Litotex
 * 
 * Permission is hereby granted, free of charge,
 * to any person obtaining a copy of this software and
 * associated documentation files (the "Software"),
 * to deal in the Software without restriction,
 * including without limitation the rights to use, copy,
 * modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit
 * persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions
 * of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */
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
		
	
		$result =  Package::$pdb->query("SELECT COUNT(*) AS count FROM lttx1_log where log_type ='0' ");
		$result = $result->fetch(); 
		$count = $result['count'];
		$limit=$count;
		
		
		if( $count >0 ) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) 	$page=$total_pages; 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1) 
	
		$SQL = "SELECT lttx1_log.*, lttx1_users.username  FROM lttx1_log LEFT OUTER JOIN lttx1_users ON (lttx1_log.userid=lttx1_users.ID)  where log_type ='0' ORDER BY $sidx $sord LIMIT $start , $limit"; 
		$result =  Package::$pdb->query($SQL );
		
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0; 
		foreach($result as $log){
			$responce->rows[$i]['id']=$log['ID']; 
			$responce->rows[$i]['cell']=array($log['ID'],$log['username'],$log['logdate'],$log['message']); 
			$i++; 
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
		
	
		$result =  Package::$pdb->query("SELECT COUNT(*) AS count FROM lttx1_log WHERE package_action = 'database'");
		$result = $result->fetch(); 
		$count = $result['count'];
		$limit=$count;
		
		
		if( $count >0 ) { 
			$total_pages = ceil($count/$limit); 
		} else { 
			$total_pages = 0; 
		} 
		if ($page > $total_pages) 	$page=$total_pages; 
		$start = $limit*$page - $limit; // do not put $limit*($page - 1) 
	
		$SQL = "SELECT * FROM lttx1_log  WHERE package_action = 'database' ORDER BY $sidx $sord LIMIT $start , $limit"; 
		$result =  Package::$pdb->query($SQL);
		
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count; 
		
		$i=0; 
		foreach($result as $log){
			$responce->rows[$i]['id']=$log['ID']; 
			$responce->rows[$i]['cell']=array($log['ID'],$log['logdate'],$log['message']); 
			$i++; 
		} 
		
		 echo json_encode($responce);
		return true;

	}

	
	
	public static function registerHooks(){
		return true;
	}
}
