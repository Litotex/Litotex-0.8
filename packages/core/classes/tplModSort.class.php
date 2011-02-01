<?php
class tplModSort extends Basis_Entry {

	protected $_sTableName = 'lttx_tplmodificationsort';
	protected static $_sClassName = 'tplModSort';

	/**
	 * Get a List of all Entrys
	 * @return self
	 */
	public static function getList(){
		return parent::getList(self::$_sClassName);
	}
	
}