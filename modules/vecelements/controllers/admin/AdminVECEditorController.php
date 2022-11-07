<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

class AdminVECEditorController extends ModuleAdminController
{
    public $name = 'AdminVECEditor';

    public $display_header = false;

    public $content_only = true;

    /** @var VEC\UId */
    protected $uid;

    public function initShopContext()
    {
        require_once _PS_MODULE_DIR_ . 'vecelements/classes/wrappers/UId.php';

        Tools::getIsset('uid') && $this->uid = VEC\UId::parse(Tools::getValue('uid'));

        if (!empty($this->uid->id_shop) && $this->uid->id_type > VEC\UId::TEMPLATE && Shop::getContext() > 1) {
            ${'_POST'}['setShopContext'] = 's-' . $this->uid->id_shop;
        }
        parent::initShopContext();
    }

    public function init()
    {
        if (isset($this->context->cookie->last_activity)) {
            if ($this->context->cookie->last_activity + 900 < time()) {
                $this->context->employee->logout();
            } else {
                $this->context->cookie->last_activity = time();
            }
        }

        if (!isset($this->context->employee) || !$this->context->employee->isLoggedBack()) {
            if (isset($this->context->employee)) {
                $this->context->employee->logout();
            }
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminLogin') . '&redirect=' . $this->controller_name);
        }
        $this->initProcess();
    }

    public function initCursedPage()
    {
        if ($this->ajax) {
            VEC\wp_send_json_error('token_expired');
        }
        parent::initCursedPage();
    }

    public function initProcess()
    {
        header('Cache-Control: no-store, no-cache');

        $this->ajax = Tools::getIsset('ajax');
        $this->action = Tools::getValue('action', '');
        $this->tabAccess = Profile::getProfileAccess($this->context->employee->id_profile, $this->id);

        if (Shop::isFeatureActive() && $this->uid && !$this->ajax) {
            $domain = Tools::getShopProtocol() === 'http://' ? 'domain' : 'domain_ssl';

            if ($this->context->shop->$domain != $_SERVER['HTTP_HOST'] && $this->viewAccess()) {
                VEC\update_post_meta(0, 'cookie', $this->context->cookie->getAll());

                $id_shop = $this->uid->id_shop ? $this->uid->id_shop : $this->uid->getDefaultShopId();

                Tools::redirectAdmin(
                    $this->context->link->getModuleLink('vecelements', 'preview', [
                        'id_employee' => $this->context->employee->id,
                        'adtoken' => Tools::getAdminTokenLite('AdminVECEditor'),
                        'redirect' => urlencode($_SERVER['REQUEST_URI']),
                    ], true, $this->uid->id_lang, $id_shop)
                );
            }
        }
        VEC\Plugin::instance();
    }

    public function postProcess()
    {
        $process = 'process' . Tools::toCamelCase($this->action, true);

        if ($this->ajax) {
            method_exists($this, "ajax$process") && $this->{"ajax$process"}();

            if ('elementor_ajax' === $this->action) {
                VEC\add_action('elementor/ajax/register_actions', [$this, 'registerAjaxActions']);
            }
            VEC\do_action('wp_ajax_' . $this->action);
        } elseif ($this->action && method_exists($this, $process)) {
            // Call process
            return $this->$process();
        }

        return false;
    }

    public function initContent()
    {
        $this->viewAccess() or die(VEC\Helper::transError('You do not have permission to view this.'));

        empty($this->uid) && Tools::redirectAdmin($this->context->link->getAdminLink('AdminVECContent'));
        
        VEC\add_action('elementor/editor/before_enqueue_scripts', [$this, 'beforeEnqueueScripts']);

        VEC\Plugin::instance()->editor->init();
    }

    public function beforeEnqueueScripts()
    {
        $suffix = _PS_MODE_DEV_ ? '' : '.min';

        // Enqueue CE assets
        VEC\wp_enqueue_style('ce-editor', _VEC_ASSETS_URL_ . 'css/editor-ce.css', [], _VEC_VERSION_);
        VEC\wp_register_script('ce-editor', _VEC_ASSETS_URL_ . 'js/editor-ce.js', [], _VEC_VERSION_, true);
        VEC\wp_localize_script('ce-editor', 'ce', [
            'wrapfix' => VEC\Helper::getWrapfix(),
        ]);
        VEC\wp_localize_script('ce-editor', 'baseDir', __PS_BASE_URI__);
        VEC\wp_enqueue_script('ce-editor');

        // Enqueue TinyMCE assets
        VEC\wp_enqueue_style('material-icons', _VEC_ASSETS_URL_ . 'lib/material-icons/material-icons.css', [], '1.011');
        VEC\wp_enqueue_style('tinymce-theme', _VEC_ASSETS_URL_ . "lib/tinymce/ps-theme{$suffix}.css", [], _VEC_VERSION_);

        VEC\wp_register_script('tinymce', _PS_JS_DIR_ . 'tiny_mce/tinymce.min.js', ['jquery'], false, true);
        VEC\wp_register_script('tinymce-inc', _VEC_ASSETS_URL_ . 'lib/tinymce/tinymce.inc.js', ['tinymce'], _VEC_VERSION_, true);

        VEC\wp_localize_script('tinymce', 'baseAdminDir', __PS_BASE_URI__ . basename(_PS_ADMIN_DIR_) . '/');
        VEC\wp_localize_script('tinymce', 'iso_user', VEC\get_locale());

        VEC\wp_enqueue_script('tinymce-inc');

    }

    public function processBackToPsEditor()
    {
        if (VEC\current_user_can('edit', $this->uid)) {
            VEC\Plugin::instance()->db->setIsElementorPage($this->uid, false);
        }
        Tools::redirectAdmin($_SERVER['HTTP_REFERER']);
    }

    public function processAddFooterProduct()
    {
        if (!$this->uid->id || $this->uid->id_type != VEC\UId::PRODUCT) {
            Tools::redirectAdmin($_SERVER['HTTP_REFERER']);
        }

        $content = new VECContent();
        $content->hook = 'displayFooterProduct';
        $content->id_product = $this->uid->id;
        $content->active = true;
        $content->title = 'Product Footer #' . $this->uid->id;;
        $content->content = [];

        $content->save();

        $uid = new VEC\UId($content->id, VEC\UId::CONTENT, $this->uid->id_lang, $this->uid->id_shop);

        Tools::redirectAdmin(
            $this->context->link->getAdminLink('AdminVECEditor') . "&uid=$uid&footerProduct={$content->id_product}"
        );
    }

    public function processAddMaintenance()
    {
        if (!$uid = Tools::getValue('uid')) {
            Tools::redirectAdmin($_SERVER['HTTP_REFERER']);
        }

        $content = new VECContent();
        $content->hook = 'displayMaintenance';
        $content->active = true;
        $content->title = 'Maintenance';
        $content->content = [];

        foreach (Language::getLanguages(false) as $lang) {
            $id_lang = $lang['id_lang'];

            $content->content[$id_lang] = (string) Configuration::get('PS_MAINTENANCE_TEXT', $id_lang);
        }
        $content->save();

        $id_lang = Tools::substr($uid, -4, 2);
        $id_shop = Tools::substr($uid, -2);
        $uid = new VEC\UId($content->id, VEC\UId::CONTENT, $id_lang, $id_shop);

        Tools::redirectAdmin($this->context->link->getAdminLink('AdminVECEditor') . "&uid=$uid");
    }

    public function ajaxProcessHeartbeat()
    {
        $response = [];
        $data = isset(${'_POST'}['data']) ? (array) ${'_POST'}['data'] : [];
        $screen_id = Tools::getValue('screen_id', 'front');

        empty($data) or $response = VEC\apply_filters('heartbeat_received', $response, $data, $screen_id);

        $response = VEC\apply_filters('heartbeat_send', $response, $screen_id);

        VEC\do_action('heartbeat_tick', $response, $screen_id);

        $response['server_time'] = time();

        VEC\wp_send_json($response);
    }

    public function ajaxProcessAutocompleteLink()
    {
        $context = Context::getContext();
        $db = Db::getInstance();
        $ps = _DB_PREFIX_;
        $search = $db->escape(Tools::getValue('search'));
        $id_lang = (int) $context->language->id;
        $id_shop = (int) $context->shop->id;
        $limit = 10;

        $rows = $db->executeS("(
            SELECT m.`id_meta` AS `ID`, ml.`title`, m.`page` AS `permalink`, 'Page' AS `info` FROM `{$ps}meta` AS m
            LEFT JOIN `{$ps}meta_lang` AS ml ON m.`id_meta` = ml.`id_meta`
            WHERE ml.`id_lang` = $id_lang AND ml.`id_shop` = $id_shop AND ml.`title` LIKE '%$search%' LIMIT $limit
        ) UNION (
            SELECT `id_cms` AS `ID`, `meta_title` AS `title`, `link_rewrite` AS `permalink`, 'CMS' AS `info` FROM `{$ps}cms_lang`
            WHERE `id_lang` = $id_lang AND `id_shop` = $id_shop AND `meta_title` LIKE '%$search%' LIMIT $limit
        ) UNION (
            SELECT `id_cms_category` AS `ID`, `name` AS `title`, `link_rewrite` AS `permalink`, 'CMS Category' AS `info` FROM `{$ps}cms_category_lang`
            WHERE `id_lang` = $id_lang AND `id_shop` = $id_shop AND `name` LIKE '%$search%' LIMIT $limit
        ) UNION (
            SELECT `id_product` AS `ID`, `name` AS `title`, '' AS `permalink`, 'Product' AS `info` FROM `{$ps}product_lang`
            WHERE `id_lang` = $id_lang AND `id_shop` = $id_shop AND `name` LIKE '%$search%' LIMIT $limit
        ) UNION (
            SELECT `id_category` AS `ID`, `name` AS `title`, `link_rewrite` AS `permalink`, 'Category' AS `info` FROM `{$ps}category_lang`
            WHERE `id_lang` = $id_lang AND `id_shop` = $id_shop AND `name` LIKE '%$search%' LIMIT $limit
        ) UNION (
            SELECT `id_manufacturer` AS `ID`, `name` AS `title`, '' AS `permalink`, 'Brand' AS `info` FROM `{$ps}manufacturer`
            WHERE `active` = 1 AND `name` LIKE '%$search%' LIMIT $limit
        ) UNION (
            SELECT `id_supplier` AS `ID`, `name` AS `title`, '' AS `permalink`, 'Supplier' AS `info` FROM `{$ps}supplier`
            WHERE `active` = 1 AND `name` LIKE '%$search%' LIMIT $limit
        )");

        if ($rows) {
            foreach ($rows as &$row) {
                switch ($row['info']) {
                    case 'CMS':
                        $row['permalink'] = $context->link->getCMSLink($row['ID'], $row['permalink'], null, $id_lang, $id_shop);
                        break;
                    case 'CMS Category':
                        $row['permalink'] = $context->link->getCMSCategoryLink($row['ID'], $row['permalink'], $id_lang, $id_shop);
                        break;
                    case 'Product':
                        $product = new Product($row['ID'], false, $id_lang, $id_shop);
                        $row['permalink'] = $context->link->getProductLink($product);
                        break;
                    case 'Category':
                        $row['permalink'] = $context->link->getCategoryLink($row['ID'], $row['permalink'], $id_lang, null, $id_shop);
                        break;
                    case 'Brand':
                        $row['permalink'] = $context->link->getManufacturerLink($row['ID'], Tools::link_rewrite($row['title']), $id_lang, $id_shop);
                        break;
                    case 'Supplier':
                        $row['permalink'] = $context->link->getSupplierLink($row['ID'], Tools::link_rewrite($row['title']), $id_lang, $id_shop);
                        break;
                    default:
                        $row['permalink'] = $context->link->getPageLink($row['permalink'], null, $id_lang, null, false, $id_shop);
                        break;
                }
                $row['info'] = VEC\__($row['info']);
            }
        }
        die(json_encode($rows));
    }

    public function registerAjaxActions($ajax_manager)
    {
        $ajax_manager->registerAjaxAction('get_language_content', [$this, 'ajaxGetLanguageContent']);
        $ajax_manager->registerAjaxAction('get_products_by_id', [$this, 'ajaxGetProductsById']);

        VEC\add_filter('elementor/api/get_templates/body_args', [$this, 'filterApiGetTemplateArgs']);
        VEC\add_filter('elementor/api/get_templates/content', [$this, 'filterApiGetTemplateContent']);
        VEC\add_action('elementor/document/after_save', [$this, 'onAfterSaveDocument']);
    }

    public function ajaxGetLanguageContent($request)
    {
        $data = null;

        if (!empty($request['uid']) && $data = VEC\get_post_meta($request['uid'], '_elementor_data', true)) {
            VEC\Plugin::$instance->db->iterateData($data, function ($element) {
                $element['id'] = VEC\Utils::generateRandomString();

                return $element;
            });
        }
        return is_array($data) ? $data : [];
    }

    public function ajaxGetProductsById($request)
    {
        if (empty($request['ids'])) {
            return [];
        }
        $context = Context::getContext();
        $results = [];
        $ids = implode(',', array_map('intval', $request['ids']));

        $items = Db::getInstance()->executeS('
            SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`, image_shop.`id_image` id_image FROM `' . _DB_PREFIX_ . 'product` p
            ' . Shop::addSqlAssociation('product', 'p') . '
            LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (pl.id_product = p.id_product AND pl.id_lang = ' . (int) $context->language->id . Shop::addSqlRestrictionOnLang('pl') . ')
            LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
                ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $context->shop->id . ')
            LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $context->language->id . ')
            WHERE p.id_product IN (' . $ids . ') GROUP BY p.id_product
        ');

        if ($items) {
            $protocol = Tools::getShopProtocol();
            $image_size = ImageType::{'getFormattedName'}('home');

            foreach ($items as &$item) {
                $results[] = [
                    'id' => $item['id_product'],
                    'name' => $item['name'] . (!empty($item['reference']) ? ' (ref: ' . $item['reference'] . ')' : ''),
                    'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                    'image' => str_replace('http://', $protocol, $context->link->getImageLink($item['link_rewrite'], $item['id_image'], $image_size)),
                ];
            }
        }
        return $results;
    }

    public function onAfterSaveDocument($document)
    {
        // Set edit mode to builder only at save
        VEC\Plugin::$instance->db->setIsElementorPage($document->getPost()->uid);
    }
}
