<?php
abstract class acpPackage extends package{
	public final function runtime(){
		if(!package::$user->checkAcpLoginExpired())
			package::$user->revokeAcpLogin();
		if(!package::$user->isAcpLogin() && $this->_packageName != 'acp_login'){
    		header('Location: index.php?package=acp_login');
    		exit();
    	}
    	package::$user->acpReLegit();
    	$this->runtimeAcp();
	}
	public function runtimeAcp(){}
}