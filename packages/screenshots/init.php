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
		self::addJsFile('jquery.lightbox-0.5.pack.js', 'screenshots');
		self::addCssFile('screenshots.css', 'screenshots');	
		
        return true;
    }
	private function _scan_dir($type){
		$filenames = array(); 
		if ($type==1){
			$mydir = self::getTplDir('screenshots')."img_frontend";
			$myURL = self::getTplURL('screenshots')."img_frontend";
		}else{
			$mydir = self::getTplDir('screenshots')."img_acp";
			$myURL = self::getTplURL('screenshots')."img_acp";
		}
		
		$folder = dir($mydir);
		while($entry=$folder->read()){
			if($entry != "." && $entry != ".."){
				if(substr($entry, -4) == ".jpg" || substr($entry, -4) == ".png" )
					if(substr($entry, 0,6) != "thumb_"){
						$thumb_image = $myURL."/thumb_".$entry;
						$normal_image= $myURL."/".$entry;
						$filenames[]=array('thumb'=>$thumb_image,'normal'=>$normal_image,'name'=>$entry);
					}
			}
		}
		$folder->close();
		return $filenames ;
		
		 
	}
	
	public function __action_frontend() {
		self::addJsFile('jquery.lightbox-0.5.pack.js', 'screenshots');
		self::addCssFile('screenshots.css', 'screenshots');	
		$images = $this->_scan_dir(1);
		if (empty($images))
			return true;

		self::$tpl->assign('ImageItems', $images);
		self::$tpl->display(self::getTplDir('screenshots') . 'frontend.tpl');
		
	}
	public function __action_acp() {
		self::addJsFile('jquery.lightbox-0.5.pack.js', 'screenshots');
		self::addCssFile('screenshots.css', 'screenshots');	
		$images = $this->_scan_dir(2);
		if (empty($images))
			return true;

		self::$tpl->assign('ImageItems', $images);
		self::$tpl->display(self::getTplDir('screenshots') . 'acp.tpl');
		
	}
	
}
