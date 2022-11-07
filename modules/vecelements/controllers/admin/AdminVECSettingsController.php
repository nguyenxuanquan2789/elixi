<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

class AdminVECSettingsController extends ModuleAdminController
{
    protected $clearCss = false;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->className = 'CESettings';
        $this->table = 'configuration';

        parent::__construct();

        $this->fields_options['general_settings'] = [
            'icon' => 'icon-cog',
            'title' => $this->l('General Settings'),
            'fields' => [
                'elementor_css_print_method' => [
                    'title' => $this->l('CSS Print Method'),
                    'desc' => $this->l('Use external CSS files for all generated stylesheets. Choose this setting for better performance (recommended).'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => [
                        ['value' => 'external', 'name' => $this->l('External File')],
                        ['value' => 'internal', 'name' => $this->l('Internal Embedding')],
                    ],
                ],
                'elementor_disable_color_schemes' => [
                    'title' => $this->l('Disable Default Colors'),
                    'desc' => $this->l('If you prefer to inherit the colors from your theme, you can disable this feature.'),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'type' => 'bool',
                    'default' => '0',
                ],
                'elementor_disable_typography_schemes' => [
                    'title' => $this->l('Disable Default Fonts'),
                    'desc' => $this->l('If you prefer to inherit the fonts from your theme, you can disable this feature here.'),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'type' => 'bool',
                    'default' => '0',
                ],

            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];
        
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_btn['regenerate-css'] = [
            'icon' => 'process-icon-reload icon-rotate-right',
            'desc' => $this->l('Regenerate CSS'),
            'js' => '//' . Tools::safeOutput(
                $this->l('Styles set in V-Elements are saved in CSS files. Recreate those files, according to the most recent settings.')
            ),
        ];
        if (Shop::getContext() === Shop::CONTEXT_SHOP) {
            $this->page_header_toolbar_btn['replace-url'] = [
                'icon' => 'process-icon-refresh',
                'desc' => $this->l('Replace URL'),
                'js' => "$('#modal_replace_url').modal()",
            ];
        }

        parent::initPageHeaderToolbar();
    }

    public function initModal()
    {
        $this->modals[] = [
            'modal_id' => 'modal_replace_url',
            'modal_class' => 'modal-md',
            'modal_title' => $this->l('Update Site Address (URL)'),
            'modal_content' => VECSmarty::sprintf(
                _VEC_TEMPLATES_ . 'admin/admin.tpl',
                'ce_modal_replace_url',
                $this->l('It is strongly recommended that you backup your database before using Replace URL.'),
                'http://old-url.com',
                'http://new-url.com',
                $this->l('Enter your old and new URLs for your PrestaShop installation, to update all V-Elements data (Relevant for domain transfers or move to \'HTTPS\').'),
                $this->l('Replace URL')
            ),
        ];
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->css_files[_MODULE_DIR_ . 'vecelements/views/css/settings.css?v=' . _VEC_VERSION_] = 'all';
        $this->js_files[] = _MODULE_DIR_ . 'vecelements/views/js/settings.js?v=' . _VEC_VERSION_;
    }

    protected function processUpdateOptions()
    {
        parent::processUpdateOptions();
        empty($this->clearCss) or VEC\Plugin::instance()->files_manager->clearCache();
    }

    protected function updateOptionElementorPageTitleSelector($val)
    {
        $val = trim($val);

        if (!empty($val) && Validate::isCleanHtml($val)) {
            Configuration::updateValue('elementor_page_title_selector', $val);
        } else {
            $this->errors[] = $this->trans('Required field', [], 'Shop.Forms.Errors') . ': ' . $this->l('Page Title Selector');
        }
    }

    protected function updateOptionElementorFullWidthSelector($val)
    {
        $val = trim($val);

        if (!empty($val) && Validate::isCleanHtml($val)) {
            Configuration::updateValue('elementor_full_width_selector', $val);
        } else {
            $this->errors[] = $this->trans('Required field', [], 'Shop.Forms.Errors') . ': ' . $this->l('Full Width Selector');
        }
    }

    protected function updateOptionElementorCssPrintMethod($val)
    {
        if (Configuration::get('elementor_css_print_method') != $val) {
            Configuration::updateValue('elementor_css_print_method', $val);

            $this->clearCss = true;
        }
    }

    public function ajaxProcessRegenerateCss()
    {
        VEC\Plugin::instance()->files_manager->clearCache();

        VEC\wp_send_json_success();
    }

    public function ajaxProcessReplaceUrl()
    {
        $from = trim(Tools::getValue('from'));
        $to = trim(Tools::getValue('to'));

        $is_valid_urls = filter_var($from, FILTER_VALIDATE_URL) && filter_var($to, FILTER_VALIDATE_URL);

        if (!$is_valid_urls) {
            VEC\wp_send_json_error(VEC\__("The `from` and `to` URL's must be a valid URL"));
        }

        if ($from === $to) {
            VEC\wp_send_json_error(VEC\__("The `from` and `to` URL's must be different"));
        }

        $db = Db::getInstance();
        $table = _DB_PREFIX_ . 'vec_meta';
        $id = sprintf('%02d', $this->context->shop->id);
        $old = str_replace('/', '\\\/', $from);
        $new = str_replace('/', '\\\/', $to);

        $result = $db->execute("
            UPDATE $table SET `value` = REPLACE(`value`, '$old', '$new')
            WHERE `name` = '_elementor_data' AND `id` LIKE '%$id' AND `value` <> '[]'
        ");

        if (false === $result) {
            VEC\wp_send_json_error(VEC\__('An error occurred'));
        } else {
            VEC\wp_send_json_success(sprintf(VEC\__('%d Rows Affected'), $db->affected_rows()));
        }
    }

    protected function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        return empty($this->translator) ? $this->l($id) : parent::trans($id, $parameters, $domain, $locale);
    }

    protected function l($string, $module = 'vecelements', $addslashes = false, $htmlentities = true)
    {
        $str = Translate::getModuleTranslation($module, $string, '', null, $addslashes || !$htmlentities);

        return $htmlentities ? $str : call_user_func('stripslashes', $str);
    }
}
