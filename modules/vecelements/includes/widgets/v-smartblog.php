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
 * Elementor heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class WidgetVSmartBlog extends WidgetBase
{
    use CarouselTrait;
    /**
     * Get widget name.
     *
     * Retrieve heading widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'v-smartblog';
    }

    /**
     * Get widget title.
     *
     * Retrieve heading widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Latest post');
    }

    /**
     * Get widget icon.
     *
     * Retrieve heading widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-blockquote';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the heading widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 2.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function getCategories()
    {
        return ['premium'];
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
        return ['blog'];
    }

    /**
     * Register heading widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_content',
            [
                'label' => __('Content'),
            ]
        );

        $this->addControl(
            'limit',
            [
                'label' => __('Number post to show'),
                'type' => ControlsManager::NUMBER,
            ]
        );
        $this->addControl(
            'style',
            [
                'label' => __('Display'),
                'type' => ControlsManager::SELECT,
                'default' => 'style1',
                'options' => [
                    'style1' => __('Style 1'),
                    'style2' => __('Style 2'),
                    'style3' => __('Style 3'),
                    'style4' => __('Style 4'),
                ],
            ]
        );
        $this->addControl(
            'enable_slider',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => 'yes',
            ]
        );

        $this->endControlsSection();
        $this->registerCarouselSection([
            'default_slides_desktop' => 3,
            'default_slides_tablet' => 2,
            'default_slides_mobile' => 1,
        ]);

        $this->registerNavigationStyleSection();
    }

    /**
     * Render heading widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $context = \Context::getContext();
        $settings = $this->getSettingsForDisplay();
        //echo '<pre>'; print_r($settings); echo '</pre>'; die('x_x');
        $limit =  4;
        if((int)$settings['limit']){
            $limit = (int)$settings['limit'];
        }
        $posts = \SmartBlogPost::GetPostLatestHome($limit);
        $smart_blog_link = new \SmartBlogLink();
        $imageType = 'home-default';
        $images = \BlogImageType::GetImageByType($imageType);

        foreach ($posts as $post) {
            $post['url']          = $smart_blog_link->getSmartBlogPostLink($post['id_post'], $post['link_rewrite']);
            $post['image']['url'] = $smart_blog_link->getImageLink($post['link_rewrite'], $post['id_post'], $imageType);
            
            foreach ($images as $image) {
                if ($image['type'] == 'post') {
                    $post['image']['type']   = 'blog_post_'.$imageType;
                    $post['image']['width']  = $image['width'];
                    $post['image']['height'] = $image['height'];
                    break;
                }
            }
        }
        $classes = 'columns-desktop-'. ($settings['slides_to_scroll'] ? $settings['slides_to_scroll'] : $settings['default_slides_desktop']);
        $classes .= ' columns-tablet-'. ($settings['slides_to_scroll_tablet'] ? $settings['slides_to_scroll_tablet'] : $settings['default_slides_tablet']);
        $classes .= ' columns-mobile-'. ($settings['slides_to_scroll_mobile'] ? $settings['slides_to_scroll_mobile'] : $settings['default_slides_mobile']); 
        $classes .= ' slick-arrows-' . $settings['arrows_position'];
        
        $context->smarty->assign(
            array(
                'posts'  => $posts,
                'smartbloglink' => $smart_blog_link,
                'smartshowauthor'      => \Configuration::get( 'smartshowauthor' ),
                'smartshowauthorstyle' => \Configuration::get( 'smartshowauthorstyle' ),
                'smartshowviewed'      => \Configuration::get( 'smartshowviewed' ),
                'style' => 'module:smartblog/views/templates/front/post/'. $settings['style'] .'.tpl',
                'classes' => $classes,
            )
        );
        $template_file_name = _VEC_TEMPLATES_ . 'front/widgets/v-smartblog.tpl';

        echo $context->smarty->fetch( $template_file_name );
        
    }

    /**
     * Render heading widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate(){}

}
