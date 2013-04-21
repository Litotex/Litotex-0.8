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
		//self::addJsFile('ckfinder/ckfinder.js',false);
		return true;
	}

	/**
	 * Make new News
	 */
	public function __action_new(){
		$this->__action_edit(true);
		return true;
	}
	/**
	 * Edit News News
	 */
	public function __action_edit($new_entry=false){
		$NewsTitle="";
		$NewsText="";
		$NewsComments="";
		$iNewsId=0;
		$Newscategory=0;
		$folder=Package::getFilesDir('news');
		// Create Session for Filemanger
		$_SESSION['uploadfolder']=$folder.'/';
		$FilemanagerFolder=Package::getTplURL()."js/pdw_file_browser/";
		
		
		
		$this->_theme = 'edit.tpl';
		$iNewsId = 0;

		if(isset($_GET['id'])){
			$iNewsId = (int)$_GET['id'];
		}
		if ($iNewsId > 0) {


			$result = Package::$pdb->prepare("SELECT * FROM `lttx1_news` WHERE `id` = ?");
			$result->execute(array($iNewsId));
			if($result->rowCount() < 1){
				throw new LitotexError('LN_DB_ERRROR_1');
				return true;
			}

			$result = $result->fetch();
			$NewsTitle =$result['title'];
			$NewsText =$result['text'];
			$NewsComments =$result['allow_comments'];
			$Newscategory =$result['category'];
		}

		$cat_elements[]="";
		$categories = Package::$pdb->query("SELECT id,title FROM `lttx1_news_categories` order by title");
		foreach($categories as $category) {
			$cat_elements[$category['id']] = $category['title'];				
		}

		Package::$tpl->assign('cat_options_sel', $Newscategory);
		Package::$tpl->assign('cat_options', $cat_elements);
		Package::$tpl->assign('News_Title', $NewsTitle );
		Package::$tpl->assign('News_Text', $NewsText );
		Package::$tpl->assign('News_Comments', $NewsComments);
		Package::$tpl->assign('News_ID', $iNewsId);
		Package::$tpl->assign('FileBrowser', $FilemanagerFolder );
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

		$searchResults =self::$pdb->query("update `lttx1_news` set active=1 where id ='".$newsId."'");
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

		$results =self::$pdb->query("update `lttx1_news` set active=0 where id ='".$newsId."'");
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

		$results =self::$pdb->query("update `lttx1_news` set allow_comments=1 where id ='".$newsId."'");
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

		$results =self::$pdb->query("update `lttx1_news` set allow_comments=0 where id ='".$newsId."'");
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

		$results =self::$pdb->query("delete from `lttx1_news` where id ='".$newsId."'");

		return true;
	}

	public function __action_list(){

		$this->_theme = 'list.tpl';
		$elements = array();
		$searchResults =self::$pdb->query("SELECT * FROM `lttx1_news` order by date DESC");
		
		if($searchResults == false){
			throw new LitotexDBError();
		}
		foreach($searchResults as $result){		
			$result['commentcount']=self::_getCommentCount($result[0]);
			$elements[] = $result;
			
		}
		self::$tpl->assign('aOptions', $elements);

		return true;
	}

	public function __action_save(){
		$this->_theme = 'empty.tpl';
		$iNewsID=0;
		$category=0;
		$saveDate=date("Y-m-d H:m:s", time());

		$saveUserID=Package::$user->getUserID();

		if(isset($_POST['news_text'])){
			$news_text = $_POST['news_text'];
		} else {
			throw new LitotexInfo('LN_NEWS_ERROR_DEFAULT');
		}
		if(isset($_POST['categories_id'])){
			$category=$_POST['categories_id'];
		} 
		
		if(isset($_POST['news_over'])){
			$news_title = htmlspecialchars($_POST['news_over']);
		} else {
			throw new LitotexInfo('LN_NEWS_ERROR_DEFAULT');
		}

		if (isset($_GET['id'])) {
			$iNewsID = (int) $_GET['id'];
		}
		if ($iNewsID > 0) {
			self::$pdb->prepare('UPDATE lttx1_news
                            SET
                                category=?,
								title = ?,
                                text = ?,
                                allow_comments = ?
                            WHERE `id` = ?')->execute(
			array(
			$category,
			$news_title,
			$news_text,
            '0',
			$iNewsID
			));
		}else{
			self::$pdb->prepare('INSERT into lttx1_news
                            (title,text,date,writtenBy,active,allow_comments,category)
							 values
							 (?,?,?,?,?,?,?)')->execute(
			array(
			$news_title,
			$news_text,
			$saveDate,
			$saveUserID,
                                '0',
								'0',
								'0'
			));


		}
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
		$searchResults =self::$pdb->query("SELECT * FROM `lttx1_news_categories` order by title");
		foreach($searchResults as $element) {
			$elements[] = $element;
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
			$result = self::$pdb->prepare("SELECT * FROM `lttx1_news_categories` WHERE `id` = ?");
			$result->execute(array($iNewsId));
			if($result->rowCount() < 1){
				throw new LitotexError('LN_DB_ERRROR_1');
				return true;
			}

			$result = $result->fetch();
			$cat_titel =$result['title'];
			$cat_description =$result['description'];
		}

		Package::$tpl->assign('cat_titel', $cat_titel );
		Package::$tpl->assign('cat_description', $cat_description );
		Package::$tpl->assign('edit_id', $iNewsId );

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
			throw new LitotexInfo('LN_NEWS_ERROR_DEFAULT');
		}

		if(isset($_POST['OvalueDesc'])){
			$cat_description = $_POST['OvalueDesc'];
		} else {
			throw new LitotexInfo('LN_NEWS_ERROR_DEFAULT');
		}

		if ($iNewsID > 0) {
			self::$pdb->prepare('UPDATE lttx1_news_categories
                            SET
                                title = ?,
                                description = ?
                            WHERE `id` = ?')->execute(
			array(
			$cat_title,
			$cat_description,
			$iNewsID
			));
		}else{
			self::$pdb->prepare('insert into lttx1_news_categories
                             (newsLastDate,newsNum,title,description) values
							 (?,?,?,?)')->execute(
			array(
                                '0000-00-00 00:00:00',
			0,
			$cat_title,
			$cat_description,
			));

		}
			return true;
	}


	public static function registerHooks(){
		return true;
	}
/**
* Returns numbers of comments
* @return int
*/
	private function _getCommentCount($ID){
		$result = Package::$pdb->prepare("SELECT COUNT(`ID`) FROM `lttx1_news_comments` WHERE `news` = ?");	
		$result->execute(array($ID));
		if($result->rowCount() < 1)
            return 0;
		$result = $result->fetch();
		return $result[0];
	}
 
	
}