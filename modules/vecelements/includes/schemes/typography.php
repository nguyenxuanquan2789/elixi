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

class SchemeTypography extends SchemeBase
{
    const TYPOGRAPHY_1 = '1';
    const TYPOGRAPHY_2 = '2';
    const TYPOGRAPHY_3 = '3';
    const TYPOGRAPHY_4 = '4';

    public static function getType()
    {
        return 'typography';
    }

    public function getTitle()
    {
        return __('Typography');
    }

    public function getDisabledTitle()
    {
        return __('Default Fonts');
    }

    public function getSchemeTitles()
    {
        return [
            self::TYPOGRAPHY_1 => __('Primary Headline'),
            self::TYPOGRAPHY_2 => __('Secondary Headline'),
            self::TYPOGRAPHY_3 => __('Body Text'),
            self::TYPOGRAPHY_4 => __('Accent Text'),
        ];
    }

    public function getDefaultScheme()
    {
        return [
            self::TYPOGRAPHY_1 => [
                'font_family' => 'Roboto',
                'font_weight' => '600',
            ],
            self::TYPOGRAPHY_2 => [
                'font_family' => 'Roboto Slab',
                'font_weight' => '400',
            ],
            self::TYPOGRAPHY_3 => [
                'font_family' => 'Roboto',
                'font_weight' => '400',
            ],
            self::TYPOGRAPHY_4 => [
                'font_family' => 'Roboto',
                'font_weight' => '500',
            ],
        ];
    }

    protected function _initSystemSchemes()
    {
        return [];
    }

    public function printTemplateContent()
    {
        ?>
        <div class="elementor-panel-scheme-items"></div>
        <?php
    }
}
