<?php
/*
* 2017 AxonVIP
*
* NOTICE OF LICENSE
*
*  @author AxonVIP <axonvip@gmail.com>
*  @copyright  2017 axonvip.com
*   
*/

class VecPopupNewsletterAjaxModuleFrontController extends ModuleFrontController
{
    /**
     * @var int
     */
    public $dataForm;

    public function init()
    {
        parent::init();
    }

    public function postProcess()
    {
        if(Tools::getIsset('email')){
            $this->processPopup();
        }
    }

    public function processPopup()
    {
		$dataForm = array();
		
		$dataForm['email'] = Tools::getValue('email');
		$dataForm['action'] = (int)Tools::getValue('action');
		
		$result = $this->module->ActionRegistered($dataForm);
		
		if(ob_get_contents()){
			ob_end_clean();
		}
		header('Content-Type: application/json');
		
		die(Tools::jsonEncode($result));
		
    }
	
}
