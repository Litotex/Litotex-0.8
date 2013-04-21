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

class UserPermission extends Permission {

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
			
			$oGroupPerm = new UserGroupPermission($oGroup);
			$iGroupPerm = $oGroupPerm->checkPerm($mPackage, $sFunction, $sClass);
			
			$iPerm = $this->_mergePerm($iPerm, (int)$iGroupPerm);
			
			if($iPerm == -1) {
				return false;
			}
		}
		
		if($iPerm == 1){
			return true;
		}
		
		return false;
	}

}
