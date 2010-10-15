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
		self::_registerTplModification(__CLASS__, 'displayAcpNavigation', 'acp_navigation');
		return true;
	}
	public static function __tpl_displayAcpNavigation(){
		$nodeArray = array();
		$unsortedNodes = array();
		$nodes = package::$db->Execute("SELECT `ID`, `parent`, `title`, `package`, `action` FROM `lttx_acpNavigation` ORDER BY `sort` DESC");
		if(!$nodes)
			throw new lttxFatalError('ACP navigation could not be fetched!');
		while(!$nodes->EOF){
			$row = $nodes->fields;
			$nodes->MoveNext();
			if(!package::$perm->checkPerm($row[3], $row[4]))
				continue;
			if($row[1] && !isset($nodeArray[$row[1]])){
				$unsortedNodes[] = $row;
				continue;
			} else if($row[1]){
				$nodeArray[$row[1]]['children'][$row[0]] = $row;
			} else {
				$nodeArray[$row[0]] = $row;
				$nodeArray[$row[0]]['children'] = array();
			}
		}
		if(count($unsortedNodes) > 0)
			$nodeArray[] = array(0, NULL, 'UNSORTED', NULL, NULL, 'ID' => 0, 'parent' => NULL, 'title' => 'UNSORTED', 'package' => NULL, 'action' => NULL, 'children' => $unsortedNodes);
		package::$tpl->assign('acpNavigationNodes', $nodeArray);
		package::$tpl->display(self::getTplDir('acp_navigation') . 'navigation.tpl');
	}
	public static function __hook_addAcpNavigationNode(){
		
	}
	public static function __hook_removeAcpNavigationNode(){
		
	}
}