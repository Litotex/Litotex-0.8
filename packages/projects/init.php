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
 * Provides front end package management features
 *
 * @author:     Martin Lantzsch <martin@linux-doku.de>
 * @copyright:  Copyright 2010 Litotex Team
 */
class package_projects extends package {
    protected $_availableActions = array('main', 'getList');
    protected $_packageName = 'projects';
    protected $_theme = 'main.tpl';
    
    protected static $_packageCache = array();

    public static function registerHooks() {
        return true;
    }

    public function __action_main() {
        return true;
    }
	
    protected static function _addCert($packageName, $releaseID, $fullReview, $certComment = ''){
    	if(!isset(self::$_packageCache[$packageName]['releases'][$releaseID]))
    		return false;
    	if(!isset(self::$_packageCache[$packageName]['releases'][$releaseID]['certs']))
    		self::$_packageCache[$packageName]['releases'][$releaseID]['certs'] = array();
    	self::$_packageCache[$packageName]['releases'][$releaseID]['certs'][] = array($fullReview, $certComment);
    	return true;
    }
    
	protected static function _addRelease($packageName, $releaseID, $data){
    	if(!isset(self::$_packageCache[$packageName]))
    		return false;
    	if(!isset(self::$_packageCache[$packageName]['releases']))
    		self::$_packageCache[$packageName]['releases'] = array();
    	self::$_packageCache[$packageName]['releases'][$releaseID] = array($data['version'], $data['time'], $data['changelog'], $data['critupdate']);
    	return true;
    }
    
    protected static function _addPackage($packageName, $data){
    	try{
    		self::$_packageCache[$packageName] = array($data['description'], new user($data['owner']), unserialize($data['dependencies']));
    	} catch(lttxFatalError $e){
    		//The user does not exist, we can't show this package!
    	}
    }
    
    public static function generateCacheFile($platform) {
    	$xmlFile = new SimpleXMLElement('<litotex origin="updateServer" version="undefined" responsetype="packageList"/>');
    	$dataArea = $xmlFile->addChild('data');
    	$packageData = self::$db->Execute("SELECT
    		`lttx_projects`.`ID` AS `projectID`,
    		`lttx_projects`.`name`,
    		`lttx_projects`.`description`,
    		`lttx_projects`.`owner`,
    		`lttx_projects`.`creationTime`,
    		`lttx_projectReleases`.`ID` AS `releaseID`,
    		`lttx_projectReleases`.`uploader`,
    		`lttx_projectReleases`.`version`,
    		`lttx_projectReleases`.`platform`,
    		`lttx_projectReleases`.`dependencies`,
    		`lttx_projectReleases`.`changelog`,
    		`lttx_projectReleases`.`critupdate`,
    		`lttx_projectReleases`.`time`,
    		`lttx_projectReleases`.`file`,
    		`lttx_projectReleases`.`downloads`,
    		`lttx_projectSignes`.`ID` AS `certID`,
    		`lttx_projectSignes`.`comment` AS `certComment`,
    		`lttx_projectSignes`.`fullReview`
    		FROM `lttx_projects`
    		LEFT JOIN `lttx_projectReleases`
	    	ON `lttx_projects`.`id` = `lttx_projectReleases`.`projectID`
	    	LEFT JOIN `lttx_projectSignes`
	    	ON `lttx_projectReleases`.`ID` = `lttx_projectSignes`.`releaseID`
	    	WHERE `lttx_projectReleases`.`platform` = ?
	    	ORDER BY  `lttx1_projectReleases`.`version` DESC", array($platform));
    	self::$_packageCache = array();
    	while(!$packageData->EOF){
    		if(isset(self::$_packageCache[$packageData->fields['name']])){
    			if(isset(self::$_packageCache[$packageData->fields['name']]['releases'][$packageData->fields['releaseID']])){
    				self::_addCert($packageData->fields['name'], $packageData->fields['releaseID'], $packageData->fields['fullReview'], $packageData->fields['certComment']);
    			} else {
    				self::_addRelease($packageData->fields['name'], $packageData->fields['releaseID'], $packageData->fields);
    				self::_addCert($packageData->fields['name'], $packageData->fields['releaseID'], $packageData->fields['fullReview'], $packageData->fields['certComment']);
    			}
    		} else {
    			self::_addPackage($packageData->fields['name'], $packageData->fields);
    			self::_addRelease($packageData->fields['name'], $packageData->fields['releaseID'], $packageData->fields);
    			self::_addCert($packageData->fields['name'], $packageData->fields['releaseID'], $packageData->fields['fullReview'], $packageData->fields['certComment']);
    		}
    		$packageData->MoveNext();
    	}
    	foreach(self::$_packageCache as $name => $data){
    		$package = $dataArea->addChild('package');
    		$package->addAttribute('name', $name);
    		$releaseIDs = array_keys($data['releases']);
    		$package->addAttribute('version', $data['releases'][$releaseIDs[0]][0]);
    		$package->addAttribute('description', $data[0]);
    		if(is_array($data[2])){
    			foreach ($data[2] as $item){
    				$depElement = $package->addChild('dependency');
    				$depElement->addAttribute('name', $item[0]);
    				$depElement->addAttribute('minVersion', $item[1]);
    			}
    		}
    		foreach($data['releases'] as $release){
    			$changeLogElement = $package->addChild('changelog');
    			$changeLogElement->addAttribute('text', $release[2]);
    			$changeLogElement->addAttribute('date', $release[1]);
    			$changeLogElement->addAttribute('crit', $release[3]);
    			foreach ($release['certs'] as $certs){
    				if($certs[0] == NULL)
    					continue;
    				$signElement = $package->addChild('signed');
    				$signElement->addAttribute('version', $release[0]);
    				$signElement->addAttribute('completeReview', $certs[0]);
    				$signElement->addAttribute('comment', $certs[1]);
    			}
    		}
    		$authorElement = $package->addChild('author');
    		$data[1]->setLocalBufferPolicy(false);
	    	$authorElement->addAttribute('name', $data[1]->getUsername());
	    	$authorElement->addAttribute('mail', $data[1]->getData('email'));
    	}
    	echo $xmlFile->asXML();
    }

    public function __action_getList() {
    	self::generateCacheFile('0.8.x');
    	die();
        $this->_theme = 'getList';
        $availiblePlatforms = array('0.8.0', '0.8.x'); // @TODO: Add Platform to an option page (atm its hardcoded!)

        // get requested platform
        $platform = $_GET['platform'];
        $platform = str_replace('../', '', $platform);

        // is platform valid
        if(!in_array($platform, $availiblePlatforms)) {
            echo "ERROR: Invalid Platform";
            die();
        }

        // check cache
        $cacheFile = 'files/packages/cache/'.$platform.'.xml';
        $lifetime = 3600; // one hour

        if(@filectime($cacheFile) < time() - $lifetime || !file_exists($cacheFile)) {
            // create new cache
            self::generateCacheFile($platform);
        }
        // read cache file
        header('content-type: text/xml');
        echo file_get_contents($cacheFile);

        die();
        return true;
    }
}