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
/**
 * This is a sample package which displays a little message in
 * the left and the right sidebar
 *
 * @author: Martin Lantzsch <martin@linux-doku.de>
 * @copyright: 2010
 */
class package_sample3 extends Package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'sample3';

    /**
     * Default template
     * @var string
     */
    protected $_theme = 'table.tpl';

    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array('main');

    /**
     * Register all hooks of this package
     * @return bool
     */
    
    /*
     * This is a simple example for dependency loading... check __action_main for further examples
     */
    public static $dependency = array('sample1');
	public static $loadDependency = array('sample2');
	/*
	 * Dependencies are loaded and (at some points) initialized, ($loadDependency is initilized)
	 */
	
    public static function registerHooks(){
		self::_registerHook(__CLASS__, 'sample3', 0);
        return true;
    }
   public static function registerTplModifications(){
    	self::_registerTplModification(__CLASS__, 'sample3', 'sample3');
    	return true;
    }

	
    /**
     * Main action displays a table in content area
     */
    public function __action_main() {
    	$testData = package_sample1::displayDepExample();
    	$testData .= $this->_dep['sample2']->displayDepExample();
    	self::$tpl->assign('testData', $testData);
    	$this->_theme = 'main.tpl';
        return true;
    }
    public static function __hook_sample3() {
		Package::$tpl->display(self::getTplDir('sample3') . 'table.tpl');
		return true;
    }

 	public static function  __tpl_sample3() {
        return self::__hook_sample3(2);
    }	
	
}
