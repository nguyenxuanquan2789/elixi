<?php
class AdminVecVegamenuController extends ModuleAdminController
{
	public function __construct() {

     $token = Tools::getAdminTokenLite('AdminModules');
     $currentIndex='index.php?controller=AdminModules&token='.$token.'&configure=vecvegamenu&tab_module=front_office_features&module_name=vecvegamenu';

     parent::__construct();
     Tools::redirectAdmin($currentIndex);
  }
}
