<?php
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

    public static function registerHooks() {
        return true;
    }

    public function __action_main() {
        return true;
    }

    public static function generateCacheFile($platform) {
        // check if cache dir exists
        if(!file_exists('files/packages/cache/'))
            mkdir('files/packages/cache/', 0777, true);
        // get all packages for this platform, but ONLY the newest release
        $xml = array();
        $result = self::$db->Execute('SELECT id, name, description FROM lttx1_projects');
        while(!$result->EOF) {
            // get latest release
            $release = self::$db->Execute('SELECT version, time, changelog FROM lttx1_projects_releases
                                           WHERE
                                            projectID = ? AND
                                            platform = ?
                                           ORDER by time DESC
                                           LIMIT 1',
                    array(
                    $result->fields[0],
                    $platform)
            );

            $name = $result->fields[1];

            $xml[$name]['name']        = $result->fields[1];
            $xml[$name]['description'] = $result->fields[2];
            $xml[$name]['version']     = $release->fields[0];
            $xml[$name]['time']        = date('d.m.y', $release->fields[1]);
            $xml[$name]['changelog']   = $release->fields[2];
            $result->MoveNext();
        }

        // make a xml of this array
        ob_start();
        echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        echo '<packages>'."\n";

        foreach($xml as $name => $value) {
            echo '    <package name="'.$name.'">'."\n";
            foreach($value as $subName => $subValue) {
                echo '        <'.$subName.'>'.$subValue.'</'.$subName.'>'."\n";
            }
            echo '    </package>'."\n";
        }
        echo '</packages>';

        $xml = ob_get_clean();

        return @file_put_contents('files/packages/cache/'.$platform.'.xml', $xml);
    }

    public function __action_getList() {
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