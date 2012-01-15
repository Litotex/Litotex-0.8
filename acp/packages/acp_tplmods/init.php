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

class package_acp_tplmods extends acpPackage{
	
	protected $_availableActions = array('main', 'frame');
	
	public static $dependency = array('acp_config');
	
	protected $_packageName = 'acp_tplmods';
	
	protected $_theme = 'main.tpl';
	
	public function __action_main(){
		
		self::addJsFile('tplmod.js', 'acp_tplmods');
		self::addCssFile('tplmod.css', 'acp_tplmods');

		Package::$tpl->assign('LITO_FRONTEND_URL', LITO_FRONTEND_URL);

		return true;
	}

	public function __action_frame(){

		$this->_theme = 'frame.tpl';

		self::addJsFile('tplmod.js', 'acp_tplmods');
		self::addCssFile('tplmod.css', 'acp_tplmods');

		return true;
	}

	public static function registerHooks(){
		return true;
	}
}