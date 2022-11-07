<?php
class AdminSmartBlogController extends ModuleAdminController
{
	public function __construct() {

     $token = Tools::getAdminTokenLite('AdminModules');
     $currentIndex='index.php?controller=AdminModules&token='.$token.'&configure=smartblog&tab_module=front_office_features&module_name=smartblog';

     parent::__construct();
     Tools::redirectAdmin($currentIndex);
  }
}
