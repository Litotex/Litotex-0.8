<?php
class tplModSort extends Basis_Entry {

	protected $_sTableName = 'lttx_tplmodificationsort';
	protected static $_sClassName = 'tplModSort';

	/**
	 * Get a List of all Entrys
	 * @return self
	 */
	public static function getList($sPosition){

		$oTemp = new tplModSort(0);

		$sSql = " SELECT * FROM `".$oTemp->_sTableName."` WHERE `position` = ? ";
		$aSql = array($sPosition);

		$aResult = package::$db->GetAssoc($sSql, $aSql, true);

		$aBack = array();
		if(!empty($aResult)){
			foreach((array)$aResult as $aData){
				$aBack[] = new tplModSort($aData['ID']);
			}
		}

		return $aBack;
	}

	public static function searchId($sClass, $sFuntion){

		$oTemp = new tplModSort(0);
		
		$sSql = " SELECT
						`ID`
					FROM
						`".$oTemp->_sTableName."`
					WHERE
						`class` = ? AND
						`function` = ? AND
						`active` = ?";
		$aSql = array($sClass, $sFuntion, 1 );

		$iID = package::$db->GetOne($sSql, $aSql, true);

		return $iID;

	}
	
}