<?php
/**
 * Well... This class will handle the whole navigation in acp.
 * We can't use any actions here... As it will lead to an endless recursion
 * Only hooks and tplMods
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 */
class package_acp_navigation extends acpPackage{
	protected $_availableActions = array();
	protected $_packageName = 'acp_navigation';
	public function __action_main(){
		return false;
	}
	public static function registerHooks(){
		self::_registerHook(__CLASS__, 'addAcpNavigationNode', 4);
		self::_registerHook(__CLASS__, 'removeAcpNavigationNode', 2);
		return true;
	}
	public static function registerTplModifications(){
		self::_registerTplModification(__CLASS__, 'displayAcpTopNavigation', 'acp_navigation');
                self::_registerTplModification(__CLASS__, 'displayAcpSubNavigation', 'acp_navigation');
		return true;
	}
	public static function __tpl_displayAcpTopNavigation(){
            package::addJsFile('jquery.effects.core.min.js');
            $elements = array();
            $data = self::$db->Execute("SELECT `ID`, `title`, `description`, `icon`, `package`, `action` FROM `lttx_acpNavigation` WHERE `parent` IS NULL ORDER BY `sort` ASC");
            while(!$data->EOF){
                $elements[] = $data->fields;
                $data->MoveNext();
            }
            self::$tpl->assign('navigationItems', $elements);
            self::$tpl->display(self::getTplDir('acp_navigation') . 'topNavigation.tpl');
	}
        public static function __tpl_displayAcpSubNavigation(){
            $data = self::$db->Execute("SELECT `ID`, `parent`, `title`, `description`, `icon`, `package`, `action`, `tab` FROM `lttx_acpNavigation` WHERE `parent` IS NOT NULL ORDER BY `sort` ASC");
            $elements = array();
            while(!$data->EOF){
                $sub = self::$db->Execute("SELECT `ID`, `parent`, `title`, `description`, `icon`, `package`, `action`, `tab` FROM `lttx_acpNavigation` WHERE `parent` = ? ORDER BY `sort` ASC", array($data->fields['ID']));
                if($sub->NumRows() <= 0){
                    $data->MoveNext();
                        continue(1);
                }
                if(!isset($elements[$data->fields['parent']]))
                        $elements[$data->fields['parent']] = array();
                $subElements = array();
                while(!$sub->EOF){
                    $subElements[] = $sub->fields;
                    $sub->MoveNext();
                }
                $data->fields['sub'] = $subElements;
                $elements[$data->fields['parent']][] = $data->fields;
                $data->MoveNext();
            }
            self::$tpl->assign('navigationItems', $elements);
            self::$tpl->display(self::getTplDir('acp_navigation') . 'subNavigation.tpl');
	}
	public static function __hook_addAcpNavigationNode(){
		
	}
	public static function __hook_removeAcpNavigationNode(){
		
	}
}