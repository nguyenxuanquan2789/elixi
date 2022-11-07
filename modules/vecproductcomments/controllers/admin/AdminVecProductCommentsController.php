<?php

class AdminVecProductCommentsController extends ModuleAdminController {
    public function __construct() {
        parent::__construct();
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').'&configure=vecproductcomments');
    }
}