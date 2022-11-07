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

use VEC\CoreXBaseXDocument as Document;

abstract class CoreXBaseXThemeDocument extends Document
{
    /*
    public static function getPreviewAsDefault()
    {
        return '';
    }

    public static function getPreviewAsOptions()
    {
        return [];
    }
    */
    public static function getProperties()
    {
        $properties = parent::getProperties();

        $properties['show_in_library'] = true;
        $properties['register_type'] = true;

        return $properties;
    }

    public function _getInitialConfig()
    {
        $config = parent::_getInitialConfig();

        $config['library'] = [
            'save_as_same_type' => true,
        ];

        return $config;
    }
    /*
    protected function _registerControls()
    {
        parent::_registerControls();

        $this->startControlsSection(
            'preview_settings',
            [
                'label' => __('Preview Settings'),
                'tab' => ControlsManager::TAB_SETTINGS,
            ]
        );

        $this->addControl(
            'preview_type',
            [
                'label' => __('Preview Dynamic Content as'),
                'label_block' => true,
                'type' => ControlsManager::SELECT,
                'default' => $this::getPreviewAsDefault(),
                'groups' => $this::getPreviewAsOptions(),
                'export' => false,
            ]
        );

        // $this->addControl(
        //     'preview_id',
        //     [
        //         'type' => QueryModule::QUERY_CONTROL_ID,
        //         'label_block' => true,
        //         'filter_type' => '',
        //         'object_type' => '',
        //         'separator' => 'none',
        //         'export' => false,
        //         'condition' => [
        //             'preview_type!' => [
        //                 '',
        //                 'search',
        //             ],
        //         ],
        //     ]
        // );

        $this->addControl(
            'preview_search_term',
            [
                'label' => __('Search Term'),
                'export' => false,
                'condition' => [
                    'preview_type' => 'search',
                ],
            ]
        );

        $this->addControl(
            'apply_preview',
            [
                'type' => ControlsManager::BUTTON,
                'label' => __('Apply & Preview'),
                'label_block' => true,
                'show_label' => false,
                'text' => __('Apply & Preview'),
                'separator' => 'none',
                'event' => 'elementorThemeBuilder:ApplyPreview',
            ]
        );

        $this->endControlsSection();
    }
    */
}
