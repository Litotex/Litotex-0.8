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
 * @package navigation

 */
class package_navigation extends Package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'navigation';
    /**
     * Avaibilbe actions in this package
     * @var array
     */
    
    protected $_theme = 'main.tpl';
    
	protected $_availableActions = array();

	
    public function __action_main() {
		return true;
    }
	
    public static function registerTplModifications(){
	    
    self::_registerTplModification(__CLASS__, 'displayTopNavigation', 'navigation');
	self::_registerTplModification(__CLASS__, 'displaySubNavigation', 'navigation');
		return true;
    }
    public static function __tpl_displayTopNavigation(){
		
		if(!isset($_GET['menu'])) 
			$menu_item=1;
		else
			$menu_item=intval($_GET['menu']);
		
		$elements = array();
        $data = self::$pdb->query("SELECT `ID`, `title`, `description`, `icon`, `package`, `action` FROM `lttx1_navigation` WHERE `parent` IS NULL ORDER BY `sort` ASC");
        foreach($data as $element) {
			if(!isset($_GET['package'])) $_GET['package'] = 'main';
			if($element[0] == $menu_item)
				$element['active'] = true;
			else
				$element['active'] = false;
		
			$package_name=$element['package'];
			if ($package_name =="") $package_name="main"; 
                        //Check of external link
                        if (strpos($package_name, 'http') !== false)
                            $element['link'] = $package_name;
			else
                            $element['link'] = "index.php?package=".$package_name."&menu=".$element['ID'];
                            
			$elements[] = $element;
        }
		
		 self::$tpl->assign('navigationItems', $elements);
		 self::$tpl->display(self::getTplDir('navigation') . 'topNavigation.tpl');
	}
	public static function __tpl_displaySubNavigation(){

		if(!isset($_GET['menu'])) 
			$menu_item=1;
		else
			$menu_item=intval($_GET['menu']);

	
		if(!isset($_GET['submenu'])) 
			$submenu_item=1;
		else
			$submenu_item=intval($_GET['submenu']);
		
		$elements = array();
        $data = self::$pdb->prepare("SELECT `ID`, `title`, `description`, `icon`, `package`, `action` FROM `lttx1_navigation` WHERE `parent` =? ORDER BY `sort` ASC");
        $data->execute(array($menu_item));
        foreach($data as $element) {
			if(!isset($_GET['package'])) $_GET['package'] = 'main';
			if($element[0] == $menu_item)
				$element['active'] = true;
			else
				$element['active'] = false;
		
			$package_name=$element['package'];
			$action_name=$element['action'];
			if ($package_name =="") $package_name="main"; 
			if ($action_name =="") $action_name="main"; 
			
                        //Check of external link
                        if (preg_match('/^([a-z]*\:\/\/.*)/', $package_name))
                            $element['link'] = $package_name;
			else
                            $element['link'] = "index.php?package=".$package_name."&menu=".$menu_item."&submenu=".$element['ID']."&action=".$action_name;
			
                        if($element[0] == $submenu_item)
				$element['active'] = true;
			else
				$element['active'] = false;
			
			$elements[] = $element;
        }
		
		 self::$tpl->assign('navigationItems', $elements);

		 self::$tpl->display(self::getTplDir('navigation') . 'subNavigation.tpl');
	}
	
	
	
}
?>
