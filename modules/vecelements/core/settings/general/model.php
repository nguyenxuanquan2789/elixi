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

use VEC\CoreXSettingsXBaseXModel as BaseModel;
use VEC\CoreXSettingsXGeneralXManager as Manager;

/**
 * Elementor global settings model.
 *
 * Elementor global settings model handler class is responsible for registering
 * and managing Elementor global settings models.
 *
 * @since 1.6.0
 */
class CoreXSettingsXGeneralXModel extends BaseModel
{
    /**
     * Get model name.
     *
     * Retrieve global settings model name.
     *
     * @since 1.6.0
     * @access public
     *
     * @return string Model name.
     */
    public function getName()
    {
        return 'global-settings';
    }

    /**
     * Get CSS wrapper selector.
     *
     * Retrieve the wrapper selector for the global settings model.
     *
     * @since 1.6.0
     * @access public
     *
     * @return string CSS wrapper selector.
     */

    public function getCssWrapperSelector()
    {
        return '';
    }

    /**
     * Get panel page settings.
     *
     * Retrieve the panel setting for the global settings model.
     *
     * @since 1.6.0
     * @access public
     *
     * @return array {
     *    Panel settings.
     *
     *    @type string $title The panel title.
     *    @type array  $menu  The panel menu.
     * }
     */
    public function getPanelPageSettings()
    {
        return [
            'title' => __('Global Settings'),
            'menu' => [
                'icon' => 'fa fa-cogs',
                'beforeItem' => 'elementor-settings',
            ],
        ];
    }

    /**
     * Get controls list.
     *
     * Retrieve the global settings model controls list.
     *
     * @since 1.6.0
     * @access public
     * @static
     *
     * @return array Controls list.
     */
    public static function getControlsList()
    {
        return [
            ControlsManager::TAB_STYLE => [
                'style' => [
                    'label' => __('Style'),
                    'controls' => [
                        'elementor_default_generic_fonts' => [
                            'label' => __('Default Generic Fonts'),
                            'type' => ControlsManager::TEXT,
                            'default' => 'Sans-serif',
                            'description' => __('The list of fonts used if the chosen font is not available.'),
                            'label_block' => true,
                        ],
                        'elementor_container_width' => [
                            'label' => __('Content Width') . ' (px)',
                            'type' => ControlsManager::NUMBER,
                            'min' => 300,
                            'description' => __('Sets the default width of the content area (Default: 1140)'),
                            'selectors' => [
                                '.elementor-section.elementor-section-boxed > .elementor-container' => 'max-width: {{VALUE}}px',
                            ],
                        ],
                        'elementor_space_between_widgets' => [
                            'label' => __('Widgets Space') . ' (px)',
                            'type' => ControlsManager::NUMBER,
                            'min' => 0,
                            'placeholder' => '20',
                            'description' => __('Sets the default space between widgets (Default: 20)'),
                            'selectors' => [
                                '.elementor-widget:not(:last-child)' => 'margin-bottom: {{VALUE}}px',
                            ],
                        ],
                        'elementor_stretched_section_container' => [
                            'label' => __('Stretched Section Fit To'),
                            'type' => ControlsManager::TEXT,
                            'placeholder' => 'body',
                            'description' => __('Enter parent element selector to which stretched sections will fit to (e.g. #primary / .wrapper / main etc). Leave blank to fit to page width.'),
                            'label_block' => true,
                            'frontend_available' => true,
                        ],
                        'elementor_page_title_selector' => [
                            'label' => __('Page Title Selector'),
                            'type' => ControlsManager::TEXT,
                            'placeholder' => 'h1.entry-title',
                            'description' => sprintf(
                                __('You can hide the title at document settings. This works for themes that have "%s" selector. If your theme\'s selector is different, please enter it above.'),
                                'header.page-header h1'
                            ),
                            'label_block' => true,
                        ],
                        'elementor_page_wrapper_selector' => [
                            'label' => __('Full Width Selector'),
                            'type' => ControlsManager::TEXT,
                            'placeholder' => 'h1.entry-title',
                            'description' => sprintf(
                                __('You can force full width layout at document settings. This works for themes that have "%s" selector. If your theme\'s selector is different, please enter it above.'),
                                '#wrapper, #wrapper .container, #content'
                            ),
                            'label_block' => true,
                        ],
                    ],
                ],
            ],
            Manager::PANEL_TAB_LIGHTBOX => [
                'lightbox' => [
                    'label' => __('Lightbox'),
                    'controls' => [
                        'elementor_global_image_lightbox' => [
                            'label' => __('Image Lightbox'),
                            'type' => ControlsManager::SWITCHER,
                            'return_value' => '1',
                            'description' => __('Open all image links in a lightbox popup window. The lightbox will automatically work on any link that leads to an image file.'),
                            'frontend_available' => true,
                        ],
                        'elementor_enable_lightbox_in_editor' => [
                            'label' => __('Enable In Editor'),
                            'type' => ControlsManager::SWITCHER,
                            'default' => 'yes',
                            'frontend_available' => true,
                        ],
                        'elementor_lightbox_color' => [
                            'label' => __('Background Color'),
                            'type' => ControlsManager::COLOR,
                            'selectors' => [
                                '.elementor-lightbox' => 'background-color: {{VALUE}}',
                            ],
                        ],
                        'elementor_lightbox_ui_color' => [
                            'label' => __('UI Color'),
                            'type' => ControlsManager::COLOR,
                            'selectors' => [
                                '.elementor-lightbox .dialog-lightbox-close-button, .elementor-lightbox .elementor-swiper-button' => 'color: {{VALUE}}',
                            ],
                        ],
                        'elementor_lightbox_ui_color_hover' => [
                            'label' => __('UI Hover Color'),
                            'type' => ControlsManager::COLOR,
                            'selectors' => [
                                '.elementor-lightbox .dialog-lightbox-close-button:hover, .elementor-lightbox .elementor-swiper-button:hover' => 'color: {{VALUE}}',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Register model controls.
     *
     * Used to add new controls to the global settings model.
     *
     * @since 1.6.0
     * @access protected
     */
    protected function _registerControls()
    {
        $controls_list = self::getControlsList();

        foreach ($controls_list as $tab_name => $sections) {
            foreach ($sections as $section_name => $section_data) {
                $this->startControlsSection(
                    $section_name,
                    [
                        'label' => $section_data['label'],
                        'tab' => $tab_name,
                    ]
                );

                foreach ($section_data['controls'] as $control_name => $control_data) {
                    $this->addControl($control_name, $control_data);
                }

                $this->endControlsSection();
            }
        }
    }
}
