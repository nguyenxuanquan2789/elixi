<?php

include_once dirname(__FILE__).'/../../src/vecVegamenuSubmenuItemClass.php';
class AdminVecVegamenuSubmenuController extends ModuleAdminController {
    public function __construct() {
		$this->bootstrap = true;
        $this->display = 'view';
        parent::__construct();
        $this->meta_title = $this->l('Vec Megamenu');
        if (!$this->module->active) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminHome'));
        }
    }
    public function ajaxProcessSave()
    {
        $data = Tools::getValue('data');
        $id_column = Tools::getValue('id_column');
        $id = Tools::getValue('id');
        //echo $id; die;
        $title = array();
        $errors = array();
        if ($id) {
            $model = new VecVegamenuSubmenuItemClass($id);
        } else {
            $model = new VecVegamenuSubmenuItemClass();
            $model->position = VecVegamenuSubmenuItemClass::getLastPosition() + 1;
            $model->active = 1;
        }
        
        foreach ($data as $param) {
            if ($param['name'] == 'type_link') {
                $model->type_link = pSQL($param['value']);
            }
            if ($param['name'] == 'category_tree') {
                $model->category_tree = pSQL($param['value']);
            }
            if ($param['name'] == 'ps_link') {
                $model->ps_link = pSQL($param['value']);
            }
            if ($param['name'] == 'type_item') {
                $model->type_item = pSQL($param['value']);
            }
            if ($param['name'] == 'id_product') {
                $model->id_product = pSQL($param['value']);
            }
            if ($param['name'] == 'id_manufacturer') {
                $model->id_manufacturer = pSQL($param['value']);
            }
            if ($param['name'] == 'active_mobile') {
                $model->active_mobile = pSQL($param['value']);
            }
            $languages = Language::getLanguages(false);
            foreach ($languages as $language)
            {   
                if ($param['name'] == 'customlink_title_'.$language['id_lang']) {
                    $model->customlink_title[$language['id_lang']] = pSQL($param['value']);
                }
                if ($param['name'] == 'customlink_link_'.$language['id_lang']) {
                    $model->customlink_link[$language['id_lang']] = pSQL($param['value']);
                }
                if ($param['name'] == 'htmlcontent_'.$language['id_lang']) {
                    $model->htmlcontent[$language['id_lang']] = $param['value'];
                }
                if ($param['name'] == 'image_'.$language['id_lang']) {
                    $model->image[$language['id_lang']] = pSQL($param['value']);
                }
                if ($param['name'] == 'image_link_'.$language['id_lang']) {
                    $model->image_link[$language['id_lang']] = pSQL($param['value']);
                }
            }
            
        }
        $model->id_vecvegamenu_submenu_column = $id_column;
        if ($errors) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'errors' => $errors
            )));
        }
        if ($id) {
            $model->save();
        }else{
            $model->add();
        }
        $this->module->clearCache();
        die(Tools::jsonEncode(array(
            'success' => 1,
            'errors' => $errors,
            'model' => $model
        )));
    }
    public function ajaxProcessSwitch()
    {
        $id = Tools::getValue('id');
        $model = new VecVegamenuSubmenuItemClass($id);
        $model->active = !$model->active;
        $model->save();
        $this->module->clearCache();
        die(Tools::jsonEncode(array(
            'active' => (int)$model->active
        )));
    }
    public function ajaxProcessEdit()
    {
        $id = Tools::getValue('id');
        $model = new VecVegamenuSubmenuItemClass($id);
        if($model->id_product > 0) {

            $model->product_name = $this->getProductnameById($model->id_product).' - ID: '.$model->id_product; 
        }else{
            $model->product_name = '';
        }
        die(Tools::jsonEncode($model));
    }
    public function getProductnameById($id_prod)
    {
        $id_lang = (int)$this->context->language->id;   
        $name = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
        SELECT pl.name
        FROM '._DB_PREFIX_.'product_lang pl
        WHERE pl.id_product ='.$id_prod.'
        AND pl.id_lang = '.$id_lang.'');
        return $name['name'];
    }
    public function ajaxProcessDelete(){
        $id = Tools::getValue('id');
        $model = new VecVegamenuSubmenuItemClass($id);
        die(Tools::jsonEncode(array(
            'success' => $model->delete()
        )));
    }
    public function ajaxProcessReload()
    {
        die(Tools::jsonEncode(array(
            'content' => $this->module->renderSubmenu()
        )));
    }
    public function ajaxProcessEditColumn()
    {
        $id = Tools::getValue('id');
        $model = new VecVegamenuSubmenuColumnClass($id);
        die(Tools::jsonEncode($model));
    }
    public function ajaxProcessSaveColumn(){
        $data = Tools::getValue('data');
        $id_row = Tools::getValue('id_row');
        $id = Tools::getValue('id');
        
        $errors = array();
        if ($id) {
            $model = new VecVegamenuSubmenuColumnClass($id);
        } else {
            $model = new VecVegamenuSubmenuColumnClass();
            $model->position = VecVegamenuSubmenuColumnClass::getLastPosition() + 1;
            $model->active = 1;
        }
        
        foreach ($data as $param) {
            if ($param['name'] == 'column_width') {
                $model->width = pSQL($param['value']);
            }
            if ($param['name'] == 'column_class') {
                $model->class = pSQL($param['value']);
            }
            if ($param['name'] == 'column_type_link') {
                $model->type_link = pSQL($param['value']);
            }
            if ($param['name'] == 'column_link') {
                $model->link = pSQL($param['value']);
            }
            if ($param['name'] == 'active_mobile') {
                $model->active_mobile = pSQL($param['value']);
            }
            $languages = Language::getLanguages(false);
            foreach ($languages as $language)
            {   
                if ($param['name'] == 'column_title_'.$language['id_lang']) {
                    $model->title[$language['id_lang']] = pSQL($param['value']);
                }
                if ($param['name'] == 'column_custom_link_'.$language['id_lang']) {
                    $model->custom_link[$language['id_lang']] = pSQL($param['value']);
                }
            }
 
        }
        $model->id_row = $id_row;
        if ($errors) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'errors' => $errors
            )));
        }
        if ($id) {
            $model->save();
        }else{
            $model->add();
        }
        $this->module->clearCache();
        die(Tools::jsonEncode(array(
            'success' => 1,
            'errors' => $errors,
            'model' => $model
        )));
    }
    public function ajaxProcessDeleteColumn(){
        $id = Tools::getValue('id');
        $model = new VecVegamenuSubmenuColumnClass($id);
        die(Tools::jsonEncode(array(
            'success' => $model->delete()
        )));
    }
    // Row functions
    public function ajaxProcessEditRow()
    {
        $id = Tools::getValue('id');
        $model = new VecVegamenuSubmenuRowClass($id);
        die(Tools::jsonEncode($model));
    }
    public function ajaxProcessSaveRow(){
        $data = Tools::getValue('data');
        $id_vecvegamenu_item = Tools::getValue('id_vecvegamenu_item');
        $id = Tools::getValue('id');
        
        $errors = array();
        if ($id) {
            $model = new VecVegamenuSubmenuRowClass($id);
        } else {
            $model = new VecVegamenuSubmenuRowClass();
            $model->position = VecVegamenuSubmenuRowClass::getLastPosition() + 1;
            $model->active = 1;
        }
        
        foreach ($data as $param) {
            if ($param['name'] == 'column_class') {
                $model->class = pSQL($param['value']);
            }
 
        }
        $model->id_vecvegamenu_item = $id_vecvegamenu_item;
        if ($errors) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'errors' => $errors
            )));
        }
        if ($id) {
            $model->save();
        }else{
            $model->add();
        }
        $this->module->clearCache();
        die(Tools::jsonEncode(array(
            'success' => 1,
            'errors' => $errors,
            'model' => $model
        )));
    }
    public function ajaxProcessSwitchRow()
    {
        $id = Tools::getValue('id');
        $model = new VecVegamenuSubmenuRowClass($id);
        $model->active = !$model->active;
        $model->save();
        $this->module->clearCache();
        die(Tools::jsonEncode(array(
            'success' => 1,
        )));
    }
    public function ajaxProcessDeleteRow(){
        $id = Tools::getValue('id');
        $model = new VecVegamenuSubmenuRowClass($id);
        die(Tools::jsonEncode(array(
            'success' => $model->delete()
        )));
    }
}
