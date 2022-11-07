<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class SmartBlogCategories extends Module
{ 
    public $templateFile;
    
    public function __construct()
    {
        $this->name       = 'smartblogcategories';
        $this->tab        = 'front_office_features';
        $this->version    = '2.2.2';
        $this->bootstrap  = true;
        $this->author     = 'SmartDataSoft';
        $this->secure_key = Tools::encrypt($this->name);
        
        parent::__construct();
        
        $this->displayName      = $this->l('Smart Blog Categories');
        $this->description      = $this->l('The Most Powerfull Presta shop Blog  Module\'s tag - by smartdatasoft');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
        
        $this->templateFile = 'module:smartblogcategories/views/templates/front/smartblogcategories.tpl';
    }
    public function install()
    {
        if (!parent::install() || 
			!$this->registerHook('displaySmartBlogLeft') || 
			!$this->registerHook('displaySmartBlogRight') || 
			!$this->registerHook('actionsbdeletecat') || 
			!$this->registerHook('actionsbnewcat') || 
			!$this->registerHook('actionsbupdatecat') || 
			!$this->registerHook('actionsbtogglecat') || 
			!$this->registerHook('displayHeader'))
		{
            return false;
		}
		
        return true;
    }
    
    public function uninstall()
    {
        $this->DeleteCache();
		
        if (!parent::uninstall())
		{
            return false;
		}
		
        return true;
    }
    
    public function hookLeftColumn($params)
    {
        
        if (Module::isInstalled('smartblog') != 1) {
            $this->smarty->assign(array(
                'smartmodname' => $this->name
            ));
            return $this->display(__FILE__, 'views/templates/front/install_required.tpl');
        } else {
		
			if (!$this->isCached($this->templateFile)) {
				$view_data = array();
				$id_lang   = $this->context->language->id;

				/*arif call*/

				$maxdepth = 4;
				// Get all groups for this customer and concatenate them as a string: "1,2,3..."
				$groups   = implode(', ', Customer::getGroupsStatic((int) $this->context->customer->id));

				$active = 1;
				$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
				SELECT *
				FROM `' . _DB_PREFIX_ . 'smart_blog_category` c
				LEFT JOIN `' . _DB_PREFIX_ . 'smart_blog_category_lang` cl ON c.`id_smart_blog_category` = cl.`id_smart_blog_category`
				LEFT JOIN `' . _DB_PREFIX_ . 'smart_blog_category_shop` cs ON c.`id_smart_blog_category` = cs.`id_smart_blog_category`
				WHERE   `id_lang` = ' . (int) $id_lang . '
				' . ($active ? 'AND `active` = 1' : '') . '
				AND cs.`id_shop` = ' . (int) Context::getContext()->shop->id . ' 
				ORDER BY `meta_title` ASC');

				$resultParents = array();
				$resultIds     = array();
				foreach ($result as &$row) {
					$resultParents[$row['id_parent']][] =& $row;
					$resultIds[$row['id_smart_blog_category']] =& $row;
				}

				$root_id = 1;
				$blockCategTree = $this->getTree($resultParents, $resultIds, 10, 0);

				if (!Configuration::get('smartblogrootcat')) {
					$blockCategTree = array(
						'id' => 0,
						'link' => '',
						'name' => '',
						'desc' => '',
						'children' => $blockCategTree['children']?$blockCategTree['children'][0]['children']:array()
					);
				}

				$this->smarty->assign('blockCategTree', $blockCategTree);
				$this->smarty->assign('select', true);

			}
			return $this->fetch($this->templateFile, $this->getCacheId());

		}
    }
    
    public function getTree($resultParents, $resultIds, $maxDepth, $id_smart_blog_category = null, $currentDepth = 0)
    {
        if (is_null($id_smart_blog_category)) {
            $id_smart_blog_category = $this->context->shop->getCategory();
        }
        
        $children = array();
        
        if (isset($resultParents[$id_smart_blog_category]) && count($resultParents[$id_smart_blog_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth)) {
            foreach ($resultParents[$id_smart_blog_category] as $subcat) {
                $children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_smart_blog_category'], $currentDepth + 1);
            }
        }
        
        if (isset($resultIds[$id_smart_blog_category])) {
            
            $smartbloglink = new SmartBlogLink();
            $link = $smartbloglink->getSmartBlogCategoryLink($id_smart_blog_category, $resultIds[$id_smart_blog_category]['link_rewrite']);
            $name = $resultIds[$id_smart_blog_category]['name'];
            
            $level_depth = str_repeat('&nbsp;', $resultIds[$id_smart_blog_category]['level_depth'] * 2);
        } else {
            $level_depth = $link = $name = '';
        }
        
        return array(
            'id' => $id_smart_blog_category,
            'link' => $link,
            'name' => $name,
            'level_depth' => $level_depth,
            'children' => $children
        );
    }
	
    public function hookRightColumn($params)
    {
        return $this->hookLeftColumn($params);
    }
	
    public function hookdisplaySmartBlogLeft($params)
    {
        return $this->hookLeftColumn($params);
    }
	
    public function hookdisplaySmartBlogRight($params)
    {
        return $this->hookLeftColumn($params);
    }
	
    public function DeleteCache()
    {
        return $this->_clearCache($this->templateFile, $this->getCacheId());
    }
	
    public function hookactionsbdeletecat($params)
    {
        return $this->DeleteCache();
    }
	
    public function hookactionsbnewcat($params)
    {
        return $this->DeleteCache();
    }
	
    public function hookactionsbupdatecat($params)
    {
        return $this->DeleteCache();
    }
    public function hookactionsbtogglecat($params)
    {
        return $this->DeleteCache();
    }
    
}