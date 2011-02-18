<?php
class package_content extends package{
	protected $_packageName = 'content';
	protected $_theme = 'main.tpl';
	protected $_availableActions = array('main');
	public function __action_main(){
		$id = (isset($_GET['ID']))?$_GET['ID']:0;
		$contentData = self::$db->Execute("SELECT `title`, `text`, `lastEdit`, `editUser` FROM `lttx_contents` WHERE `ID` = ?", array($id));
		if(!isset($contentData->fields[0])){
			$error = self::$packages->loadPackage('errorPage', true);
                        if(!$error){
                                header('HTTP/ 500');
                                die('<h1>Internal Server Error</h1><p>Whoops something went wrong!</p>');
                        }
                        $error->__action_404();
		}
		self::$tpl->assign('PAGE_TITLE', $contentData->fields[0]);
		self::$tpl->assign('title', $contentData->fields[0]);
		self::$tpl->assign('text', $contentData->fields[1]);
		self::$tpl->assign('editUser', new user($contentData->fields[3]));
		return true;
	}
}
