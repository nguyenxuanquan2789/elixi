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

use VEC\CoreXBaseXModule as BaseModule;

class ModulesXStickyXModule extends BaseModule
{
    public function getName()
    {
        return 'sticky';
    }

    public function registerControls(ElementBase $element)
    {
        $element->addControl(
            'sticky',
            [
                'label' => __('Sticky'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('None'),
                    'top' => __('Top'),
                    'bottom' => __('Bottom'),
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->addControl(
            'sticky_on',
            [
                'label' => __('Sticky On'),
                'type' => ControlsManager::SELECT2,
                'multiple' => true,
                'label_block' => 'true',
                'default' => ['desktop', 'tablet', 'mobile'],
                'options' => [
                    'desktop' => __('Desktop'),
                    'tablet' => __('Tablet'),
                    'mobile' => __('Mobile'),
                ],
                'condition' => [
                    'sticky!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->addControl(
            'sticky_offset',
            [
                'label' => __('Offset'),
                'type' => ControlsManager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 500,
                'required' => true,
                'condition' => [
                    'sticky!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $element->addControl(
            'sticky_effects_offset',
            [
                'label' => __('Effects Offset'),
                'type' => ControlsManager::NUMBER,
                'default' => 0,
                'min' => 0,
                'max' => 1000,
                'required' => true,
                'condition' => [
                    'sticky!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $is_section = $element instanceof ElementSection;

        if ($is_section || $element instanceof WidgetBase) {
            $condition = [
                'sticky!' => '',
            ];

            if ($is_section && Plugin::$instance->editor->isEditMode()) {
                $condition['isInner'] = true;
            }

            $element->addControl(
                'sticky_parent',
                [
                    'label' => __('Stay In Column'),
                    'type' => ControlsManager::SWITCHER,
                    'condition' => $condition,
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );
        }

        $element->addControl(
            'sticky_divider',
            [
                'type' => ControlsManager::DIVIDER,
            ]
        );
    }

    public function __construct()
    {
        add_action('elementor/element/section/section_effects/after_section_start', [$this, 'register_controls']);
        add_action('elementor/element/common/section_effects/after_section_start', [$this, 'register_controls']);
    }
}
