<?php
class AdminVecMegamenuController extends ModuleAdminController
{
	public function __construct() {

     $token = Tools::getAdminTokenLite('AdminModules');
     $currentIndex='index.php?controller=AdminModules&token='.$token.'&configure=vecmegamenu&tab_module=front_office_features&module_name=vecmegamenu';

     parent::__construct();
     Tools::redirectAdmin($currentIndex);
  }
}
