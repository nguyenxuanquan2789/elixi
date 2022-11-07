<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace VEC;

defined('_PS_VERSION_') or die;

class WidgetSiteLogo extends WidgetImage
{
    public function getName()
    {
        return 'theme-site-logo';
    }

    public function getTitle()
    {
        return __('Site Logo');
    }

    public function getIcon()
    {
        return 'eicon-site-logo';
    }

    public function getCategories()
    {
        return ['theme-elements', 'maintenance-theme-elements'];
    }

    public function getKeywords()
    {
        return ['site', 'logo', 'branding'];
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl(
            'image',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => [
                    'id' => '',
                    'url' => 'img/' . \Configuration::get('PS_LOGO'),
                ],
            ]
        );

        $this->updateControl(
            'link_to',
            [
                'options' => [
                    'none' => __('None'),
                    'custom' => __('Site URL'),
                ],
                'default' => 'custom',
            ]
        );

        $context = \Context::getContext();

        $this->updateControl(
            'link',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => [
                    'url' => $context->link->getPageLink('index', true, $context->language->id, null, false, $context->shop->id, true),
                    'is_external' => false,
                ],
            ]
        );

        $this->removeControl('caption');
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-' . parent::getName();
    }
}
