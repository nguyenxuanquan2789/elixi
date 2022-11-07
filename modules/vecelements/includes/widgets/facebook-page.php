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

class WidgetFacebookPage extends WidgetBase
{
    public function getName()
    {
        return 'facebook-page';
    }

    public function getTitle()
    {
        return __('Facebook Page');
    }

    public function getIcon()
    {
        return 'eicon-fb-feed';
    }

    public function getCategories()
    {
        return ['premium', 'maintenance-premium'];
    }

    public function getKeywords()
    {
        return ['facebook', 'fb', 'social', 'embed', 'page'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_content',
            [
                'label' => __('Facebook Page'),
            ]
        );

        $this->addControl(
            'url',
            [
                'label' => __('URL'),
                'placeholder' => 'https://www.facebook.com/your-page/',
                'default' => 'https://www.facebook.com/webshopworks/',
                'label_block' => true,
                'description' => __('Paste the URL of the Facebook page.'),
            ]
        );

        $this->addControl(
            'tabs',
            [
                'label' => __('Tabs'),
                'type' => ControlsManager::SELECT2,
                'multiple' => true,
                'label_block' => true,
                'default' => [
                    'timeline',
                ],
                'options' => [
                    'timeline' => __('Timeline'),
                    'events' => __('Events'),
                    'messages' => __('Messages'),
                ],
            ]
        );

        $this->addControl(
            'small_header',
            [
                'label' => __('Small Header'),
                'type' => ControlsManager::SWITCHER,
                'default' => '',
            ]
        );

        $this->addControl(
            'show_cover',
            [
                'label' => __('Cover'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'separator' => '',
            ]
        );

        $this->addControl(
            'show_facepile',
            [
                'label' => __('Profile Photos'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'separator' => '',
            ]
        );

        $this->addControl(
            'show_cta',
            [
                'label' => __('Custom CTA Button'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'separator' => '',
                'condition' => [
                    'small_header' => '',
                ],
            ]
        );

        $this->addControl(
            'height',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'range' => [
                    'px' => [
                        'min' => 70,
                        'max' => 1000,
                    ],
                ],
                'size_units' => ['px'],
            ]
        );

        $this->addResponsiveControl(
            'align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    public function render()
    {
        $settings = $this->getSettings();

        if (empty($settings['url'])) {
            echo $this->getTitle() . ': ' . __('Please enter a valid URL');

            return;
        }

        $this->addRenderAttribute('frame', [
            'height' => $settings['height']['size'],
            'src' => 'about:blank',
            'loading' => 'lazy',
            'data-url' => 'https://www.facebook.com/plugins/page.php?' . http_build_query([
                'href' => $settings['url'],
                'tabs' => implode(',', $settings['tabs']),
                'small_header' => $settings['small_header'] ? 'true' : 'false',
                'hide_cover' => $settings['show_cover'] ? 'false' : 'true',
                'show_facepile' => $settings['show_facepile'] ? 'true' : 'false',
                'hide_cta' => $settings['show_cta'] ? 'false' : 'true',
                'height' => $settings['height']['size'],
                'width' => '',
            ]),
            'onload' => "this.removeAttribute('onload'),this.src=this.getAttribute('data-url')+this.offsetWidth",
            'style' => implode(';', [
                'border: none',
                'min-height: 70px',
                'min-width: 180px',
                'max-width: 500px',
            ]),
            'frameborder' => '0',
            'scrolling' => 'no',
            'allow' => 'encrypted-media',
            'allowFullscreen' => 'true',
        ]);

        echo "<\x69frame {$this->getRenderAttributeString('frame')}></\x69frame>";
    }

    public function renderPlainContent()
    {
    }
}
