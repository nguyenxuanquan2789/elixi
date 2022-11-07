<?php
if (!defined('_PS_VERSION_'))
    exit;
 
require_once (_PS_MODULE_DIR_.'smartblog/classes/SmartBlogPost.php');
require_once (_PS_MODULE_DIR_.'smartblog/smartblog.php');
class smartbloghomelatestnews extends Module {
    
     public function __construct(){
        $this->name = 'smartbloghomelatestnews';
        $this->tab = 'front_office_features';
        $this->version = '2.0';
        $this->bootstrap = true;
        $this->author = 'SmartDataSoft';
        $this->secure_key = Tools::encrypt($this->name);

        parent::__construct();

        $this->displayName = $this->module->trans('SmartBlog Home Latest News', [], 'Modules.Smartblog.smartbloghomelatestnews');
        $this->description = $this->module->trans('The Most Powerfull Presta shop Blog  Module\'s tag - by smartdatasoft', [], 'Modules.Smartblog.smartbloghomelatestnews');
        $this->confirmUninstall = $this->module->trans('Are you sure you want to delete your details ?', [], 'Modules.Smartblog.smartbloghomelatestnews');
        }
        
     public function install(){
                $langs = Language::getLanguages();
                $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
                 if (!parent::install() || !$this->registerHook('displayHome'))
            return false;
                 Configuration::updateValue('smartshowhomepost',3);
                 Configuration::updateGlobalValue('latestnews_sort_by', 'id_DESC');
                 return true;
            }
            
     public function uninstall() {
            if (!parent::uninstall() || !Configuration::deleteByName('smartshowhomepost'))
                 return false;
            return true;
                }
            

    public function smartbloghomelatestnewsHookDisplayHome($params)
    {
        /* Server Params */
        $protocol_link = (Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
        $protocol_content = (isset($useSSL) and $useSSL and Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';

        $smartbloglink = new SmartBlogLink($protocol_link, $protocol_content);


        if (!$this->isCached('smartblog_latest_news.tpl')) {
            $view_data['posts'] = SmartBlogPost::GetPostLatestHome(Configuration::get('smartshowhomepost'));
            $this->smarty->assign(array(
                'smartbloglink' => $smartbloglink,
                'view_data' => $view_data['posts']
            ));
        }
        return $this->display(__FILE__, 'views/templates/front/smartblog_latest_news.tpl');
    }

    public function hookDisplayHome($params)
    {
        return $this->smartbloghomelatestnewsHookDisplayHome($params);
    }

            
     public function getContent(){
         
                $html = '';
                if(Tools::isSubmit('save'.$this->name))
                {
                    Configuration::updateValue('smartshowhomepost', Tools::getvalue('smartshowhomepost'));

                    Configuration::updateValue('latestnews_sort_by', Tools::getvalue('latestnews_sort_by'));

                    $html = $this->displayConfirmation($this->module->trans('The settings have been updated successfully.', [], 'Modules.Smartblog.smartbloghomelatestnews'));
                    $helper = $this->SettingForm();
                    $html .= $helper->generateForm($this->fields_form); 
                    return $html;
                }
                else
                {
                   $helper = $this->SettingForm();
                   $html .= $helper->generateForm($this->fields_form);
                   return $html;
                }
            }
            
     public function SettingForm() {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $this->fields_form[0]['form'] = array(
          'legend' => array(
          'title' => $this->module->trans('General Setting', [], 'Modules.Smartblog.smartbloghomelatestnews'),
            ),
            'input' => array(
                
                array(
                    'type' => 'text',
                    'label' => $this->module->trans('Number of posts to dispay in Lastest News', [], 'Modules.Smartblog.smartbloghomelatestnews'),
                    'name' => 'smartshowhomepost',
                    'size' => 15,
                    'required' => true
                ),
                array(
                    'type' => 'select',
                    'label' => $this->module->trans('Sort Latest News By', [], 'Modules.Smartblog.smartbloghomelatestnews'),
                    'name' => 'latestnews_sort_by',
                    'required' => false,
                    'options' => array(
                        'query' => array( 
                            array(
                                'id_option' => 'name_ASC',
                                'name' => 'Name ASC (A-Z)'
                            ),
                            array(
                                'id_option' => 'name_DESC',
                                'name' => 'Name DESC (Z-A)'
                            ),
                            array(
                                'id_option' => 'id_ASC',
                                'name' => 'Id ASC'
                            ),
                            array(
                                'id_option' => 'id_DESC',
                                'name' => 'Id DESC'
                            ),
                        ),
                        'id' => 'id_option',
                        'name' => 'name'
                    )
                ),               
            ),
            'submit' => array(
                'title' => $this->module->trans('Save', [], 'Modules.Smartblog.smartbloghomelatestnews'),
            )
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        foreach (Language::getLanguages(false) as $lang)
                            $helper->languages[] = array(
                                    'id_lang' => $lang['id_lang'],
                                    'iso_code' => $lang['iso_code'],
                                    'name' => $lang['name'],
                                    'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
                            );
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->module->trans('Save', [], 'Modules.Smartblog.smartbloghomelatestnews'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save'.$this->name.'token=' . Tools::getAdminTokenLite('AdminModules'),
            )
        );
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;       
        $helper->toolbar_scroll = true;    
        $helper->submit_action = 'save'.$this->name;
        
        $helper->fields_value['smartshowhomepost'] = Configuration::get('smartshowhomepost');
        $helper->fields_value['latestnews_sort_by'] = Configuration::get('latestnews_sort_by');
        return $helper;
      }
}