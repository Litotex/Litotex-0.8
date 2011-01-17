<?php
/*
 * This file is part of Litotex | Open Source Browsergame Engine.
 *
 * Litotex is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Litotex is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Litotex.  If not, see <http://www.gnu.org/licenses/>.
 */
class userPerm extends perm {

	/**
	 * User object
	 * @var user
	 */
	protected $_oUser = false;

	/**
	 * This will hold all userGroups in an Array
	 * @var array
	 */
	protected $_aGroups = array();

	/**
	 * true if user flag serveradmin is set in database, just ignore all limitations
	 * @var bool
	 */
	protected $_bServerAdmin = false;

	/**
	 * This will set up permission handlich for a user
	 * @param user $oUser
	 * @return void
	 */
	public function  __construct($oUser) {

		if(!is_a($oUser, 'user')) {
			throw new Exception('User class has to be passed');
			return;
		}

		$this->_bServerAdmin = (bool)$oUser->getData('serverAdmin');

		$this->_oUser = $oUser;
		$this->_aGroups = (array)$oUser->getUserGroups();

		$this->_iAssociateID = $oUser->getData('ID');
		$this->_iAssociateType = 1;

		return;
	}

	/**
	 * This will return true, if there are enough permissions for this user
	 * @param package (extended) $mPackage name of the package this function belongs to
	 * @param str $sFunction name of the function to be checked
	 * @param str $sClass Name of class which is the "owner" of the static function $sFunction, false will check the function on package object
	 * @return bool
	 */
	public function checkPerm($mPackage, $sFunction, $sClass = false) {

		if($this->_bServerAdmin) {
			return true;
		}

		$iPerm = parent::checkPerm($mPackage, $sFunction, $sClass);

		foreach((array)$this->_aGroups as $oGroup) {

			if(!is_a($oGroup, 'userGroup')){
				return false;
			}

			$oGroupPerm = new userGroupPerm($oGroup);
			$iGroupPerm = $oGroupPerm->checkPerm($mPackage, $sFunction, $sClass);

			$iPerm = $this->_mergePerm($iPerm, (int)$iGroupPerm);

			if($iPerm == -1){
				return false;
			}

		}

		if($iPerm == 1){
			return true;
		}

		return false;
	}

}