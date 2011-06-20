<?php
class package_acp_news extends acpPackage{
	
	protected $_availableActions = array('main', 'new', 'edit', 'list', 'save', 'delete','activate','deactivate','allow_comments','forbid_comments','categories_list','categories_edit','categories_save','categories_delete','categories_show_list');
	
	public static $dependency = array('acp_config');
	
	protected $_packageName = 'acp_news';
	
	protected $_theme = 'main.tpl';

	/**
	* Init
	*/
	public function __action_main(){
		self::addJsFile('news.js', 'acp_news');
		self::addCssFile('news.css', 'acp_news');
		self::addJsFile('ckeditor/ckeditor.js',false);
		self::addJsFile('ckfinder/ckfinder.js',false);
		return true;
	}

	/**
	* Make new News
	*/
	public function __action_new(){
		$this->__action_edit();
		return true;
	}
	/**
	* Edit News News
	*/
	public function __action_edit(){
		$FilemanagerFolder=package::getTplURL()."js/pdw_file_browser/";
		$folder=package::getFilesDir('news');
		// Create Session for Filemanger
		$_SESSION['uploadfolder']=$folder.'/';			
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
		package::$tpl->assign('FileBrowser', $FilemanagerFolder );
		return true;
	}

	/**
	* activate News
	*/
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
	
	/**
	* deactivate News
	*/
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

	/**
	* allow News comments
	*/
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
	
	public function __action_categories_list(){
		self::addJsFile('news.js', 'acp_news');
		self::addCssFile('news.css', 'acp_news');
		$this->_theme = 'cat_list.tpl';
		return true;
	}	
	
	public function __action_categories_show_list(){
		self::addJsFile('news.js', 'acp_news');
		$this->_theme = 'show_catlist.tpl';
		$elements = array();
    	$searchResults =self::$db->Execute("SELECT * FROM `lttx1_news_categories` order by title");
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
	

	public function __action_categories_edit(){
		$this->_theme = 'edit_cat.tpl';
		
		$iNewsId =0;
		$cat_titel="";
		$cat_description="";
		if(isset($_GET['id'])){
			$iNewsId = (int)$_GET['id'];
		}		
		if ($iNewsId > 0) {
            
        
		$result = package::$db->Execute("SELECT * FROM `lttx1_news_categories` WHERE `id` = ?",$iNewsId);
		if(!$result || !$result->RecordCount() ){
				throw new lttxError('LN_DB_ERRROR_1');
				return true;
		}
				
		$cat_titel =$result->fields['title'];
		$cat_description =$result->fields['description'];
		}
		
		package::$tpl->assign('cat_titel', $cat_titel );
		package::$tpl->assign('cat_description', $cat_description );
		package::$tpl->assign('edit_id', $iNewsId );
		
        return true;
	}	
	
	public function __action_categories_save(){
		$this->_theme = 'empty.tpl';
		$iNewsID=0;
		if (isset($_GET['id'])) {
            $iNewsID = (int) $_GET['id'];
        }		

		if(isset($_POST['OvalueTitle'])){
			$cat_title = $_POST['OvalueTitle'];
		} else {
			throw new lttxInfo('LN_NEWS_ERROR_DEFAULT');
		}

		if(isset($_POST['OvalueDesc'])){
			$cat_description = $_POST['OvalueDesc'];
		} else {
			throw new lttxInfo('LN_NEWS_ERROR_DEFAULT');
		}

		if ($iNewsID > 0) {
			self::$db->Execute('UPDATE lttx1_news_categories
                            SET
                                title = ?,
                                description = ?
                            WHERE `id` = ?',
                            array(
                                $cat_title,
                                $cat_description,
                                $iNewsID
                            ));
		}else{
			self::$db->Execute('insert into lttx1_news_categories 
                             (newsLastDate,newsNum,title,description) values
							 (?,?,?,?)',
                            array(
                                '0000-00-00 00:00:00',
								0,
								$cat_title,
                                $cat_description,
                            ));
		
		}
	header('Location: index.php?package=acp_news&action=categories_list');
	}	
	
	
	public static function registerHooks(){
		return true;
	}
}