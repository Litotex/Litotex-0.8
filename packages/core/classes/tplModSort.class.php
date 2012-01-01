<?php
class tplModSort extends Basis_Entry {

	protected $_sTableName = 'lttx1_tpl_modification_sort';
	protected static $_sClassName = 'tplModSort';

	/**
	 * Get a List of all Entrys
	 * @return self
	 */
	public static function getList($sPosition){

		$oTemp = new tplModSort(0);

		$sSql = " SELECT * FROM `".$oTemp->_sTableName."` WHERE `position` = ? ";
		$aSql = array($sPosition);

		$aResult = package::$db->prepare($sSql);
		$aResult->execute($aSql);
		$aResult = $aResult->fetch(PDO::FETCH_ASSOC);

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

		$iID = package::$db->prepare($sSql);
		$iID->execute($aSql);
		$iID = $iID->fetch();
		$iID = $iID[0];

		return $iID;

	}
	
}