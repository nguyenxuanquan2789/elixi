<?php
if (!defined('_PS_VERSION_')) {
	exit;
}

define('_MODULE_SMARTBLOG_VERSION_', '4.1.1');
define('_MODULE_SMARTBLOG_DIR_', _PS_MODULE_DIR_ . 'smartblog/images/');
define('_MODULE_SMARTBLOG_URL_', _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . '/modules/' . 'smartblog/');
define('_MODULE_SMARTBLOG_IMAGE_URL_', _MODULE_SMARTBLOG_URL_ . 'images/');
define('_MODULE_SMARTBLOG_GALLARY_DIR_', _PS_MODULE_DIR_ . 'smartblog/gallary/');
define('_MODULE_SMARTBLOG_JS_DIR_', _PS_MODULE_DIR_ . 'smartblog/views/js/');
define('_MODULE_SMARTBLOG_CLASS_DIR_', _PS_MODULE_DIR_ . 'smartblog/classes/');

require_once dirname(__FILE__) . '/classes/BlogCategory.php';
require_once dirname(__FILE__) . '/classes/BlogImageType.php';
require_once dirname(__FILE__) . '/classes/BlogTag.php';
require_once dirname(__FILE__) . '/classes/SmartBlogPost.php';
require_once dirname(__FILE__) . '/classes/SmartBlogHelperTreeCategories.php';
require_once dirname(__FILE__) . '/classes/Blogcomment.php';
require_once dirname(__FILE__) . '/classes/BlogPostCategory.php';
require_once dirname(__FILE__) . '/classes/SmartBlogLink.php';
class smartblog extends Module
{
	public $nrl;
	public $crl;
	public $erl;
	public $capl;
	public $warl;
	public $sucl;
	public $blog_url;


	public function __construct()
	{

		$this->name          = 'smartblog';
		$this->tab           = 'front_office_features';
		$this->version       = '4.1.1';
		$this->author        = 'SmartDataSoft';
		$this->need_upgrade  = true;
		$this->controllers   = array('category', 'details', 'search', 'tagpost', "archivemonth","list");
		$this->secure_key    = Tools::encrypt($this->name);
		$this->smart_shop_id = Context::getContext()->shop->id;
		$this->bootstrap     = true;
		parent::__construct();
		$this->displayName = $this->trans('Smart Blog', [], 'Modules.Smartblog.Smartblog');
		$this->nrl  = $this->trans('Name is required', [], 'Modules.Smartblog.Smartblog');
		$this->crl  = $this->trans('Comment must be between 25 and 1500 characters!', [], 'Modules.Smartblog.Smartblog');
		$this->erl  = $this->trans('E-mail address not valid !', [], 'Modules.Smartblog.Smartblog');
		$this->capl = $this->trans('Captcha is not valid', [], 'Modules.Smartblog.Smartblog');
		$this->warl = $this->trans('Warning: Please check required form bellow!', [], 'Modules.Smartblog.Smartblog');
		$this->sucl = $this->trans('Your comment successfully submitted.', [], 'Modules.Smartblog.Smartblog');
		$this->description      = $this->trans('The Most Powerfull Prestashop Blog  Module - by smartdatasoft', [], 'Modules.Smartblog.Smartblog');
		$this->confirmUninstall = $this->trans('Are you sure you want to delete your details ?', [], 'Modules.Smartblog.Smartblog');
		$this->module_key       = '5679adf718951d4bc63422b616a9d75d';
		$this->blog_url = '';
	}

	public function install()
	{

		Configuration::updateGlobalValue('smartblogrootcat', '1');
		Configuration::updateGlobalValue('smartpostperpage', '5');
		Configuration::updateGlobalValue('smartpostperrow', '2');
		Configuration::updateGlobalValue('sborderby', 'id_smart_blog_post');
		Configuration::updateGlobalValue('sborder', 'DESC');
		Configuration::updateGlobalValue('smartshowauthorstyle', '1');
		Configuration::updateGlobalValue('smartshowauthor', '1');
		Configuration::updateGlobalValue('smartmainblogurl', 'smartblog');
		Configuration::updateGlobalValue('smartusehtml', '1');
		Configuration::updateGlobalValue('smartshowauthorstyle', '1');
		Configuration::updateGlobalValue('smartenablecomment', '1');
		Configuration::updateGlobalValue('smartenableguestcomment', '1');
		Configuration::updateGlobalValue('smartcaptchaoption', '1');
		Configuration::updateGlobalValue('smartshowviewed', '1');
		Configuration::updateGlobalValue('smartshownoimg', '1');
		Configuration::updateGlobalValue('smartsearchengine', '1');
		Configuration::updateGlobalValue('smartshowcolumn', '3');
		Configuration::updateGlobalValue('smartacceptcomment', '1');
		Configuration::updateGlobalValue('smartdisablecatimg', '1');
		Configuration::updateGlobalValue('smartdataformat', 'm/d/Y H:i:s');
		Configuration::updateGlobalValue('smartblogurlpattern', 1);
		Configuration::updateGlobalValue('smartblogmetatitle', 'Smart Blog Title');
		Configuration::updateGlobalValue('smartstyle', '1');
		Configuration::updateGlobalValue('smartblogmetakeyword', 'smart,blog,smartblog,prestashop blog,prestashop,blog');
		Configuration::updateGlobalValue('smartblogmetadescrip', 'Prestashop powerfull blog site developing module. It has hundrade of extra plugins. This module developed by SmartDataSoft.com');

		$ret  = (bool) parent::install();
		$ret &= $this->addquickaccess();
		$ret &= $this->htaccessCreate();
		$ret &= $this->registerHook('displayHeader') &&
			$this->registerHook('header') &&
			$this->registerHook('moduleRoutes') &&
			$this->registerHook('displayBackOfficeHeader') &&
			$this->registerHook('displayOverrideTemplate');

		$ret &= $this->installSql();
		$ret &= $this->CreateSmartBlogTabs();
		$ret &= $this->requiredDataInstall();
		$ret &= $this->sampleDataInstall();
		$ret &= $this->installDummyData();

		// Later Will Be Fine Tuned
		// *************************************

		$ret &= $this->SmartHookInsert();
		$ret &= $this->SmartHookRegister();
		return true;
	}

	public function isUsingNewTranslationSystem(){
		return true;
	}

	protected function installSql()
	{
		$sql = array();
		include_once dirname(__FILE__) . '/sql/install.php';
		foreach ($sql as $sq) :
			if (!Db::getInstance()->Execute($sq)) {
				return false;
			}
		endforeach;
		return true;
	}

	public function installDummyData()
	{
		$image_types         = BlogImageType::GetImageAllType('post');
		$id_smart_blog_posts = $this->getAllPost();

		$tmp_name            = tempnam(_PS_TMP_IMG_DIR_, 'PS');
		$langs               = Language::getLanguages();
		$arrayImg = array();
		foreach (scandir(__DIR__ . '/dummy_data') as $images) {
			if (in_array($images, array('.', '..', '.DS_Store'))) {
				continue;
			}
			$arrayImg[] = $images;
		}

		$img_count = 0;
		$dummy_post_ids = array();
		foreach ($id_smart_blog_posts as $id_smart_blog_post) {
			$dummy_post_ids[] = $id_smart_blog_post['id_smart_blog_post'];
		}


		$dummy_post_ids = array_unique($dummy_post_ids);

		foreach ($dummy_post_ids as $id_smart_blog_post) {
			$files_to_delete = array();
			$files_to_delete[] = _PS_TMP_IMG_DIR_ . 'smart_blog_post_' . $id_smart_blog_post . '.jpg';
			$files_to_delete[] = _PS_TMP_IMG_DIR_ . 'smart_blog_post_mini_' . $id_smart_blog_post . '.jpg';
			foreach ($langs as $l) {
				$files_to_delete[] = _PS_TMP_IMG_DIR_ . 'smart_blog_post_' . $id_smart_blog_post . '_' . $l['id_lang'] . '.jpg';
				$files_to_delete[] = _PS_TMP_IMG_DIR_ . 'smart_blog_post_mini_' . $id_smart_blog_post . '_' . $l['id_lang'] . '.jpg';
			}
			foreach ($files_to_delete as $file) {
				if (file_exists($file)) {
					@unlink($file);
				}
			}
			if (isset($arrayImg[$img_count])) {
				Tools::Copy(__DIR__ . '/dummy_data/' . $arrayImg[$img_count], _PS_MODULE_DIR_ . '/smartblog/images/' . $id_smart_blog_post . '.jpg');
				foreach ($image_types as $image_type) {
					ImageManager::resize(
						__DIR__ . '/dummy_data/' . $arrayImg[$img_count],
						_PS_MODULE_DIR_ . 'smartblog/images/' . $id_smart_blog_post . '-' . stripslashes($image_type['type_name']) . '.jpg',
						(int) $image_type['width'],
						(int) $image_type['height']
					);
				}
			}
			$img_count = (count($arrayImg) > $img_count) ? $img_count + 1 : 0;
		}
		Tools::Copy(__DIR__ . '/dummy_data/no.jpg', _PS_MODULE_DIR_ . '/smartblog/images/no.jpg');
		foreach ($image_types as $image_type) {
			ImageManager::resize(
				__DIR__ . '/dummy_data/no.jpg',
				_PS_MODULE_DIR_ . 'smartblog/images/no-' . stripslashes($image_type['type_name']) . '.jpg',
				(int) $image_type['width'],
				(int) $image_type['height']
			);
		}
	}

	public static function getAllPost()
	{
		$sql = 'SELECT p.id_smart_blog_post  FROM `' . _DB_PREFIX_ . 'smart_blog_post_lang` p';
		if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
			return false;
		}
		return $result;
	}

	public function hookactionHtaccessCreate()
	{
		$content = file_get_contents(_PS_ROOT_DIR_ . '/.htaccess');
		if (!preg_match('/\# Images Blog\n/', $content)) {
			$content = preg_replace_callback('/\# Images\n/', array($this, 'updateSiteHtaccess'), $content);
			@file_put_contents(_PS_ROOT_DIR_ . '/.htaccess', $content);
		}
	}

	public function hookDisplayBackOfficeHeader($params)
	{
		$this->context->controller->addJquery();
		$this->context->controller->addCSS($this->_path . 'views/css/admin.css');
	}

	public function hookDisplayHeader($params)
	{
		//$this->context->controller->addCSS($this->_path . 'views/css/fw.css');
		$this->context->controller->addCSS($this->_path . 'views/css/smartblogstyle.css', 'all');
		$smartblogurlpattern = (int) Configuration::get('smartblogurlpattern');
		$id_post             = null;
		switch ($smartblogurlpattern) {
			case 1:
				$slug    = Tools::getValue('slug');
				$id_post = self::slug2id($slug);
				break;
			case 2:
				$id_post = pSQL(Tools::getvalue('id_post'));
				break;
			case 3:
				$id_post = pSQL(Tools::getvalue('id_post'));
				break;
			default:
				$id_post = pSQL(Tools::getvalue('id_post'));
		}
		if ($id_post) {
			$obj_post         = new SmartBlogPost($id_post, true, $this->context->language->id, $this->context->shop->id);
			$meta_title       = $obj_post->meta_title;
			$meta_keyword     = $obj_post->meta_keyword;
			$meta_description = $obj_post->meta_description;
		} else {
			$meta_title       = Configuration::get('smartblogmetatitle');
			$meta_keyword     = Configuration::get('smartblogmetakeyword');
			$meta_description = Configuration::get('smartblogmetadescrip');
		}
	}

	public function htaccessCreate()
	{
		$content = file_get_contents(_PS_ROOT_DIR_ . '/.htaccess');
		if (!preg_match('/\# Images Blog\n/', $content)) {
			$content = preg_replace_callback('/\# Images\n/', array($this, 'updateSiteHtaccess'), $content);
			@file_put_contents(_PS_ROOT_DIR_ . '/.htaccess', $content);
		}
		return true;
	}

	public function updateSiteHtaccess($match)
	{
		$htupdate = '';
		include_once dirname(__FILE__) . '/htupdate.php';
		$str = '';
		if (isset($match[0])) {
			$str .= "\n{$htupdate}\n\n{$match[0]}\n";
		}
		return $str;
	}

	public function addquickaccess()
	{
		$link      = new Link();
		$qa        = new QuickAccess();
		$qa->link  = $link->getAdminLink('AdminModules') . '&configure=smartblog';
		$languages = Language::getLanguages(false);
		foreach ($languages as $language) {
			$qa->name[$language['id_lang']] = 'Smart Blog Setting';
		}
		$qa->new_window = '0';
		if ($qa->save()) {
			Configuration::updateValue('smartblog_quick_access', $qa->id);
			return true;
		}
	}

	protected function CreateSmartBlogTabs()
	{

		$postabID = Tab::getIdFromClassName('VecThemeMenu');
		$langs                = Language::getLanguages();
		$smarttab             = new Tab();
		$smarttab->class_name = 'SMARTBLOG';
		$smarttab->module     = '';
		$smarttab->id_parent  = $postabID;
		foreach ($langs as $l) {
			$smarttab->name[$l['id_lang']] = $this->trans('Blog', [], 'Modules.Smartblog.Smartblog');
		}
		$smarttab->icon = 'announcement';
		$smarttab->position = 2;
		$smarttab->save();
		$tab_id = $smarttab->id;
		@copy(dirname(__FILE__) . '/views/img/AdminSmartBlog.gif', _PS_ROOT_DIR_ . '/img/t/AdminSmartBlog.gif');

		$tabvalue = array();
		// assign tab value from include file
		include_once dirname(__FILE__) . '/sql/install_tab.php';
		foreach ($tabvalue as $tab) {
			$newtab             = new Tab();
			$newtab->class_name = $tab['class_name'];
			if ($tab['id_parent'] == -1) {
				$newtab->id_parent = $tab['id_parent'];
			} else {
				$newtab->id_parent = $tab_id;
			}
			$newtab->icon = NULL;
			$newtab->module = $tab['module'];
			foreach ($langs as $l) {
				$newtab->name[$l['id_lang']] = $this->trans($tab['name'], [], 'Modules.Smartblog.Smartblog');
			}
			$newtab->save();
		}
		return true;
	}

	public function requiredDataInstall()
	{
		$ret  = true;
		$ret &= Db::getInstance()->execute(
			'INSERT INTO `' . _DB_PREFIX_ . 'smart_blog_category` (`id_parent`,`level_depth`,`position`,`active`,`created`) VALUES (0,0,0,1,NOW())'
		);

		$ret &= Db::getInstance()->execute(
			'INSERT INTO `' . _DB_PREFIX_ . 'smart_blog_category_shop` (`id_smart_blog_category`,`id_shop`) VALUES (1,' . (int) $this->smart_shop_id . ')'
		);

		$languages = Language::getLanguages(false);
		foreach ($languages as $language) {
			$ret &= Db::getInstance()->execute(
				'INSERT INTO `' . _DB_PREFIX_ . 'smart_blog_category_lang` (`id_smart_blog_category`,`name`,`meta_title`,`id_lang`,`link_rewrite`) VALUES (1,"Home","Home",' . (int) $language['id_lang'] . ",'home')"
			);
		}

		
		$type_name = 'category-default';
		$width     = '470';
		$height    = '289';
		$type      = 'Category';

		$damiimgtype = 'INSERT INTO ' . _DB_PREFIX_ . "smart_blog_imagetype (type_name,width,height,type,active) VALUES ('" . $type_name . "','" . $width . "','" . $height . "','" . $type . "',1);";
		$ret        &= Db::getInstance()->execute($damiimgtype);
		
		return $ret;
	}

	public function sampleDataInstall()
	{
		for ($i = 1; $i <= 4; $i++) {
			Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'smart_blog_post`(`id_author`, `id_category`, `position`, `active`, `available`, `created`, `viewed`, `comment_status`) VALUES(1,1,0,1,1,NOW(),0,1)');
		}

		$languages = Language::getLanguages(false);
		for ($i = 1; $i <= 4; $i++) {
			if ($i == 1) :
				$title = 'Building intelligent transportation systems';
				$slug  = 'building-intelligent-transportation-systems';
				$des   = 'ThemeVec is an offshore web development company located in Bangladesh. We are serving this sector since 2010. Our team is committed to develop high quality web based application and theme for our clients and also for the global marketplace. As your web development partner we will assist you in planning, development, implementation and upgrade! Why SmartDataSoft? SmartDataSoft released their first prestashop theme in November 2012. Till now we have 6+ prestashop theme which are getting sold on global renowned marketplace. We have brought products like Revolution Slider and Visual Composer to PrestaShop. SmartBlog, the most popular blog module for PrestaShop is also built by us.
				
				After a long time SmartDataSoft is back as ThemeVec with a team of talented developers. Till now they have already released two awesome modules. The best elementor based page builder for PrestaShop Crazy Elements and the latest version of the best slider module Revolution Slider 6. They have brought the latest version of SmartBlog 4.0.0 on their site. Classy Product Extra Tab is another free module with which you can add extra tabs to your products.';
			elseif ($i == 2) :
				$title = 'The Ultimate Success Formula – How?';
				$slug  = 'the-ultimate-success-formula-how';
				$des   = 'PrestaShop has launched PrestaShop 1.7, the latest software version. It was developed with the help of user feedback from the last few years. Today, over 250,000 online stores use PrestaShop to sell their products. What do these sellers need to know?
				
				How can I upgrade my store to 1.7? Developers are updating the one-click upgrade module to work with the transition to version 1.7. Take note that this module will only deal with your store\'s data. The theme and modules will be those used by default, and your theme from version 1.5/1.6 will be deactivated, as will all of your third-party modules. Be sure to consider that before updating! We strongly recommend you get in touch with our partner agencies and developers to make sure that your move to version 1.7 goes off without a hitch.';
			elseif ($i == 3) :
				$title = 'Utilizing mobile technology in the field';
				$slug  = 'utilizing-mobile-technology-in-the-field';
				$des   = 'Vec Elements and elementor based page builder for PrestaShop vows to upgrade and take your PrestaShop web page editing and scheming abilities to a whole new level.

				This elementor based add-ons is the most recent inclusion in the list of PrestaShop premium product libraries. Appreciate this premium page builder for elementor that consist of leading-edge widgets that are clearly set to take your PrestaShop page building knowledge to the next level.
				
				Unlike most other addons, Crazy Elements offers itself with numerous strong  widgets. These significant widgets would surely give you a feel of surprise about these wonderful widgets. Let’s explore all the outstanding widgets of Crazy Elements.';
			elseif ($i == 4) :
				$title = 'Working from home? Let’s get started.';
				$slug  = 'working-from-home-let-get-started';
				$des   = 'Slider Revolution is a PrestaShop advanced module for today’s skyward web design demands. Wrapped with cool features, it can turn dull and static designs into visually-grabbing, responsive websites with just a few snaps.
				
				This Powerful PrestaShop module helps the designer of any level to display the website to their visitors and clients in an attractive way. You can now wow your clients and site visitors with astonishing responsive designs that look wonderful on any device. No coding knowledge is needed. Transcend even the most unreal fantasy with special effects, animation, and exciting designs—the powerful drag & drop visual editor will let you tell your own stories in no time!
				
				Create simplistic or high-level content modules with our entirely visual editor. No coding knowledge is needed.';
			endif;
			foreach ($languages as $language) {
				if (!Db::getInstance()->Execute(
					'INSERT INTO `' . _DB_PREFIX_ . 'smart_blog_post_lang`(`id_smart_blog_post`,`id_lang`,`meta_title`,`meta_description`,`short_description`,`content`,`link_rewrite`)
                        VALUES(' . (int) $i . ',' . (int) $language['id_lang'] . ', 
							"' . htmlspecialchars($title) . '", 
							"' . htmlspecialchars($des) . '","' . Tools::substr($des, 0, 200) . '","' . htmlspecialchars($des) . '","' . $slug . '"
						)'
				)) {
					return false;
				}
			}
		}

		for ($i = 1; $i <= 4; $i++) {
			Db::getInstance()->Execute(
				'INSERT INTO `' . _DB_PREFIX_ . 'smart_blog_post_shop`(`id_smart_blog_post`, `id_shop`) 
        VALUES(' . (int) $i . ',' . (int) $this->smart_shop_id . ')'
			);
		}
		for ($i = 1; $i <= 3; $i++) {
			if ($i == 1) :
				$type_name = 'home-default';
				$width     = '470';
				$height    = '289';
				$type      = 'post';
			elseif ($i == 2) :
				$type_name = 'home-small';
				$width     = '200';
				$height    = '123';
				$type      = 'post';
			elseif ($i == 3) :
				$type_name = 'single-default';
				$width     = '1410';
				$height    = '868';
				$type      = 'post';
			endif;
			$damiimgtype = 'INSERT INTO ' . _DB_PREFIX_ . "smart_blog_imagetype (type_name,width,height,type,active) VALUES ('" . $type_name . "','" . $width . "','" . $height . "','" . $type . "',1);";
			Db::getInstance()->execute($damiimgtype);
		}
		return true;
	}

	public function hookHeader($params)
	{
		$this->smarty->assign('meta_title', 'This is Title' . ' - ' . 'MName');
	}
	public function SmartHookInsert()
	{
		$hookvalue = array();
		include_once dirname(__FILE__) . '/sql/addhook.php';

		foreach ($hookvalue as $hkv) {

			$hookid = Hook::getIdByName($hkv['name']);
			if (!$hookid) {
				$add_hook              = new Hook();
				$add_hook->name        = pSQL($hkv['name']);
				$add_hook->title       = pSQL($hkv['title']);
				$add_hook->description = pSQL($hkv['description']);
				$add_hook->position    = pSQL($hkv['position']);
				$add_hook->live_edit   = $hkv['live_edit'];
				$add_hook->add();
				$hookid = $add_hook->id;
				if (!$hookid) {
					return false;
				}
			} else {
				$up_hook = new Hook($hookid);
				$up_hook->update();
			}
		}
		return true;
	}

	public function SmartHookRegister()
	{
		$hookvalue = array();
		include_once dirname(__FILE__) . '/sql/addhook.php';

		foreach ($hookvalue as $hkv) {

			$this->registerHook($hkv['name']);
		}
		return true;
	}

	public function uninstall()
	{
		if (
			!parent::uninstall()
			|| !Configuration::deleteByName('smartblogmetatitle')
			|| !Configuration::deleteByName('smartblogmetakeyword')
			|| !Configuration::deleteByName('smartblogmetadescrip')
			|| !Configuration::deleteByName('smartpostperpage')
			|| !Configuration::deleteByName('smartpostperrow')
			|| !Configuration::deleteByName('sborderby')
			|| !Configuration::deleteByName('sborder')
			|| !Configuration::deleteByName('smartblogrootcat')
			|| !Configuration::deleteByName('smartacceptcomment')
			|| !Configuration::deleteByName('smartusehtml')
			|| !Configuration::deleteByName('smartcaptchaoption')
			|| !Configuration::deleteByName('smartshowviewed')
			|| !Configuration::deleteByName('smartdisablecatimg')
			|| !Configuration::deleteByName('smartenablecomment')
			|| !Configuration::deleteByName('smartenableguestcomment')
			|| !Configuration::deleteByName('smartmainblogurl')
			|| !Configuration::deleteByName('smartshowcolumn')
			|| !Configuration::deleteByName('smartshowauthorstyle')
			|| !Configuration::deleteByName('smartshownoimg')
			|| !Configuration::deleteByName('smartsearchengine')
			|| !Configuration::deleteByName('smartshowauthor')
			|| !Configuration::deleteByName('smartblogurlpattern')
		) {
			return false;
		}

		$idtabs = array();

		include_once dirname(__FILE__) . '/sql/uninstall_tab.php';
		foreach ($idtabs as $tabid) :
			if ($tabid) {
				$tab = new Tab($tabid);
				$tab->delete();
			}
		endforeach;
		$sql = array();
		include_once dirname(__FILE__) . '/sql/uninstall.php';
		foreach ($sql as $s) :
			if (!Db::getInstance()->Execute($s)) {
				return false;
			}
		endforeach;

		// $this->SmartHookDelete();
		$this->deletequickaccess();
		$this->DeleteCache();
		return true;
	}

	public function deletequickaccess()
	{
		$qa = new QuickAccess(Configuration::get('smartblog_quick_access'));
		$qa->delete();
	}


	public function getContent()
	{
		$feed_url      = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/smartblog/rss.php';
		$feed_url_html = '<div class="row">
        <div class="alert alert-info"><strong>Feed URL: </strong>' . $feed_url . '</div>
    </div>';

		$html = '';

		$this->autoregisterhook('moduleRoutes', 'smartblog');
		$this->autoregisterhook('vcBeforeInit', 'smartlegendaaddons');
		if (Tools::isSubmit('savesmartblog')) {

			Configuration::updateValue('smartblogmetatitle', Tools::getvalue('smartblogmetatitle'));
			Configuration::updateValue('smartenablecomment', Tools::getvalue('smartenablecomment'));
			Configuration::updateValue('smartenableguestcomment', Tools::getvalue('smartenableguestcomment'));
			Configuration::updateValue('smartblogmetakeyword', Tools::getvalue('smartblogmetakeyword'));
			Configuration::updateValue('smartblogmetadescrip', Tools::getvalue('smartblogmetadescrip'));
			Configuration::updateValue('smartpostperpage', Tools::getvalue('smartpostperpage'));
			Configuration::updateValue('smartpostperrow', Tools::getvalue('smartpostperrow'));
			Configuration::updateValue('sborderby', Tools::getvalue('sborderby'));
			Configuration::updateValue('sborder', Tools::getvalue('sborder'));
			Configuration::updateValue('smartblogrootcat', Tools::getvalue('smartblogrootcat'));
			Configuration::updateValue('smartblogurlpattern', Tools::getvalue('smartblogurlpattern'));
			Configuration::updateValue('smartacceptcomment', Tools::getvalue('smartacceptcomment'));
			Configuration::updateValue('smartcaptchaoption', Tools::getvalue('smartcaptchaoption'));
			Configuration::updateValue('smartshowviewed', Tools::getvalue('smartshowviewed'));
			Configuration::updateValue('smartdisablecatimg', Tools::getvalue('smartdisablecatimg'));
			Configuration::updateValue('smartshowauthorstyle', Tools::getvalue('smartshowauthorstyle'));
			Configuration::updateValue('smartshowauthor', Tools::getvalue('smartshowauthor'));
			Configuration::updateValue('smartshowcolumn', Tools::getvalue('smartshowcolumn'));
			Configuration::updateValue('smartmainblogurl', Tools::getvalue('smartmainblogurl'));
			Configuration::updateValue('smartusehtml', Tools::getvalue('smartusehtml'));
			Configuration::updateValue('smartshownoimg', Tools::getvalue('smartshownoimg'));
			Configuration::updateValue('smartsearchengine', Tools::getvalue('smartsearchengine'));
			Configuration::updateValue('smartdataformat', Tools::getvalue('smartdataformat'));
			Configuration::updateValue('smartstyle', Tools::getvalue('smartstyle'));

			$html   = $this->displayConfirmation($this->trans('The settings have been updated successfully.', [], 'Modules.Smartblog.Smartblog'));

			$helper = $this->SettingForm();
			$html  .= $feed_url_html;
			$html  .= $helper->generateForm($this->fields_form);
			$helper = $this->regenerateform();
			$html  .= $helper->generateForm($this->fields_form);

			return $html;
		} elseif (Tools::isSubmit('generateimage')) {
			if (Tools::getvalue('isdeleteoldthumblr') != 1) {
				BlogImageType::ImageGenerate();
				$html   = $this->displayConfirmation($this->trans('Generate New Thumblr Succesfully.', [], 'Modules.Smartblog.Smartblog'));
				$helper = $this->SettingForm();
				$html  .= $helper->generateForm($this->fields_form);
				$helper = $this->regenerateform();
				$html  .= $helper->generateForm($this->fields_form);

				return $html;
			} else {
				BlogImageType::ImageDelete();
				BlogImageType::ImageGenerate();
				$html   = $this->displayConfirmation($this->trans('Delete Old Image and Generate New Thumblr Succesfully.', [], 'Modules.Smartblog.Smartblog'));
				$helper = $this->SettingForm();
				$html  .= $helper->generateForm($this->fields_form);
				$helper = $this->regenerateform();
				$html  .= $helper->generateForm($this->fields_form);

				return $html;
			}
		} else {

			$helper = $this->SettingForm();
			$html  .= $helper->generateForm($this->fields_form);
			$helper = $this->regenerateform();
			$html  .= $helper->generateForm($this->fields_form);

			return $html;
		}
	}

	public function autoregisterhook($hook_name = 'moduleRoutes', $module_name = 'smartblog', $shop_list = null)
	{
		if ((Module::isEnabled($module_name) == 1) && (Module::isInstalled($module_name) == 1)) {
			$return    = true;
			$id_sql    = 'SELECT `id_module` FROM `' . _DB_PREFIX_ . 'module` WHERE `name` = "' . $module_name . '"';
			$id_module = Db::getInstance()->getValue($id_sql);
			if (is_array($hook_name)) {
				$hook_names = $hook_name;
			} else {
				$hook_names = array($hook_name);
			}
			foreach ($hook_names as $hook_name) {
				if (!Validate::isHookName($hook_name)) {
					throw new PrestaShopException('Invalid hook name');
				}
				if (!isset($id_module) || !is_numeric($id_module)) {
					return false;
				}
				// $hook_name_bak = $hook_name;
				if ($alias = Hook::getRetroHookName($hook_name)) {
					$hook_name = $alias;
				}
				$id_hook = Hook::getIdByName($hook_name);
				// $live_edit = Hook::getLiveEditById((int) Hook::getIdByName($hook_name_bak));
				if (!$id_hook) {
					$new_hook            = new Hook();
					$new_hook->name      = pSQL($hook_name);
					$new_hook->title     = pSQL($hook_name);
					$new_hook->live_edit = (bool) preg_match('/^display/i', $new_hook->name);
					$new_hook->position  = (bool) $new_hook->live_edit;
					$new_hook->add();
					$id_hook = $new_hook->id;
					if (!$id_hook) {
						return false;
					}
				}
				if (is_null($shop_list)) {
					$shop_list = Shop::getShops(true, null, true);
				}
				foreach ($shop_list as $shop_id) {
					$sql = 'SELECT hm.`id_module`
                        FROM `' . _DB_PREFIX_ . 'hook_module` hm, `' . _DB_PREFIX_ . 'hook` h
                        WHERE hm.`id_module` = ' . (int) ($id_module) . ' AND h.`id_hook` = ' . (int) $id_hook . '
                        AND h.`id_hook` = hm.`id_hook` AND `id_shop` = ' . (int) $shop_id;
					if (Db::getInstance()->getRow($sql)) {
						continue;
					}

					$sql = 'SELECT MAX(`position`) AS position
                        FROM `' . _DB_PREFIX_ . 'hook_module`
                        WHERE `id_hook` = ' . (int) $id_hook . ' AND `id_shop` = ' . (int) $shop_id;
					if (!$position = Db::getInstance()->getValue($sql)) {
						$position = 0;
					}

					$return &= Db::getInstance()->insert(
						'hook_module',
						array(
							'id_module' => (int) $id_module,
							'id_hook'   => (int) $id_hook,
							'id_shop'   => (int) $shop_id,
							'position'  => (int) ($position + 1),
						)
					);
				}
			}
			return $return;
		} else {
			return false;
		}
	}

	protected function regenerateform()
	{
		$default_lang                 = (int) Configuration::get('PS_LANG_DEFAULT');
		$this->fields_form = array();
		$this->fields_form[0]['type'] = 'normal';
		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->trans('Blog Thumblr Configuration', [], 'Modules.Vecblog.Vecblog'),
			),
			'input'  => array(
				array(
					'type'     => 'switch',
					'label'    => $this->trans('Delete Old Thumblr', [], 'Modules.Vecblog.Vecblog'),
					'name'     => 'isdeleteoldthumblr',
					'required' => false,
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active_on',
							'value' => 1,
							'label' => $this->trans('Enabled', [], 'Modules.Vecblog.Vecblog'),
						),
						array(
							'id'    => 'active_off',
							'value' => 0,
							'label' => $this->trans('Disabled', [], 'Modules.Vecblog.Vecblog'),
						),
					),
				),
			),
			'submit' => array(
				'title' => $this->trans('Re Generate Thumblr', [], 'Modules.Vecblog.Vecblog'),
			),
		);

		$helper                  = new HelperForm();
		$helper->module          = $this;
		$helper->name_controller = $this->name;
		$helper->token           = Tools::getAdminTokenLite('AdminModules');
		foreach (Language::getLanguages(false) as $lang) {
			$helper->languages[] = array(
				'id_lang'    => $lang['id_lang'],
				'iso_code'   => $lang['iso_code'],
				'name'       => $lang['name'],
				'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0),
			);
		}
		$helper->currentIndex                       = AdminController::$currentIndex . '&configure=' . $this->name;
		$helper->default_form_language              = $default_lang;
		$helper->allow_employee_form_lang           = $default_lang;
		$helper->toolbar_scroll                     = true;
		$helper->show_toolbar                       = false;
		$helper->submit_action                      = 'generateimage';
		$helper->fields_value['isdeleteoldthumblr'] = Configuration::get('isdeleteoldthumblr');
		return $helper;
	}

	public function SettingForm()
	{
		$this->blog_url = self::GetSmartBlogLink('module-smartblog-list');
		//RENDER FIELDS
        include_once(dirname(__FILE__) . '/fields_array.php'); 

		$default_lang                 = (int) Configuration::get('PS_LANG_DEFAULT');
		
		$helper                  = new HelperForm();
		$helper->module          = $this;
		$helper->name_controller = $this->name;
		$helper->token           = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex    = AdminController::$currentIndex . '&configure=' . $this->name;
		foreach (Language::getLanguages(false) as $lang) {
			$helper->languages[] = array(
				'id_lang'    => $lang['id_lang'],
				'iso_code'   => $lang['iso_code'],
				'name'       => $lang['name'],
				'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0),
			);
		}
		$helper->toolbar_btn              = array(
			'save' =>
			array(
				'desc' => $this->trans('Save', [], 'Modules.Smartblog.Smartblog'),
				'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name . 'token=' . Tools::getAdminTokenLite('AdminModules'),
			),
		);
		$helper->default_form_language    = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		$helper->title                    = $this->displayName;
		$helper->show_toolbar             = true;
		$helper->toolbar_scroll           = true;
		$helper->submit_action            = 'save' . $this->name;
		$helper->fields_value['smartpostperpage']        = Configuration::get('smartpostperpage');
		$helper->fields_value['smartpostperrow']        = Configuration::get('smartpostperrow');
		$helper->fields_value['sborderby']        		 = Configuration::get('sborderby');
		$helper->fields_value['sborder']        		 = Configuration::get('sborder');
		$helper->fields_value['smartdataformat']         = Configuration::get('smartdataformat');
		$helper->fields_value['smartacceptcomment']      = Configuration::get('smartacceptcomment');
		$helper->fields_value['smartshowauthorstyle']    = Configuration::get('smartshowauthorstyle');
		$helper->fields_value['smartshowauthor']         = Configuration::get('smartshowauthor');
		$helper->fields_value['smartmainblogurl']        = Configuration::get('smartmainblogurl');
		$helper->fields_value['smartusehtml']            = Configuration::get('smartusehtml');
		$helper->fields_value['smartshowcolumn']         = Configuration::get('smartshowcolumn');
		$helper->fields_value['smartblogmetakeyword']    = Configuration::get('smartblogmetakeyword');
		$helper->fields_value['smartblogmetatitle']      = Configuration::get('smartblogmetatitle');
		$helper->fields_value['smartblogmetadescrip']    = Configuration::get('smartblogmetadescrip');
		$helper->fields_value['smartshowviewed']         = Configuration::get('smartshowviewed');
		$helper->fields_value['smartdisablecatimg']      = Configuration::get('smartdisablecatimg');
		$helper->fields_value['smartenablecomment']      = Configuration::get('smartenablecomment');
		$helper->fields_value['smartenableguestcomment'] = Configuration::get('smartenableguestcomment');
		$helper->fields_value['smartshownoimg']          = Configuration::get('smartshownoimg');
		$helper->fields_value['smartsearchengine']       = Configuration::get('smartsearchengine');
		$helper->fields_value['smartcaptchaoption']      = Configuration::get('smartcaptchaoption');
		$helper->fields_value['smartblogurlpattern']     = Configuration::get('smartblogurlpattern');
		$helper->fields_value['smartstyle']     		 = Configuration::get('smartstyle');
		return $helper;
	}

	public static function GetSmartBlogUrl()
	{
		$ssl_enable       = Configuration::get('PS_SSL_ENABLED');
		$id_lang          = (int) Context::getContext()->language->id;
		$id_shop          = (int) Context::getContext()->shop->id;
		$rewrite_set      = (int) Configuration::get('PS_REWRITING_SETTINGS');
		$ssl              = null;
		static $force_ssl = null;
		if ($ssl === null) {
			if ($force_ssl === null) {
				$force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
			}
			$ssl = $force_ssl;
		}
		if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') && $id_shop !== null) {
			$shop = new Shop($id_shop);
		} else {
			$shop = Context::getContext()->shop;
		}
		$base    = ($ssl == 1 && $ssl_enable == 1) ? 'https://' . $shop->domain_ssl : 'http://' . $shop->domain;
		$langUrl = Language::getIsoById($id_lang) . '/';
		if ((!$rewrite_set && in_array($id_shop, array((int) Context::getContext()->shop->id, null))) || !Language::isMultiLanguageActivated($id_shop) || !(int) Configuration::get('PS_REWRITING_SETTINGS', null, null, $id_shop)) {
			$langUrl = '';
		}

		return $base . $shop->getBaseURI() . $langUrl;
	}

	public static function GetSmartBlogLink($rewrite = 'smartblog', $params = null, $id_shop = null, $id_lang = null)
	{
		$url          = self::GetSmartBlogUrl();
		$dispatcher   = Dispatcher::getInstance();
		$id_lang      = (int) Context::getContext()->language->id;

		$force_routes = (bool) Configuration::get('PS_REWRITING_SETTINGS');
		if (Tools::isSubmit('savesmartblog')) {
			$usehtml = (int) Configuration::get('smartusehtml');
			if ($usehtml != 0) {
				$html = '.html';
			} else {
				$html = '';
			}
			return $url . Tools::getvalue('smartmainblogurl') . $html;
		}

		if ($params != null) {
			return $url . $dispatcher->createUrl($rewrite, $id_lang, $params, $force_routes);
		} else {
			$params = array();
			return $url . $dispatcher->createUrl($rewrite, $id_lang, $params, $force_routes);
		}
	}

	public function hookModuleRoutes($params)
	{
		$alias   = Configuration::get('smartmainblogurl');
		$usehtml = (int) Configuration::get('smartusehtml');
		if ($usehtml != 0) {
			$html = '.html';
		} else {
			$html = '';
		}
		$smartblogurlpattern = (int) Configuration::get('smartblogurlpattern');
		$my_link = array();
		$is_crazy_admin = Tools::getValue('hook');
		if($is_crazy_admin == 'extended'){

			$my_link = $this->urlPatterWithIdOne($alias, $html);

		}else{
			switch ($smartblogurlpattern) {
				case 1:
					$my_link = $this->urlPatterWithoutId($alias, $html);
					break;
				case 2:
					$my_link = $this->urlPatterWithIdOne($alias, $html);
					break;
	
				default:
					$my_link = $this->urlPatterWithIdOne($alias, $html);
			}
		}
		return $my_link;
	}

	public function urlPatterWithoutId($alias, $html)
	{
		$my_link = array(
			'module-smartblog-list'                    => array(
				'controller' => 'list',
				'rule'       => $alias . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_list'                => array(
				'controller' => 'category',
				'rule'       => $alias . '/category' . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_list_module'         => array(
				'controller' => 'category',
				'rule'       => 'module/' . $alias . '/category' . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_list_pagination'     => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_pagination'          => array(
				'controller' => 'categorypage',
				'rule'       => $alias . '/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_category_rule'       => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/{slug}' . $html,
				'keywords'   => array(
					'id_category'   => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'slug'          => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'slug',
					),
					'meta_keywords' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title'    => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-category'            => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/{slug}' . $html,
				'keywords'   => array(
					'id_category'   => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'slug'          => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'slug',
					),
					'meta_keywords' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title'    => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-categorypage'            => array(
				'controller' => 'categorypage',
				'rule'       => $alias . '/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_category_pagination' => array(
				'controller' => 'categorypage',
				'rule'       => $alias . '/category/{slug}/page/{page}' . $html,
				'keywords'   => array(
					'id_category' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'page'        => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
					'slug'        => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'slug',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_cat_page_mod'        => array(
				'controller' => 'category',
				'rule'       => 'module/' . $alias . '/category/{slug}/page/{page}' . $html,
				'keywords'   => array(
					'id_category' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'page'        => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
					'slug'        => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'slug',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_search'              => array(
				'controller' => 'search',
				'rule'       => $alias . '/search',
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-tagpost'                 => array(
				'controller' => 'tagpost',
				'rule'       => $alias . '/tag/{tag}' . $html,
				'keywords'   => array(
					'tag' => array(
						'regexp' => '[_a-zA-Z0-9-\pL\+]*',
						'param'  => 'tag',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_tag'                 => array(
				'controller' => 'tagpost',
				'rule'       => $alias . '/tag/{tag}' . $html,
				'keywords'   => array(
					'tag' => array(
						'regexp' => '[_a-zA-Z0-9-\pL\+]*',
						'param'  => 'tag',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_search_pagination'   => array(
				'controller' => 'search',
				'rule'       => $alias . '/search/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_post_rule'           => array(
				'controller' => 'details',
				'rule'       => $alias . '/{slug}' . $html,
				'keywords'   => array(
					'id_post'       => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_post',
					),
					'slug'          => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'slug',
					),
					'meta_keywords' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title'    => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-details'                => array(
				'controller' => 'details',
				'rule'       => $alias . '/{slug}' . $html,
				'keywords'   => array(

					'slug'          => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'slug',
					),
					'meta_keywords' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title'    => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-archivemonth'             => array(
				'controller' => 'archivemonth',
				'rule'       => $alias . '/archive/{year}/{month}' . $html,
				'keywords'   => array(
					'year' => array(
						'regexp' => '',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '',
						'param'  => 'month',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-archive'             => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}' . $html,
				'keywords'   => array(
					'year' => array(
						'regexp' => '',
						'param'  => 'year',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_archive_pagination'  => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_month'               => array(
				'controller' => 'archivemonth',
				'rule'       => $alias . '/archive/{year}/{month}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_month_pagination'    => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}/page/{page}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
					'page'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_day'                 => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}/{day}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
					'day'   => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'day',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_day_pagination'      => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}/{day}/page/{page}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
					'day'   => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'day',
					),
					'page'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_year'                => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}' . $html,
				'keywords'   => array(
					'year' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_year_pagination'     => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/page/{page}' . $html,
				'keywords'   => array(
					'year' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
		);
		return $my_link;
	}

	public function urlPatterWithIdOne($alias, $html)
	{
		$my_link = array(
			'module-smartblog-list'        => array(
				'controller' => 'list',
				'rule'       => $alias . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_list'                => array(
				'controller' => 'category',
				'rule'       => $alias . '/category' . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_list_module'         => array(
				'controller' => 'category',
				'rule'       => 'module/' . $alias . '/category' . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_list_pagination'     => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_pagination'          => array(
				'controller' => 'category',
				'rule'       => $alias . '/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'module-smartblog-category'            => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/{slug}' . $html,
				'keywords'   => array(

					'slug'        => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_category_rule'       => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/{id_category}_{slug}' . $html,
				'keywords'   => array(
					'id_category' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'slug'        => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_category_pagination' => array(
				'controller' => 'category',
				'rule'       => $alias . '/category/{id_category}_{slug}/page/{page}' . $html,
				'keywords'   => array(
					'id_category' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'page'        => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
					'slug'        => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_cat_page_mod'        => array(
				'controller' => 'category',
				'rule'       => 'module/' . $alias . '/category/{id_category}_{slug}/page/{page}' . $html,
				'keywords'   => array(
					'id_category' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_category',
					),
					'page'        => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
					'slug'        => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_search'              => array(
				'controller' => 'search',
				'rule'       => $alias . '/search' . $html,
				'keywords'   => array(),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_tag'                 => array(
				'controller' => 'tagpost',
				'rule'       => $alias . '/tag/{tag}' . $html,
				'keywords'   => array(
					'tag' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'tag',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_search_pagination'   => array(
				'controller' => 'search',
				'rule'       => $alias . '/search/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_post'                => array(
				'controller' => 'details',
				'rule'       => $alias . '/{id_post}_{slug}' . $html,
				'keywords'   => array(
					'id_post' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_post',
					),
					'slug'    => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),


			'smartblog_post_rule'           => array(
				'controller' => 'details',
				'rule'       => $alias . '/{id_post}_{slug}' . $html,
				'keywords'   => array(
					'id_post' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'id_post',
					),
					'slug'    => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),

			'smartblog_archive_pagination'  => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/page/{page}' . $html,
				'keywords'   => array(
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_month'               => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_month_pagination'    => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}/page/{page}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
					'page'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_day'                 => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}/{day}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
					'day'   => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'day',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_day_pagination'      => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/{month}/{day}/page/{page}' . $html,
				'keywords'   => array(
					'year'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'month' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'month',
					),
					'day'   => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'day',
					),
					'page'  => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_year'                => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}' . $html,
				'keywords'   => array(
					'year' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
			'smartblog_year_pagination'     => array(
				'controller' => 'archive',
				'rule'       => $alias . '/archive/{year}/page/{page}' . $html,
				'keywords'   => array(
					'year' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'year',
					),
					'page' => array(
						'regexp' => '[_a-zA-Z0-9-\pL]*',
						'param'  => 'page',
					),
				),
				'params'     => array(
					'fc'     => 'module',
					'module' => 'smartblog',
				),
			),
		);
		return $my_link;
	}

	public static function displayDate($date, $id_lang = null, $full = false, $separator = null)
	{
		if ($id_lang !== null) {
			Tools::displayParameterAsDeprecated('id_lang');
		}
		if ($separator !== null) {
			Tools::displayParameterAsDeprecated('separator');
		}

		if (!$date || !($time = strtotime($date))) {
			return $date;
		}

		if ($date == '0000-00-00 00:00:00' || $date == '0000-00-00') {
			return '';
		}

		if (!Validate::isDate($date) || !Validate::isBool($full)) {
			throw new PrestaShopException('Invalid date');
		}

		$date_format = ($full ? Configuration::get('smartdataformat') : Configuration::get('smartdataformat'));
		return date($date_format, $time);
	}

	public static function smartblogthemelist()
	{
		$directory = _PS_MODULE_DIR_ . 'smartblog/views/templates/front/themes/';
		if ( !is_dir( $directory ) ) {
			return false;       
		}
		$scanned_directory_theme = array_diff($files = preg_grep('/^([^.])/', scandir($directory)), array('..', '.'));
		sort($scanned_directory_theme);
		$directory_theme = _PS_THEME_DIR_ . "modules/smartblog/views/templates/front/themes/";
		if (is_dir($directory_theme)) {
			$scanned_directory_theme_theme = array_diff($files = preg_grep('/^([^.])/', scandir($directory_theme)), array('..', '.'));
			sort($scanned_directory_theme_theme);
			$scanned_directory_theme = array_merge($scanned_directory_theme, $scanned_directory_theme_theme);
		}
		$directory_p_theme = _PS_PARENT_THEME_DIR_ . "modules/smartblog/views/templates/front/themes/";
		if (is_dir($directory_p_theme)) {
			$scanned_directory_theme_p_theme = array_diff($files = preg_grep('/^([^.])/', scandir($directory_p_theme)), array('..', '.'));
			sort($scanned_directory_theme_p_theme);
			$scanned_directory_theme = array_merge($scanned_directory_theme, $scanned_directory_theme_p_theme);
		}
		$scanned_directory_theme = array_unique($scanned_directory_theme);
		$returnArray = [];
		foreach ($scanned_directory_theme as $key => $theme) {
			$returnArray[$key]['lab'] = ucfirst($theme);
			$returnArray[$key]['val'] = $theme;
		}
		return $returnArray;
	}

	public static function getOrderBylist()
	{
		$options = array(
			array(
				'id_orderby' => "id_smart_blog_post", 
				'orderby' => 'Blog Id' 
			),
			array(
			  'id_orderby' => "name", 
			  'orderby' => 'Name' 
			),
			array(
				'id_orderby' => "created", 
				'orderby' => 'Date Created' 
			),
			array(
				'id_orderby' => "viewed", 
				'orderby' => 'Popularity (Based on views)' 
			),
		);
		
		return $options;
	}


	public static function categoryslug2id($slug)
	{
		$sql = 'SELECT p.id_smart_blog_category 
                FROM `' . _DB_PREFIX_ . 'smart_blog_category_lang` p 
                WHERE p.link_rewrite =  "' . pSQL($slug) . '"';

		if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
			return false;
		}
		return $result[0]['id_smart_blog_category'];
	}

	public static function slug2id($slug)
	{
		$sql = 'SELECT p.id_smart_blog_post 
                FROM `' . _DB_PREFIX_ . 'smart_blog_post_lang` p 
                WHERE p.link_rewrite =  "' . pSQL($slug) . '"';

		if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
			return false;
		}
		return $result[0]['id_smart_blog_post'];
	}



	public function smartblogcategoriesHookLeftColumn($params)
	{

		if (!$thiscn->isCached('plugins/smartblogcategories.tpl')) {
			$view_data    = array();
			$id_lang      = $this->context->language->id;
			$BlogCategory = new BlogCategory();
			$categories   = $BlogCategory->getCategory(1, $id_lang);
			$i            = 0;
			foreach ($categories as $category) {
				$categories[$i]['count'] = $BlogCategory->getPostByCategory($category['id_smart_blog_category']);
				$i++;
			}
			$protocol_link    = (Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
			$protocol_content = (isset($useSSL) and $useSSL and Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
			$smartbloglink = new SmartBlogLink($protocol_link, $protocol_content);
			$this->smarty->assign(
				array(
					'smartbloglink' => $smartbloglink,
					'categories'    => $categories,
				)
			);
		}
		return $this->display(__FILE__, 'views/templates/front/plugins/smartblogcategories.tpl');
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



	public function hookactionsbdeletepost($params)
	{
		return $this->DeleteCache();
	}

	public function hookactionsbnewpost($params)
	{
		return $this->DeleteCache();
	}

	public function hookactionsbupdatepost($params)
	{
		return $this->DeleteCache();
	}

	public function hookactionsbtogglepost($params)
	{
		return $this->DeleteCache();
	}

	public function DeleteCache()
	{
		$this->_clearCache('plugins/smartblogcategories.tpl');
		$this->_clearCache('plugins/smartblog_latest_news.tpl');
		$this->_clearCache('plugins/smartblogrelatedproduct.tpl');
	}

	public function smartblogrelatedproductHookdisplayProductTab($params)
	{
		return $this->display(__FILE__, 'views/templates/front/plugins/smartproduct_tab.tpl');
	}

	public function hookdisplayProductTab($params)
	{
		return $this->smartblogrelatedproductHookdisplayProductTab($params);
	}

	public function smartblogrelatedproductHookdisplayProductTabContent($params)
	{
		$id_lang = $this->context->language->id;
		$posts   = SmartBlogPost::getRelatedPostsByProduct($id_lang, Tools::getvalue('id_product'));
		$this->smarty->assign(
			array(
				'posts' => $posts,
			)
		);
		return $this->display(__FILE__, 'views/templates/front/plugins/smart_product_tab_creator.tpl');
	}

	public function hookdisplayProductTabContent($params)
	{
		return $this->smartblogrelatedproductHookdisplayProductTabContent($params);
	}
}