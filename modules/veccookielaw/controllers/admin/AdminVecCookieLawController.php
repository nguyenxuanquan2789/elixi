<?php
class AdminVecCookieLawController extends ModuleAdminController
{
	public function __construct() {

     $token = Tools::getAdminTokenLite('AdminModules');
     $currentIndex='index.php?controller=AdminModules&token='.$token.'&configure=veccookielaw&tab_module=front_office_features&module_name=veccookielaw';

     parent::__construct();
     Tools::redirectAdmin($currentIndex);
  }
}
