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

/**
 * Elementor HTML widget.
 *
 * Elementor widget that insert a custom HTML code into the page.
 *
 * @since 1.0.0
 */
class WidgetHtml extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve HTML widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'html';
    }

    /**
     * Get widget title.
     *
     * Retrieve HTML widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('HTML');
    }

    /**
     * Get widget icon.
     *
     * Retrieve HTML widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-editor-code';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function getKeywords()
    {
        return ['html', 'code'];
    }

    /**
     * Register HTML widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_title',
            [
                'label' => __('HTML Code'),
            ]
        );

        $this->addControl(
            'html',
            [
                'label' => '',
                'type' => ControlsManager::CODE,
                'default' => '',
                'placeholder' => __('Enter your code'),
                'show_label' => false,
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render HTML widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        echo $this->getSettingsForDisplay('html');
    }

    /**
     * Render HTML widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate()
    {
        ?>
        {{{ settings.html }}}
        <?php
    }
}
