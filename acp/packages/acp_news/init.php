<?php
class package_acp_news extends acpPackage{
	
	protected $_availableActions = array('main', 'new', 'edit', 'list', 'save', 'delete','activate','deactivate','allow_comments','forbid_comments');
	
	public static $dependency = array('acp_config');
	
	protected $_packageName = 'acp_news';
	
	protected $_theme = 'main.tpl';
	
	public function __action_main(){
		/*
		echo(package::getFilesDir('news'));
		echo('<br>');
		echo(package::getFilesURL('news'));
		exit();
		*/
		//define('DOCUMENTROOT', realpath((getenv('DOCUMENT_ROOT') && preg_match('#^'.preg_quote(realpath(getenv('DOCUMENT_ROOT'))).'#', realpath(__FILE__))) ? getenv('DOCUMENT_ROOT') : str_replace(dirname(@$_SERVER['PHP_SELF']), '', str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__)))));
		

		$folder=package::getFilesDir('news');

		$_SESSION['uploadfolder']=$folder.'/';	
		self::addJsFile('news.js', 'acp_news');
		self::addJsFile('ckeditor/ckeditor.js',false);
		self::addJsFile('ckfinder/ckfinder.js',false);

		return true;
	}

	public function __action_new(){
		$this->__action_edit();
		return true;
	}
	
	public function __action_edit(){
		
		
		
		

		$this->_theme = 'edit.tpl';
		
		$iNewsId = 0;
		
		if(isset($_GET['id'])){
			$iNewsId = (int)$_GET['id'];
		}		
		 if ($iNewsId <= 0) {
            return false;
        }
		
		
		$result = package::$db->Execute("SELECT * FROM `lttx1_news` WHERE `id` = ?",$iNewsId);
		
		
		if(!$result || !$result->RecordCount() ){
				throw new lttxError('LN_DB_ERRROR_1');
				return true;
		}
		
				
		$NewsTitle =$result->fields['title'];
		$NewsText =$result->fields['text'];
		$NewsComments =$result->fields['allow_comments'];

		package::$tpl->assign('News_Title', $NewsTitle );
		package::$tpl->assign('News_Text', $NewsText );
		package::$tpl->assign('News_Comments', $NewsComments);
		package::$tpl->assign('News_ID', $iNewsId);
        return true;
			

		return true;
	}
	public function __action_activate(){
		$this->_theme = 'empty.tpl';
		if(isset($_POST['id'])){
			$newsId = (int)$_POST['id'];
		} else if(isset($_GET['id'])){
			$newsId = (int)$_GET['id'];
		}
		 if ($newsId <= 0) {
            return false;
        }

		$searchResults =self::$db->Execute("update `lttx1_news` set active=1 where id ='".$newsId."'");
		return true;
	}

	public function __action_deactivate(){
	$this->_theme = 'empty.tpl';
		if(isset($_POST['id'])){
			$newsId = (int)$_POST['id'];
		} else if(isset($_GET['id'])){
			$newsId = (int)$_GET['id'];
		}
		 if ($newsId <= 0) {
            return false;
        }
		
		$results =self::$db->Execute("update `lttx1_news` set active=0 where id ='".$newsId."'");
		return true;
	}	

	public function __action_allow_comments(){
	$this->_theme = 'empty.tpl';
		if(isset($_POST['id'])){
			$newsId = (int)$_POST['id'];
		} else if(isset($_GET['id'])){
			$newsId = (int)$_GET['id'];
		}
		 if ($newsId <= 0) {
            return false;
        }
		
		$results =self::$db->Execute("update `lttx1_news` set allow_comments=1 where id ='".$newsId."'");
		return true;
	}	

	public function __action_forbid_comments(){
	$this->_theme = 'empty.tpl';
		if(isset($_POST['id'])){
			$newsId = (int)$_POST['id'];
		} else if(isset($_GET['id'])){
			$newsId = (int)$_GET['id'];
		}
		 if ($newsId <= 0) {
            return false;
        }
		
		$results =self::$db->Execute("update `lttx1_news` set allow_comments=0 where id ='".$newsId."'");
		return true;
	}	

	
	public function __action_delete(){

		$this->_theme = 'empty.tpl';
		if(isset($_POST['id'])){
			$newsId = (int)$_POST['id'];
		} else if(isset($_GET['id'])){
			$newsId = (int)$_GET['id'];
		}

		 if ($newsId <= 0) {
            return false;
        }
		
		$results =self::$db->Execute("delete from `lttx1_news` where id ='".$newsId."'");

		return true;
	}
	
	public function __action_list(){
		
     $this->_theme = 'list.tpl';
		$elements = array();
    	$searchResults =self::$db->Execute("SELECT * FROM `lttx1_news` order by date");
		if($searchResults == false){
			throw new lttxDBError();
		}
    	
		 while(!$searchResults->EOF) {
			$elements[] = $searchResults->fields;
			
            $searchResults->MoveNext();
        }
        self::$tpl->assign('aOptions', $elements);

        return true;
	}
	
	public function __action_save(){

		
		$this->_theme = 'empty.tpl';
		$iNewsID=0;
		if (isset($_GET['id'])) {
            $iNewsID = (int) $_GET['id'];
        }		
		if ($iNewsID <= 0) {
            return false;
        }

		
		if(isset($_POST['news_text'])){
			$news_text = $_POST['news_text'];
		} else {
			throw new lttxInfo('LN_NEWS_ERROR_DEFAULT');
		}

		if(isset($_POST['news_over'])){
			$news_title = $_POST['news_over'];
		} else {
			throw new lttxInfo('LN_NEWS_ERROR_DEFAULT');
		}

		
		self::$db->Execute('UPDATE lttx1_news
                            SET
                                title = ?,
                                text = ?,
                                allow_comments = ?
                            WHERE `id` = ?',
                            array(
                                $news_title,
                                $news_text,
                                '0',
                                $iNewsID
                            ));
		
	header('Location: index.php?package=acp_news');

        return true;
	}
	
	public static function registerHooks(){
		return true;
	}
}