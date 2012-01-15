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
/**
 * ACP package to manage projects and releases
*
* @author:     Martin Lantzsch <martin@linux-doku.de>
* @licence:	Copyright 2010 Litotex Team
*/
class package_acp_projects extends acpPackage {
	protected $_availableActions = array('main', 'createProject', 'createProjectSave', 'editProject', 'editProjectSave', 'deleteProject', 'deleteProjectNotSure', 'uploadRelease', 'uploadReleaseSave', 'deleteReleaseNotSure', 'deleteRelease');
	protected $_packageName = 'acp_projects';
	protected $_theme = 'main.tpl';

	public static function registerHooks() {
		return true;
	}

	/**
	 * validate the given version number
	 *
	 * @param	string	$version
	 * @return	bool
	 */
	public static function validateVersionNumber($version) {
		return preg_match('/^[0-9].[0-9].[0-9]/', $version);
	}

	public static function validatePlatformNumber($platform) {
		$platforms = array('0.8.x', '0.8.0'); // @TODO: Add Platform to an option page (atm its hardcoded!)
		return in_array($platform, $platforms);
	}

	/**
	 * Statistics about uploaded packages, downloads, etc.
	 */
	public function __action_main() {
		// count projects
		$result = Package::$pdb->query('SELECT id FROM lttx1_projects');
		$projectCount = $result->rowCount();

		// count releases
		$result = Package::$pdb->query('SELECT id FROM lttx1_projects_releases');
		$releaseCount = $result->rowCount();

		// assign new vars to template
		self::$tpl->assign('projectCount', $projectCount);
		self::$tpl->assign('releaseCount', $releaseCount);

		// get from db
		$result = self::$pdb->query('SELECT id, name, owner, downloads FROM lttx1_projects');

		$i = 0;
		$var = array();
		foreach($result as $item) {
			// get username
			try{
				$user = new User($item[2]);
				$username = $user->getData('username');
				unset($user);
			} catch(Exception $e){
				$username = "---";
			}

			// put all to tpl var
			$var[$i]['id'] = $item[0];
			$var[$i]['name'] = $item[1];
			$var[$i]['owner'] = $username;
			$var[$i]['downloads'] = $item[3];
			$i++;
		}

		self::$tpl->assign('projects', $var);

		return true;
	}

	/**
	 * Create a new project form
	 */
	public function __action_createProject() {
		$this->_theme = 'createProject.tpl';

		return true;
	}

	/**
	 * Save the new project form
	 */
	public function __action_createProjectSave() {
		// get form data
		$name        = $_POST['name'];
		$owner       = $_POST['owner'];
		$description = $_POST['description'];

		// check if all fields are filled
		if(!$name || !$owner || !$description)
		throw new LitotexError('formNotComplete');

		// write to db
		self::$pdb->prepare('INSERT INTO lttx1_projects
                            SET
                                name = ?,
                                owner = ?,
                                description = ?,
                                creationTime = ?')->execute(array($name,User::getUserByName($owner)->getData('id'),$description,time()));

		header('Location: index.php?package=acp_projects');

		return true;
	}

	/**
	 * Display a edit form for existing packages
	 */
	public function __action_editProject() {
		$this->_theme = 'editProject.tpl';

		if(!$_GET['projectID'])
		throw new LitotexError('noProjectIdGiven');
		else
		$projectID = $_GET['projectID'];

		// get project info
		$project = self::$pdb->prepare('SELECT name, description, owner FROM lttx1_projects WHERE id = ?');
		$project->execute(array($projectID));

		$project = $project->fetch();
		try {
			$user = new User($project[2]);
			$owner = $user->getData('username');
			unset($user);
		}catch (Exception $e){
			$owner = '---';
		}

		self::$tpl->assign('projectID', $projectID);
		self::$tpl->assign('projectName', $project[0]);
		self::$tpl->assign('projectDescription', $project[1]);
		self::$tpl->assign('projectOwner', $owner);

		// get all project releases
		$result = self::$pdb->prepare('SELECT uploader, version, platform, changelog, downloads, time, id FROM lttx1_projects_releases WHERE projectID = ?');
		$result->execute(array($projectID));

		$i = 0;
		foreach($result as $item) {
			// get username
			try{
				$user = new User($item[0]);
				$uploader = $user->getData('username');
				unset($user);
			}catch (Exception $e){
				$uploader = '---';
			}

			// put all to tpl var
			$var[$i]['uploader'] = $uploader;
			$var[$i]['version'] = $item[1];
			$var[$i]['platform'] = $item[2];
			$var[$i]['changelog'] = $item[3];
			$vra[$i]['downloads'] = $item[4];
			$var[$i]['time'] = date('d.m.y H:i', $item[5]);
			$var[$i]['id'] = $item[6];
			$i++;
		}

		self::$tpl->assign('releases', @$var);

		return true;
	}

	/**
	 * Save the edit form
	 */
	public function __action_editProjectSave() {
		// get submitted form values
		$projectID   = $_GET['projectID'];
		$name        = $_POST['name'];
		$owner       = $_POST['owner'];
		$description = $_POST['description'];

		// check if all fields are filled
		if(!$name || !$owner || !$description)
		throw new LitotexError('formNotComplete');

		// write to db
		self::$pdb->prepare('UPDATE lttx1_projects
                            SET
                                name = ?,
                                owner = ?,
                                description = ?
                            WHERE `id` = ?')
		->execute(array(
		$name,
		User::getUserByName($owner)->getData('id'),
		$description,
		$projectID
		));

		header('Location: index.php?package=acp_projects&action=editProject&projectID='.$projectID);

		return true;
	}

	/**
	 * Ask if the user is sure
	 *
	 * @return bool
	 */
	public function __action_deleteProjectNotSure() {
		$this->_theme = 'deleteProjectNotSure.tpl';

		if(!$_GET['projectID'])
		throw new LitotexError('noProjectIdGiven');

		self::$tpl->assign('projectID', $_GET['projectID']);

		return true;
	}

	/**
	 * Delete an existing project
	 */
	public function __action_deleteProject() {
		if(!$_GET['projectID'])
		throw new LitotexError('noProjectIdGiven');
		else
		$projectID = $_GET['projectID'];

		self::$pdb->prepare('DELETE FROM lttx1_projects WHERE id = ?')->execute(array($projectID));
		header('Location: index.php?package=acp_projects&action=projects');
	}

	/**
	 * Display a upload form for new packages
	 */
	public function __action_uploadRelease() {
		// set template
		$this->_theme = 'uploadRelease.tpl';

		if(!$_GET['projectID'])
		throw new LitotexError('noProjectIdGiven');

		self::$tpl->assign('projectID', $_GET['projectID']);

		return true;
	}

	/**
	 * Upload a new release package to server
	 */
	public function __action_uploadReleaseSave() {
		// get submited form values
		$projectID  = $_GET['projectID'];
		$version    = $_POST['version'];
		$platform   = $_POST['platform'];
		$changelog  = $_POST['changelog'];
		$package    = $_FILES['package'];

		// check if alle fields are filled
		if(!$projectID || !$version || !$platform || !$changelog || !$package)
		throw new LitotexError('formNotComplete');

		// check if version numbers are corrent
		if(!self::validateVersionNumber($version))
		throw new LitotexError('invalidVersionNumber');

		// set package dir
		$dir = 'files/packages/'.$projectID.'/'.$platform.'/';

		// check if dir exists
		if(!file_exists('../'.$dir))
		mkdir('../'.$dir, 0777, true);

		// compare dir and filename
		$filename = $dir.$version.'.zip';

		// save file
		if(!file_exists('../'.$filename))
		move_uploaded_file($package['tmp_name'], '../'.$filename);

		// write to db
		self::$pdb->prepare('INSERT INTO lttx1_projects_releases
                            SET
                                projectID = ?,
                                uploader  = ?,
                                version   = ?,
                                platform  = ?,
                                changelog = ?,
                                file      = ?,
                                downloads = 0,
                                time = ?')
		->execute(array(
		$projectID,
		Package::$user->getData('id'),
		$version,
		$platform,
		$changelog,
		$filename,
		time()
		));

		if(!self::validateVersionNumber($version) || !self::validatePlatformNumber($platform))
		throw new LitotexError('versionNumberIsInvalid');

		header('Location: index.php?package=acp_projects&action=editProject&projectID='.$projectID);

		return true;
	}

	/**
	 * Ask if the user is sure
	 *
	 * @return bool
	 */
	public function __action_deleteReleaseNotSure() {
		$this->_theme = 'deleteReleaseNotSure.tpl';

		if(!$_GET['releaseID'])
		throw new LitotexError('noReleaseIdGiven');

		self::$tpl->assign('releaseID', $_GET['releaseID']);
		self::$tpl->assign('projectID', $_GET['projectID']);

		return true;
	}

	/**
	 * Delete an release
	 */
	public function __action_deleteRelease() {
		if(!$_GET['releaseID'])
		throw new LitotexError('noReleaseIdGiven');
		else
		$releaseID = $_GET['releaseID'];

		Package::$pdb->prepare('DELETE FROM lttx1_projects_releases WHERE id = ?')->execute(array($releaseID));
		header('Location: index.php?package=acp_projects&action=editProject&projectID='.$_GET['projectID']);
	}
}