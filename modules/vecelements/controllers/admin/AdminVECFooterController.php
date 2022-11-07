<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

require_once _PS_MODULE_DIR_ . 'vecelements/classes/VECTheme.php';

class AdminVECFooterController extends ModuleAdminController
{
    public $bootstrap = true;

    public $table = 'vec_theme';

    public $identifier = 'id_vec_theme';

    public $className = 'VECTheme';

    public $lang = true;

    protected $_defaultOrderBy = 'title';

    public function __construct()
    {
        parent::__construct();

        if ((Tools::getIsset('updatevec_theme') || Tools::getIsset('addvec_theme')) && Shop::getContextShopID() === null) {
            $this->displayWarning(
                $this->trans('You are in a multistore context: any modification will impact all your shops, or each shop of the active group.', [], 'Admin.Catalog.Notification')
            );
        }

        $table_shop = _DB_PREFIX_ . $this->table . '_shop';
        $this->_select = 'sa.*';
        $this->_join = "LEFT JOIN $table_shop sa ON sa.id_vec_theme = a.id_vec_theme AND b.id_shop = sa.id_shop";
        $this->_where = "AND a.type = 'footer' AND sa.id_shop = " . (int) $this->context->shop->id;

        $this->fields_list = [
            'id_vec_theme' => [
                'title' => $this->trans('ID', [], 'Admin.Global'),
                'class' => 'fixed-width-xs',
                'align' => 'center',
            ],
            'title' => [
                'title' => $this->trans('Title', [], 'Admin.Global'),
            ],
            'active' => [
                'title' => $this->trans('Active', [], 'Admin.Global'),
                'filter_key' => 'sa!active',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
            ],
        ];

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->trans('Delete selected', [], 'Admin.Notifications.Info'),
                'icon' => 'fa fa-icon-trash',
                'confirm' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Info'),
            ],
        ];

        $this->fields_options['theme_settings'] = [
            'class' => 'ce-theme-panel',
            'icon' => 'icon-cog',
            'title' => $this->l('Theme Settings'),
            'fields' => [
                'CE_FOOTER' => [
                    'title' => $this->l('Footer'),
                    'cast' => 'strval',
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => array_merge(
                        [
                            ['value' => '', 'name' => $this->l('Default')],
                        ],
                        VECTheme::getOptions('footer', $this->context->language->id, $this->context->shop->id)
                    ),
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];
        $this->name = 'AdminVECFooter';
    }

    public function initToolBarTitle()
    {
        $this->page_header_toolbar_title = $this->l('Footer Builder');

        $this->context->smarty->assign('icon', 'icon-list');

        $this->toolbar_title[] = 'add' === $this->display ? $this->l('Add New Template') : ('edit' === $this->display ? $this->l('Edit Template') : $this->l('Footer List'));
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['addvec_theme'] = [
                'href' => self::$currentIndex . '&addvec_theme&token=' . $this->token,
                'desc' => $this->trans('Add new', [], 'Admin.Actions'),
                'icon' => 'process-icon-new',
            ];
        }
        parent::initPageHeaderToolbar();
    }

    public function initModal()
    {
        // Prevent modals
    }

    public function initContent()
    {
        $this->context->smarty->assign('current_tab_level', 3);

        return parent::initContent();
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function renderForm()
    {
        $col = count(Language::getLanguages(false, false, true)) > 1 ? 8 : 7;

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Template'),
                'icon' => 'icon-edit',
            ],
            'input' => [
                [
                    'type' => 'hidden',
                    'name' => 'id_vec_theme',
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Title', [], 'Admin.Global'),
                    'name' => 'title',
                    'col' => 6,
                ],
                [
                    'type' => 'hidden',
                    'name' => 'type',
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->l('Content'),
                    'name' => 'content',
                    'lang' => true,
                    'col' => $col,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Active', [], 'Admin.Global'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', [], 'Admin.Global'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', [], 'Admin.Global'),
                        ],
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Admin.Actions'),
            ],
            'buttons' => [
                'save_and_stay' => [
                    'type' => 'submit',
                    'title' => $this->trans('Save and stay', [], 'Admin.Actions'),
                    'icon' => 'process-icon-save',
                    'name' => 'submitAddvec_themeAndStay',
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = [
                'type' => 'shop',
                'label' => $this->trans('Shop association', [], 'Admin.Global'),
                'name' => 'checkBoxShopAsso',
            ];
        }

        $this->fields_value['type'] = 'footer';
        $this->fields_value['id_employee'] = (int) $this->context->employee->id;
        return parent::renderForm();
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
