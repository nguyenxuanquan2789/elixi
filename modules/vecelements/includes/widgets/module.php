<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

class WidgetModule extends WidgetBase
{
    public function getName()
    {
        return 'ps-widget-module';
    }

    public function getTitle()
    {
        return __('PS Module');
    }

    public function getIcon()
    {
        return 'fa fa-puzzle-piece';
    }

    public function getCategories()
    {
        return ['premium'];
    }

    protected function getModuleOptions()
    {
        $modules = [
            __('- Select Module -'),
        ];
        if (\Context::getContext()->controller instanceof \AdminVECEditorController) {
            $exclude_tabs = [
                'administration',
                'analytics_stats',
                'billing_invoicing',
                'checkout',
                'dashboard',
                'export',
                'emailing',
                'i18n_localization',
                'migration_tools',
                'payments_gateways',
                'payment_security',
                'quick_bulk_update',
                'seo',
                'shipping_logistics',
                'market_place',
            ];
            $table = _DB_PREFIX_ . 'module';
            $rows = \Db::getInstance()->executeS(
                "SELECT m.name FROM $table AS m " . \Shop::addSqlAssociation('module', 'm') .
                " WHERE m.active = 1 AND m.name NOT IN ('vecelements', 'creativepopup', 'layerslider', 'messengerchat')"
            );
            if ($rows) {
                foreach ($rows as &$row) {
                    try {
                        $mod = \Module::getInstanceByName($row['name']);

                        if (!empty($mod->active) && !in_array($mod->tab, $exclude_tabs)) {
                            $modules[$mod->name] = !empty($mod->displayName) ? $mod->displayName : $mod->name;
                        }
                    } catch (\Exception $ex) {
                        // TODO
                    }
                }
            }
        }
        return $modules;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_module',
            [
                'label' => __('Module'),
            ]
        );

        $this->addControl(
            'module',
            [
                'label_block' => true,
                'type' => ControlsManager::SELECT,
                'options' => $this->getModuleOptions(),
                'default' => '0',
            ]
        );

        $this->addControl(
            'hook',
            [
                'label' => __('Hook'),
                'type' => ControlsManager::TEXT,
                'description' => __('Specify the required hook if needed.'),
                'input_list' => [
                    'displayHome',
                    'displayTop',
                    'displayBanner',
                    'displayNav1',
                    'displayNav2',
                    'displayNavFullWidth',
                    'displayTopColumn',
                    'displayLeftColumn',
                    'displayRightColumn',
                    'displayFooterBefore',
                    'displayFooter',
                    'displayFooterAfter',
                    'displayFooterProduct',
                ],
                'condition' => [
                    'module!' => '0',
                ],
            ]
        );

        $this->endControlsSection();
    }

    public static function isInCustomerGroups(\Module $module)
    {
        if (!\Group::isFeatureActive()) {
            return true;
        }

        $context = \Context::getContext();
        $customer = $context->customer;

        if ($customer instanceof \Customer && $customer->isLogged()) {
            $groups = $customer->getGroups();
        } elseif ($customer instanceof \Customer && $customer->isLogged(true)) {
            $groups = [\Configuration::get('PS_GUEST_GROUP')];
        } else {
            $groups = [\Configuration::get('PS_UNIDENTIFIED_GROUP')];
        }

        $table = _DB_PREFIX_ . 'module_group';
        $id_shop = (int) $context->shop->id;
        $id_module = (int) $module->id;
        $id_groups = implode(', ', array_map('intval', $groups));

        return (bool) \Db::getInstance()->getValue(
            "SELECT 1 FROM $table WHERE id_module = $id_module AND id_shop = $id_shop AND id_group IN ($id_groups)"
        );
    }

    protected function renderModule($module, $hook_name, $hook_args = [])
    {
        $res = '';
        try {
            $mod = \Module::getInstanceByName($module);

            if (!empty($mod->active) && self::isInCustomerGroups($mod)) {
                if (method_exists($mod, "hook$hook_name")) {
                    $res = \Hook::coreCallHook($mod, "hook$hook_name", $hook_args);
                } elseif (method_exists($mod, 'renderWidget')) {
                    $res = \Hook::coreRenderWidget($mod, $hook_name, $hook_args);
                }
            }
        } catch (\Exception $ex) {
            // TODO
        }
        return $res;
    }

    protected function render()
    {
        if (is_admin()) {
            return print '<div class="ce-remote-render"></div>';
        }

        $settings = $this->getSettingsForDisplay();

        if ($settings['module']) {
            echo $this->renderModule(
                $settings['module'],
                !empty($settings['hook']) ? $settings['hook'] : 'displayCEWidget'
            );
        }
    }

    public function renderPlainContent()
    {
    }
}
