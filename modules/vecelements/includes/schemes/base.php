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
 * Elementor scheme base.
 *
 * An abstract class implementing the scheme interface, responsible for
 * creating new schemes.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class SchemeBase implements SchemeInterface
{
    /**
     * DB option name for the time when the scheme was last updated.
     */
    const LAST_UPDATED_META = '_elementor_scheme_last_updated';

    /**
     * System schemes.
     *
     * Holds the list of all the system schemes.
     *
     * @since 1.0.0
     * @access private
     *
     * @var array System schemes.
     */
    private $_system_schemes;

    /**
     * Init system schemes.
     *
     * Initialize the system schemes.
     *
     * @since 1.0.0
     * @access protected
     * @abstract
     */
    abstract protected function _initSystemSchemes();

    /**
     * Get description.
     *
     * Retrieve the scheme description.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return string Scheme description.
     */
    public static function getDescription()
    {
        return '';
    }

    /**
     * Get system schemes.
     *
     * Retrieve the system schemes.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string System schemes.
     */
    final public function getSystemSchemes()
    {
        if (null === $this->_system_schemes) {
            $this->_system_schemes = $this->_initSystemSchemes();
        }

        return $this->_system_schemes;
    }

    /**
     * Get scheme value.
     *
     * Retrieve the scheme value.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Scheme value.
     */
    public function getSchemeValue()
    {
        $scheme_value = get_option('elementor_scheme_' . static::getType());

        if (!$scheme_value) {
            $scheme_value = $this->getDefaultScheme();

            update_option('elementor_scheme_' . static::getType(), $scheme_value);
        }

        return $scheme_value;
    }

    /**
     * Save scheme.
     *
     * Update Elementor scheme in the database, and update the last updated
     * scheme time.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $posted
     */
    public function saveScheme(array $posted)
    {
        $scheme_value = $this->getSchemeValue();

        update_option('elementor_scheme_' . static::getType(), array_replace($scheme_value, array_intersect_key($posted, $scheme_value)));

        update_option(self::LAST_UPDATED_META, time());
    }

    /**
     * Get scheme.
     *
     * Retrieve the scheme.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array The scheme.
     */
    public function getScheme()
    {
        $scheme = [];

        $titles = $this->getSchemeTitles();

        foreach ($this->getSchemeValue() as $scheme_key => $scheme_value) {
            $scheme[$scheme_key] = [
                'title' => isset($titles[$scheme_key]) ? $titles[$scheme_key] : '',
                'value' => $scheme_value,
            ];
        }

        return $scheme;
    }

    /**
     * Print scheme template.
     *
     * Used to generate the scheme template on the editor using Underscore JS
     * template.
     *
     * @since 1.0.0
     * @access public
     */
    final public function printTemplate()
    {
        ?>
        <script type="text/template" id="tmpl-elementor-panel-schemes-<?= static::getType() ?>">
            <div class="elementor-panel-scheme-buttons">
                <div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-reset">
                    <button class="elementor-button">
                        <i class="fa fa-undo" aria-hidden="true"></i>
                        <?= __('Reset') ?>
                    </button>
                </div>
                <div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-discard">
                    <button class="elementor-button">
                        <i class="fa fa-times" aria-hidden="true"></i>
                        <?= __('Discard') ?>
                    </button>
                </div>
                <div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-save">
                    <button class="elementor-button elementor-button-success" disabled><?= __('Apply') ?></button>
                </div>
            </div>
            <?php $this->printTemplateContent() ?>
        </script>
        <?php
    }
}
