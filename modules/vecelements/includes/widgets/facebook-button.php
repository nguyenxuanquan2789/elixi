<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace VEC;

defined('_PS_VERSION_') or exit;

class WidgetFacebookButton extends WidgetBase
{
    public function getName()
    {
        return 'facebook-button';
    }

    public function getTitle()
    {
        return __('Facebook Button');
    }

    public function getIcon()
    {
        return 'eicon-facebook-like-box';
    }

    public function getCategories()
    {
        return ['premium', 'maintenance-premium'];
    }

    public function getKeywords()
    {
        return ['facebook', 'fb', 'social', 'embed', 'button', 'like', 'share', 'recommend', 'follow'];
    }

    public function getHeight($layout, $size, $share)
    {
        $small = 'small' == $size;

        if ('box_count' == $layout) {
            return $share
                ? ($small ? 64 : 90)
                : ($small ? 40 : 58)
            ;
        }
        return $small ? 20 : 28;
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_content',
            [
                'label' => __('Button'),
            ]
        );

        $this->addControl(
            'type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'default' => 'like',
                'options' => [
                    'like' => __('Like'),
                    'recommend' => __('Recommend'),
                ],
            ]
        );

        $this->addControl(
            'layout',
            [
                'label' => __('Layout'),
                'type' => ControlsManager::SELECT,
                'default' => 'standard',
                'options' => [
                    'standard' => __('Standard'),
                    'button' => __('Button'),
                    'button_count' => __('Button Count'),
                    'box_count' => __('Box Count'),
                ],
                'prefix_class' => 'elementor-type-',
                'render_type' => 'template',
            ]
        );

        $this->addControl(
            'size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SELECT,
                'default' => 'small',
                'options' => [
                    'small' => __('Small'),
                    'large' => __('Large'),
                ],
                'prefix_class' => 'elementor-size-',
                'render_type' => 'template',
            ]
        );

        $this->addControl(
            'show_share',
            [
                'label' => __('Share Button'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
            ]
        );

        $this->addControl(
            'url_type',
            [
                'label' => __('Target URL'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'current' => __('Current Page'),
                    'custom' => __('Custom'),
                ],
                'separator' => 'before',
                'default' => 'current',
            ]
        );

        $this->addControl(
            'url',
            [
                'label' => __('Link'),
                'placeholder' => __('https://your-link.com'),
                'label_block' => true,
                'condition' => [
                    'url_type' => 'custom',
                ],
            ]
        );

        $this->endControlsSection();
    }

    public function render()
    {
        $settings = $this->getSettings();

        if ($settings['url_type'] == 'current') {
            $url = \Tools::getShopProtocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        } elseif (!empty($settings['url']) && \Validate::isAbsoluteUrl($settings['url'])) {
            $url = $settings['url'];
        } else {
            return print Helper::transError('Invalid URL');
        }

        $this->addRenderAttribute('frame', [
            'src' => 'about:blank',
            'loading' => 'lazy',
            'data-url' => 'https://www.facebook.com/plugins/like.php?' . http_build_query([
                'href' => $url,
                'action' => $settings['type'],
                'layout' => $settings['layout'],
                'size' => $settings['size'],
                'share' => $settings['show_share'] ? 'true' : 'false',
            ]),
            'style' => 'height: ' .
                $this->getHeight($settings['layout'], $settings['size'], $settings['show_share']) . 'px;',
            'onload' => "this.removeAttribute('onload'),this.src=this.getAttribute('data-url')",
            'frameborder' => '0',
        ]);

        echo "<\x69frame {$this->getRenderAttributeString('frame')}></\x69frame>";
    }

    public function renderPlainContent()
    {
    }
}
