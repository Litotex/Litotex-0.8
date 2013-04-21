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
class package_content extends Package{
	protected $_packageName = 'content';
	protected $_theme = 'main.tpl';
	protected $_availableActions = array('main');
	public function __action_main(){
		$id = (isset($_GET['ID']))?$_GET['ID']:0;
		$contentData = self::$pdb->prepare("SELECT `title`, `text`, `lastEdit`, `editUser` FROM `lttx1_contents` WHERE `ID` = ?");
		$contentData->execute(array($id));
		$contentData = $contentData->fetch();
		if(!isset($contentData[0])){
			$error = self::$packages->loadPackage(LITO_ERROR_MODULE, true);
                        if(!$error){
                                header('HTTP/ 500');
                                die('<h1>Internal Server Error</h1><p>Whoops something went wrong!</p>');
                        }
                        $error->__action_404();
		}
		self::$tpl->assign('PAGE_TITLE', $contentData[0]);
		self::$tpl->assign('title', $contentData[0]);
		self::$tpl->assign('text', $contentData[1]);
		self::$tpl->assign('editUser', new User($contentData[3]));
		return true;
	}
}
