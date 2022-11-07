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
use VEC\CoreXSettingsXManager as SettingsManager;

class CoreXDocumentTypesXPost extends Document
{
    /**
     * @since 2.0.8
     * @access public
     * @static
     */
    public static function getProperties()
    {
        $properties = parent::getProperties();

        // $properties['admin_tab_group'] = '';
        $properties['support_wp_page_templates'] = true;

        return $properties;
    }

    /**
     * @since 2.1.2
     * @access protected
     * @static
     */
    protected static function getEditorPanelCategories()
    {
        return Utils::arrayInject(
            parent::getEditorPanelCategories(),
            'theme-elements',
            [
                'theme-elements-single' => [
                    'title' => __('Single'),
                    'active' => false,
                ],
            ]
        );
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function getName()
    {
        return 'post';
    }

    /**
     * @since 2.0.0
     * @access public
     * @static
     */
    public static function getTitle()
    {
        return __('Page');
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function getCssWrapperSelector()
    {
        return 'body.elementor-page-' . $this->getMainId();
    }

    /**
     * @since 2.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        parent::_registerControls();

        self::registerHideTitleControl($this);

        self::registerPostFieldsControl($this);

        self::registerStyleControls($this);
    }

    /**
     * @since 2.0.0
     * @access public
     * @static
     * @param Document $document
     */
    public static function registerHideTitleControl($document)
    {
        $page_title_selector = SettingsManager::getSettingsManagers('general')->getModel()->getSettings('elementor_page_title_selector');

        if (!$page_title_selector) {
            $page_title_selector = 'header.page-header h1';
        }

        $page_title_selector .= ', .elementor-page-title';

        $document->startInjection([
            'of' => 'post_title',
        ]);

        $document->addControl(
            'hide_title',
            [
                'label' => __('Hide Title'),
                'type' => ControlsManager::SWITCHER,
                'description' => sprintf(__(
                    'Not working? You can set a different selector for the title ' .
                    'in the <a href="%s" target="_blank">Settings page</a>.'
                ), Helper::getSettingsLink()),
                'selectors' => [
                    '{{WRAPPER}} ' . $page_title_selector => 'display: none',
                ],
            ]
        );

        $document->endInjection();
    }

    /**
     * @since 2.0.0
     * @access public
     * @static
     * @param Document $document
     */
    public static function registerStyleControls($document)
    {
        $document->startControlsSection(
            'section_page_style',
            [
                'label' => __('Body Style'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $document->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background',
                'fields_options' => [
                    'image' => [
                        // Currently isn't supported.
                        'dynamic' => [
                            'active' => false,
                        ],
                    ],
                ],
            ]
        );

        $document->addResponsiveControl(
            'padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $document->endControlsSection();

        Plugin::$instance->controls_manager->addCustomCssControls($document);
    }

    /**
     * @since 2.0.0
     * @access public
     * @static
     * @param Document $document
     */
    public static function registerPostFieldsControl($document)
    {
        $document->startInjection([
            'of' => 'post_status',
        ]);

        // if (post_type_supports($document->post->post_type, 'excerpt')) {
        //     $document->addControl(
        //         'post_excerpt',
        //         [
        //             'label' => __('Excerpt'),
        //             'type' => ControlsManager::TEXTAREA,
        //             'default' => $document->post->post_excerpt,
        //             'label_block' => true,
        //         ]
        //     );
        // }

        $uid = UId::parse($document->getMainId());

        if (UId::CMS === $uid->id_type || UId::THEME === $uid->id_type) {
            $document->addControl(
                'post_featured_image',
                [
                    'label' => __('Featured Image'),
                    'type' => ControlsManager::MEDIA,
                    'default' => [
                        'url' => $document->getMeta('_og_image'),
                    ],
                ]
            );
        }

        $document->endInjection();
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param array $data
     *
     * @throws \Exception
     */
    public function __construct(array $data = [])
    {
        if ($data) {
            $template = get_post_meta($data['post_id'], '_wp_page_template', true);

            if (empty($template)) {
                $template = 'default';
            }

            $data['settings']['template'] = $template;
        }

        parent::__construct($data);
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['type'] = 'page';

        return $config;
    }
}
