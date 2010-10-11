<?php
abstract class acpPackage extends package{
	public final function runtime(){
		if(!package::$user->isAcpLogin() && $this->_packageName != 'acp_login'){
    		header('Location: index.php?package=acp_login');
    		exit();
    	}
    	$this->runtimeAcp();
	}
	public function runtimeAcp(){}
}