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
 * Elementor media control.
 *
 * A base control for creating a media chooser control. Based on the PrestaShop
 * file manager. Used to select an image from the PrestaShop file manager.
 *
 * @since 1.0.0
 */
class ControlMedia extends ControlBaseMultiple
{
    /**
     * Get media control type.
     *
     * Retrieve the control type, in this case `media`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'media';
    }

    /**
     * Get media control default values.
     *
     * Retrieve the default value of the media control. Used to return the default
     * values while initializing the media control.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Control default value.
     */
    public function getDefaultValue()
    {
        return [
            'url' => '',
            'id' => '',
        ];
    }

    /**
     * Import media images.
     *
     * Used to import media control files from external sites while importing
     * Elementor template JSON file, and replacing the old data.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $settings Control settings
     *
     * @return array Control settings.
     */
    public function onImport($settings)
    {
        if (empty($settings['url'])) {
            return $settings;
        }

        $settings = Plugin::$instance->templates_manager->getImportImagesInstance()->import($settings);

        if (!$settings) {
            $settings = [
                'id' => '',
                'url' => Utils::getPlaceholderImageSrc(),
            ];
        }

        return $settings;
    }

    public function onExport($settings)
    {
        if (!empty($settings['url'])) {
            $settings['url'] = Helper::getMediaLink($settings['url'], true);
        }

        return $settings;
    }

    /**
     * Enqueue media control scripts and styles.
     *
     * Used to register and enqueue custom scripts and styles used by the media
     * control.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueue()
    {
        wp_enqueue_style('jquery-fancybox', _PS_JS_DIR_ . 'jquery/plugins/fancybox/jquery.fancybox.css');

        wp_enqueue_script('jquery-fancybox', _PS_JS_DIR_ . 'jquery/plugins/fancybox/jquery.fancybox.js', ['jquery'], false, true);
    }

    /**
     * Render media control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     * @access public
     * @codingStandardsIgnoreStart Generic.Files.LineLength
     */
    public function contentTemplate()
    {
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-units-choices">
                <input type="radio" checked id="elementor-control-media-url-{{ data._cid }}" value="{{ data.controlValue.url }}"
                ><label class="elementor-units-choices-label elementor-control-media-url"><?= __('Modify URL') ?></label>
            </div>
            <div class="elementor-control-input-wrapper elementor-aspect-ratio-169">
                <div class="elementor-control-media elementor-control-tag-area elementor-control-preview-area elementor-fit-aspect-ratio">
                    <div class="elementor-control-media-upload-button elementor-fit-aspect-ratio">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </div>
                    <div class="elementor-control-media-area elementor-fit-aspect-ratio{{ data.seo ? ' elementor-control-media-seo' : '' }}">
                    <# if( 'image' === data.media_type ) { #>
                        <div class="elementor-control-media-image elementor-fit-aspect-ratio"></div>
                    <# } else if( 'video' === data.media_type ) { #>
                        <video class="elementor-control-media-video" preload="metadata"></video>
                        <i class="fa fa-video-camera"></i>
                    <# } #>
                        <div class="elementor-control-media-btn elementor-control-media-alt"><?= __('Alt') ?></div>
                        <div class="elementor-control-media-btn elementor-control-media-title"><?= __('Title') ?></div>
                        <div class="elementor-control-media-btn elementor-control-media-delete"><?= __('Delete') ?></div>
                    </div>
                </div>
            </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
            <input type="hidden" data-setting="{{ data.name }}" />
        </div>
        <?php
    }

    /**
     * Get media control default settings.
     *
     * Retrieve the default settings of the media control. Used to return the default
     * settings while initializing the media control.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function getDefaultSettings()
    {
        return [
            'label_block' => true,
            'media_type' => 'image',
            'dynamic' => [
                // 'categories' => [TagsModule::IMAGE_CATEGORY],
                'returnType' => 'object',
            ],
        ];
    }

    /**
     * Get media control image title.
     *
     * Retrieve the `title` of the image selected by the media control.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param array $instance Media attachment.
     *
     * @return string Image title.
     */
    public static function getImageTitle($instance)
    {
        return !empty($instance['title']) ? esc_attr($instance['title']) : '';
    }

    /**
     * Get media control image alt.
     *
     * Retrieve the `alt` value of the image selected by the media control.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param array $instance Media attachment.
     *
     * @return string Image alt.
     */
    public static function getImageAlt($instance)
    {
        return !empty($instance['alt']) ? esc_attr($instance['alt']) : '';
    }
}
