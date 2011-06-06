<?php
/**
 * @package screenshots

 */
class package_screenshots extends package {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'screenshots';
	
    /**
     * Default template
     * @var string
     */
    protected $_theme = 'main.tpl';

    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array('main','frontend','acp');

    /**
     * Main action displays a table in content area
     */
    public function __action_main() {
		if(!isset($_GET['type'])) 
			$screenshot_type=1;
		else
			$screenshot_type=intval($_GET['type']);
		
		if ($screenshot_type==1)
			return self::__action_frontend();
		else
			return self::__action_acp();
		
		
		
        return true;
    }
	private function _scan_dir($type){
		
		if ($type==1)
			$mydir = self::getTplDir('screenshots')."img_frontend";
		else
			$mydir = self::getTplDir('screenshots')."img_acp";
		
		$folder = dir($mydir);
		while($entry=$folder->read()){
			if($entry != "." && $entry != ".."){
				$dateinamen[] = $entry;
			}
		}
		$folder->close();
		return $dateinamen ;
		
		 
	}
	
	public function __action_frontend() {
		$images = $this->_scan_dir(1);
		
		if (empty($images))
			return true;
		rsort($images);
		while(list($key, $val) = each($images)) {
			if(substr($val, -4) == ".jpg" || substr($val, -4) == ".png" )
			{
				echo "<A HREF=\"pics/".$val."\" TARGET=\"_blank\"><IMG SRC=\"".$val . "\"><BR>".$val."</A><BR><BR>";
			}
		}
		
		self::$tpl->display(self::getTplDir('screenshots') . 'frontend.tpl');
		
	}
	public function __action_acp() {
		self::$tpl->display(self::getTplDir('screenshots') . 'acp.tpl');
		
	}

	
}
