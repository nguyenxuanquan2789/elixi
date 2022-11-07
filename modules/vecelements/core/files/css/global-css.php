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

use VEC\CoreXFilesXCSSXBase as Base;

/**
 * Elementor global CSS file.
 *
 * Elementor CSS file handler class is responsible for generating the global CSS
 * file.
 *
 * @since 1.2.0
 */
class CoreXFilesXCSSXGlobalCSS extends Base
{
    /**
     * Elementor global CSS file handler ID.
     */
    const FILE_HANDLER_ID = 'elementor-global';

    const META_KEY = '_elementor_global_css';

    /**
     * Get CSS file name.
     *
     * Retrieve the CSS file name.
     *
     * @since 1.6.0
     * @access public
     *
     * @return string CSS file name.
     */
    public function getName()
    {
        return 'global';
    }

    /**
     * Get file handle ID.
     *
     * Retrieve the handle ID for the global post CSS file.
     *
     * @since 1.2.0
     * @access protected
     *
     * @return string CSS file handle ID.
     */
    protected function getFileHandleId()
    {
        return self::FILE_HANDLER_ID;
    }

    /**
     * Render CSS.
     *
     * Parse the CSS for all the widgets and all the scheme controls.
     *
     * @since 1.2.0
     * @access protected
     */
    protected function renderCss()
    {
        $this->renderSchemesCss();
    }

    /**
     * Get inline dependency.
     *
     * Retrieve the name of the stylesheet used by `wp_add_inline_style()`.
     *
     * @since 1.2.0
     * @access protected
     *
     * @return string Name of the stylesheet.
     */
    protected function getInlineDependency()
    {
        return 'elementor-frontend';
    }

    /**
     * Is update required.
     *
     * Whether the CSS requires an update. When there are new schemes or settings
     * updates.
     *
     * @since 1.2.0
     * @access protected
     *
     * @return bool True if the CSS requires an update, False otherwise.
     */
    protected function isUpdateRequired()
    {
        $file_last_updated = $this->getMeta('time');

        $schemes_last_update = get_option(SchemeBase::LAST_UPDATED_META);

        if ($file_last_updated < $schemes_last_update) {
            return true;
        }

        // $elementor_settings_last_updated = get_option(Settings::UPDATE_TIME_FIELD);

        // if ($file_last_updated < $elementor_settings_last_updated) {
        //     return true;
        // }

        return false;
    }

    /**
     * Render schemes CSS.
     *
     * Parse the CSS for all the widgets and all the scheme controls.
     *
     * @since 1.2.0
     * @access private
     */
    private function renderSchemesCss()
    {
        $elementor = Plugin::$instance;

        foreach ($elementor->widgets_manager->getWidgetTypes() as $widget) {
            $scheme_controls = $widget->getSchemeControls();

            foreach ($scheme_controls as $control) {
                $this->addControlRules(
                    $control,
                    $widget->getControls(),
                    function ($control) use ($elementor) {
                        $scheme_value = $elementor->schemes_manager->getSchemeValue($control['scheme']['type'], $control['scheme']['value']);

                        if (empty($scheme_value)) {
                            return null;
                        }

                        if (!empty($control['scheme']['key'])) {
                            $scheme_value = $scheme_value[$control['scheme']['key']];
                        }

                        if (empty($scheme_value)) {
                            return null;
                        }

                        return $scheme_value;
                    },
                    ['{{WRAPPER}}'],
                    ['.elementor-widget-' . $widget->getName()]
                );
            }
        }
    }
}
