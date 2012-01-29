<?php
class TemplateModSort{
	public static function getList($position){
		$return = array();
		$result = Package::$pdb->prepare("SELECT * FROM `lttx1_tpl_modification_sort` WHERE `position` = ?");
		$result->execute(array($position));

		foreach($result as $item){
			$return[] = new TemplateModSort($item['ID']);
		}
		return $return;
	}

	public static function searchId($class, $funtion){
		$result = Package::$pdb->prepare("SELECT `ID` FROM `lttx1_tpl_modification_sort` WHERE `class` = ? AND `function` = ? AND `active` = ?");
		$result->execute(array($class, $funtion, 1));

		if($result->rowCount() < 1){
			return false;
		}
		$result = $result->fetch();
		return $result[0];
	}
}