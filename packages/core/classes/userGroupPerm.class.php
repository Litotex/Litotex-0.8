<?php
class userGroupPerm extends perm {

	protected $_oGroup;

 	/**
	 * This will set up permission handlich for a userGroup
	 * @param userGroup $oGroup
	 * @return void
	 */
	public function  __construct($oGroup) {

		if(!is_a($oGroup, 'userGroup')) {
			throw new Exception('UserGroup class has to be passed');
			return;
		}

		$this->_iAssociateID	= $oGroup->getID();
		$this->_iAssociateType	= 2;
		
		$this->_oGroup = $oGroup;
	}
}