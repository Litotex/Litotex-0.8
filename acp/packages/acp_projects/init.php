<?php
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
        $result = package::$db->Execute('SELECT id FROM lttx1_projects');
        $projectCount = $result->NumRows();

        // count releases
        $result = package::$db->Execute('SELECT id FROM lttx1_projects_releases');
        $releaseCount = $result->NumRows();

        // assign new vars to template
        self::$tpl->assign('projectCount', $projectCount);
        self::$tpl->assign('releaseCount', $releaseCount);

        // get from db
        $result = self::$db->Execute('SELECT id, name, owner, downloads FROM lttx1_projects');

        $i = 0;
        $var = array();
        while(!$result->EOF) {
            // get username
            try{
            	$user = new user($result->fields[2]);
            	$username = $user->getData('username');
            } catch(Exception $e){
            	$username = "---";
            }

            // put all to tpl var
            $var[$i]['id'] = $result->fields[0];
            $var[$i]['name'] = $result->fields[1];
            $var[$i]['owner'] = $username;
            $var[$i]['downloads'] = $result->fields[3];
            $result->MoveNext();
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
            throw new lttxError('formNotComplete');

        // write to db
        self::$db->Execute('INSERT INTO lttx1_projects
                            SET
                                name = ?,
                                owner = ?,
                                description = ?,
                                creationTime = ?',
                            array(
                                $name,
                                user::getUserByName($owner)->getData('id'),
                                $description,
                                time()
                            ));

        header('Location: index.php?package=acp_projects');
        
        return true;
    }

    /**
     * Display a edit form for existing packages
     */
    public function __action_editProject() {
        $this->_theme = 'editProject.tpl';

        if(!$_GET['projectID'])
            throw new lttxError('noProjectIdGiven');
        else
            $projectID = $_GET['projectID'];

        // get project info
        $project = self::$db->Execute('SELECT name, description, owner FROM lttx1_projects WHERE id = ?', array($projectID));

        $user = new user($project->fields[2]);
        $owner = $user->getData('username');
        unset($user);

        self::$tpl->assign('projectID', $projectID);
        self::$tpl->assign('projectName', $project->fields[0]);
        self::$tpl->assign('projectDescription', $project->fields[1]);
        self::$tpl->assign('projectOwner', $owner);

        // get all project releases
        $result = self::$db->Execute('SELECT uploader, version, platform, changelog, downloads, time, id FROM lttx1_projects_releases WHERE projectID = ?', array($projectID));

        $i = 0;
        while(!$result->EOF) {
            // get username
            $user = new user($result->fields[0]);
            $uploader = $user->getData('username');
            unset($user);

            // put all to tpl var
            $var[$i]['uploader'] = $uploader;
            $var[$i]['version'] = $result->fields[1];
            $var[$i]['platform'] = $result->fields[2];
            $var[$i]['changelog'] = $result->fields[3];
            $vra[$i]['downloads'] = $result->fields[4];
            $var[$i]['time'] = date('d.m.y H:i', $result->fields[5]);
            $var[$i]['id'] = $result->fields[6];
            $result->MoveNext();
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
            throw new lttxError('formNotComplete');

        // write to db
        self::$db->Execute('UPDATE lttx1_projects
                            SET
                                name = ?,
                                owner = ?,
                                description = ?',
                            array(
                                $name,
                                user::getUserByName($owner)->getData('id'),
                                $description
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
            throw new lttxError('noProjectIdGiven');

       self::$tpl->assign('projectID', $_GET['projectID']);

       return true;
    }

    /**
     * Delete an existing project
     */
    public function __action_deleteProject() {
        if(!$_GET['projectID'])
            throw new lttxError('noProjectIdGiven');
        else
            $projectID = $_GET['projectID'];
        
        self::$db->Execute('DELETE FROM lttx1_projects WHERE id = ?', array($projectID));
        header('Location: index.php?package=acp_projects&action=projects');
    }

    /**
     * Display a upload form for new packages
     */
    public function __action_uploadRelease() {
        // set template
        $this->_theme = 'uploadRelease.tpl';

        if(!$_GET['projectID'])
            throw new lttxError('noProjectIdGiven');

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
            throw new lttxError('formNotComplete');

        // check if version numbers are corrent
        if(!self::validateVersionNumber($version))
            throw new lttxError('invalidVersionNumber');

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
        self::$db->Execute('INSERT INTO lttx1_projects_releases
                            SET
                                projectID = ?,
                                uploader  = ?,
                                version   = ?,
                                platform  = ?,
                                changelog = ?,
                                file      = ?,
                                downloads = 0,
                                time = ?',
                            array(
                                $projectID,
                                package::$user->getData('id'),
                                $version,
                                $platform,
                                $changelog,
                                $filename,
                                time()
                            ));

        if(!self::validateVersionNumber($version) || !self::validatePlatformNumber($platform))
            throw new lttxError('versionNumberIsInvalid');

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
            throw new lttxError('noReleaseIdGiven');

       self::$tpl->assign('releaseID', $_GET['releaseID']);
       self::$tpl->assign('projectID', $_GET['projectID']);

       return true;
    }

    /**
     * Delete an release
     */
    public function __action_deleteRelease() {
        if(!$_GET['releaseID'])
            throw new lttxError('noReleaseIdGiven');
        else
            $releaseID = $_GET['releaseID'];

        package::$db->Execute('DELETE FROM lttx1_projects_releases WHERE id = ?', array($releaseID));
        header('Location: index.php?package=acp_projects&action=editProject&projectID='.$_GET['projectID']);
    }
}