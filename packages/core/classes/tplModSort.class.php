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

		$aResult = package::$pdb->prepare($sSql);
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

		$iID = package::$pdb->prepare($sSql);
		$iID->execute($aSql);
		$iID = $iID->fetch();
		$iID = $iID[0];

		return $iID;

	}
	
}