<?php
/**
 * This class offers the ability to do jobs in realtime as it allows to block other jobs untill
 * defined jobs are done etc.
 * It is highly recommended to use a cron job or use a progamm on the server to do the jobs as it would take
 * a lot too long if there are a lot of active users
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 * @todo: Almost everything :) So move on... nothing to see here...
 */
class cron{
	private $_now;
	private static $_runtimeEdits = array();
	public function __construct(){

	}
	public function __destruct(){

	}
	public static function change($textID, $function, $params, $object = false){
		if(is_object($object)){
			$object = serialize($object);
		} else {
			$object = '';
		}
		if(!is_array($params))
		return false;
		$params = implode(';', $params);
		$result = package::$db->Execute("SELECT `ID` FROM `lttx_cron` WHERE `textID` = ?", array($textID));
		package::$db->Execute("UPDATE `lttx_cron` SET `function` = ?, `params` = ?, `serialized` = ? WHERE `textID` = ?", array($function, $params, $object, $textID));
		if($result->RecordCount() == 0)
		return true;
		while(!$result->EOF){
			self::$_runtimeEdits[$result->fields[0]] = array('serialized' => $object, 'params' => explode(';', $params), 'function' => $function);
			$result->MoveNext();
		}
		return true;
	}
	public static function add($textID, $nextInt, $interval, $function, $params, $dependencies = array(), $user = false, $blockUser = false, $object = false){
		if(!is_array($dependencies))
		return false;
		$dependencies = implode(';', $dependencies);
		if(is_a($user, 'user')){
			$user = $user->getUserID();
		}
		if($user === false){
			$user = '';
		} else if(!is_int($user)){
			return false;
		}
		if(is_array($blockUser)){
			foreach($blockUser as $key => $block){
				if(is_a($block, 'user')){
					$block = $block->getUserID();
				}
				if($block === false){
					unset($blockUser[$key]);
				} else if(!is_int($block)){
					return false;
				} else {
					$blockUser[$key] = $block;
				}
			}
			$blockUser = implode(';', $blockUser);
		} else {
			$blockUser = '';
		}
		if(is_object($object)){
			$object = serialize($object);
		} else {
			$object = '';
		}
		if(!is_array($params))
		return false;
		$params = implode(';', $params);
		$nextInt *= 1;
		$interval *= 1;
		$result = package::$db->Execute("INSERT INTO `lttx_cron` (`textID`, `serialized`, `function`, `params`, `nextInt`, `interval`, `userID`, `blockUserID`, `dependencies`)
		VALUES
		(?, ?, ?, ?, ?, ?, ?, ?, ?)", array($textID, $object, $function, $params, $nextInt, $interval, $user, $blockUser, $dependencies));
		if(package::$db->Affected_Rows() == 1)
		return true;
		return false;
	}
	public static function remove($textID){
		$toDelete = package::$db->Execute("SELECT `ID` FROM `lttx_cron` WHERE `textID` = ?", array($textID));
		if($toDelete->RecordCount() == 0)
		return true;
		while(!$toDelete->EOF){
			self::$_runtimeEdits[$toDelete->fields[0]] = array('serialized' => '', 'function' => '', 'params' => array(), 'nextInt' => 0, 'interval' => 0, 'userID' => '', 'blockUserID' => array(), 'dependencies' => array(), 'ID' => $toDelete->fields[0]);
			$toDelete->MoveNext();
		}
		$result = package::$db->Execute("DELETE FROM `lttx_cron` WHERE `textID` = ?", array($textID));
		if(package::$db->Affected_Rows() == 0)
		return false;
		return true;
	}
	public function doActions($limit = false){
		$this->_now = time(); //We will use a variable to determine the current time because it might change over the running time, further we need to change it when we use limits
		$list = $this->_getActionList($limit);
		$blocks = self::_generateBlockIndex($list);
		$list = $this->_splitByBlocks($list, $blocks);
		package::$db->Execute("DELETE FROM `lttx_cron` WHERE `interval` = 0 AND `nextInt` <= ?", array($this->_now));
		$this->_upDateDB();
	}
	private function _upDateDB(){
		package::$db->Execute("UPDATE `lttx_cron` SET `nextInt` = `nextInt` + CEIL((?-`nextInt`)/`interval`+1) * `interval` WHERE `nextInt` <= ?", array($this->_now, $this->_now));
	}
	private function _doActions($actions){
		foreach($actions as $action){
			foreach($action['dependencies'] as $dep){
				if($dep == '')
				continue;
				if(!package::$packages->loadPackage($dep, false, false))
				return false;
			}
			if(isset(self::$_runtimeEdits[$action['ID']])){
				$edit = self::$_runtimeEdits[$action['ID']];
				if(isset($edit['serialized']) && isset($edit['function']) && $edit['serialized'] == '' && $edit['function'] == '')
					continue; //Maybe the element has been deleted during runtime, so just move ahead...
				if(isset($edit['serialized']))
					$action['serialized'] = $edit['serialized'];
				if(isset($edit['function']))
					$action['function'] = $edit['function'];
				if(isset($edit['params']))
					$action['params'] = $edit['params'];
			} //We are prepared very well... now let's do the job...
			$intervalReplace = array_keys($action['params'], '$interval');
			$intNumReplace = array_keys($action['params'], '$intNum');
			foreach($intervalReplace as $replace){
				$action['params'][$replace] = $action['interval'];
			}
			foreach($intNumReplace as $replace){
				$action['params'][$replace] = $action['intNum'];
			}
			if($action['serialized'] == ''){
				call_user_func($action['function'], $action['params']);
			}else{
				$unserialized = unserialize($action['serialized']);
				if(!$unserialized)
				return false;
				call_user_func(array($unserialized, $action['function']), $action['params']);
			}
		}
		return true;
	}
	private function _getActionList($limit){
		$list = array();
		if($limit){
			$data = package::$db->SelectLimit("SELECT `serialized`, `function`, `params`, `nextInt`, `interval`, `userID`, `blockUserID`, `dependencies`, `ID`, MAX(`nextInt`) FROM `lttx_cron` WHERE `nextInt` <= ?", $limit, -1, array($this->_now));
			if($data->RecordCount() == 1 && $data->fields[7] == 0)
			return 0;
			$this->_now = (int)$data->fields[9];
		} else {
			$data = package::$db->Execute("SELECT `serialized`, `function`, `params`, `nextInt`, `interval`, `userID`, `blockUserID`, `dependencies`, `ID` FROM `lttx_cron` WHERE `nextInt` <= ?", array($this->_now));
			if($data->RecordCount() == 0)
			return 0;
		}
		while(!$data->EOF){
			$list[] = array('serialized' => $data->fields[0], 'function' => $data->fields[1], 'params' => explode(';', $data->fields[2]), 'nextInt' => $data->fields[3], 'interval' => $data->fields[4], 'userID' => $data->fields[5], 'blockUserID' => explode(';', $data->fields[6]), 'dependencies' => explode(';', $data->fields[7]), 'ID' => $data->fields[8]);
			$data->MoveNext();
		}
		if(!$this->_sortActionList($list))
		return false;
		return $list;
	}
	private static function _sortActionList(&$list){
		return usort($list, array('self', '_generateSortID'));
	}
	private static function _generateSortID($item1, $item2){
		if($item1['nextInt'] < $item2['nextInt'])
		return -1;
		else if($item1['nextInt'] > $item2['nextInt'])
		return 1;
		else{
			if($item1['interval'] == 0){
				return -1;
			} else if($item2['interval'] == 0){
				return 1;
			}
			return 0;
		}
	}
	private static function _generateBlockIndex($list){
		$blocks = array();
		foreach($list as $item){
			if($item['interval'] == 0 && count($item['blockUserID']) > 0){
				foreach($item['blockUserID'] as $blockUsers){
					$blocks[$blockUsers][] = $item;
				}
			}
		}
		return $blocks;
	}
	private function _splitByBlocks($list, $block){
		$listRet = array();
		$addAfter = array();
		foreach($list as $item){
			$added = false;
			if($item['interval'] == 0){
				$listRet[] = array('serialized' => $item['serialized'], 'params' => $item['params'], 'function' => $item['function'], 'intNum' => 0, 'interval' => $item['interval'], 'dependencies' => $item['dependencies']);
				if(isset($addAfter[$item['ID']])){
					foreach ($addAfter[$item['ID']] as $add){
						$listRet[] = $add;
					}
				}
				continue;
			}
			$intAll = floor(($this->_now-$item['nextInt'])/$item['interval']);
			$intPreDone = 0;
			$i = 0;
			if($item['userID'] != 0 && isset($block[$item['userID']])){
				$lastBlockID = 0;
				foreach($block[$item['userID']] as $blocker){
					if(!$this->_inBlockTime($item['nextInt'], $item['interval'], $blocker['nextInt']))
					continue;
					$intThisRun = floor(($blocker['nextInt']-$item['nextInt'])/$item['interval']);
					$intPreDone += $intThisRun;
					$item['nextInt'] += $item['interval'] * $intThisRun;
					if($i == 0){
						$listRet[] = array('serialized' => $item['serialized'], 'params' => $item['params'], 'function' => $item['function'], 'intNum' => $intThisRun, 'interval' => $item['interval'], 'dependencies' => $item['dependencies']);
						$i = 1;
					}else{
						$addAfter[$lastBlockID][] = array('serialized' => $item['serialized'], 'params' => $item['params'], 'function' => $item['function'], 'intNum' => $intThisRun, 'interval' => $item['interval'], 'dependencies' => $item['dependencies']);
					}
					$lastBlockID = $blocker['ID'];
				}
				$addAfter[$blocker['ID']][] = array('serialized' => $item['serialized'], 'params' => $item['params'], 'function' => $item['function'], 'intNum' => $intAll - $intPreDone, 'interval' => $item['interval'], 'dependencies' => $item['dependencies']);
			} else {
				$listRet[] = array('serialized' => $item['serialized'], 'params' => $item['params'], 'function' => $item['function'], 'intNum' => $intAll - $intPreDone, 'interval' => $item['interval'], 'dependencies' => $item['dependencies']);
			}
		}
		return $listRet;
	}
	private function _inBlockTime($nextInt, $interval, $nextIntBlock){
		$i = $nextInt + ceil(($nextIntBlock-$nextInt)/$interval) * $interval; //This will generate the time the block would happen...
		if($i < $this->_now)
		return true;
		return false;
	}
}