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
 * Elementor element base.
 *
 * An abstract class to register new Elementor elements. It extended the
 * `Controls_Stack` class to inherit its properties.
 *
 * This abstract class must be extended in order to register new elements.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class ElementBase extends ControlsStack
{
    /**
     * Child elements.
     *
     * Holds all the child elements of the element.
     *
     * @access private
     *
     * @var ElementBase[]
     */
    private $children;

    /**
     * Element render attributes.
     *
     * Holds all the render attributes of the element. Used to store data like
     * the HTML class name and the class value, or HTML element ID name and value.
     *
     * @access private
     *
     * @var array
     */
    private $render_attributes = [];

    /**
     * Element default arguments.
     *
     * Holds all the default arguments of the element. Used to store additional
     * data.
     *
     * @access private
     *
     * @var array
     */
    private $default_args = [];

    /**
     * Is type instance.
     *
     * Whether the element is an instance of that type or not.
     *
     * @access private
     *
     * @var bool
     */
    private $is_type_instance = true;

    /**
     * Depended scripts.
     *
     * Holds all the element depended scripts to enqueue.
     *
     * @since 1.9.0
     * @access private
     *
     * @var array
     */
    private $depended_scripts = [];

    /**
     * Depended styles.
     *
     * Holds all the element depended styles to enqueue.
     *
     * @since 1.9.0
     * @access private
     *
     * @var array
     */
    private $depended_styles = [];

    /**
     * Element edit tools.
     *
     * Holds all the edit tools of the element. For example: delete, duplicate etc.
     *
     * @access protected
     * @static
     *
     * @var array
     */
    protected static $_edit_tools;

    /**
     * Add script depends.
     *
     * Register new script to enqueue by the handler.
     *
     * @since 1.9.0
     * @access public
     *
     * @param string $handler Depend script handler.
     */
    public function addScriptDepends($handler)
    {
        $this->depended_scripts[] = $handler;
    }

    /**
     * Add style depends.
     *
     * Register new style to enqueue by the handler.
     *
     * @since 1.9.0
     * @access public
     *
     * @param string $handler Depend style handler.
     */
    public function addStyleDepends($handler)
    {
        $this->depended_styles[] = $handler;
    }

    /**
     * Get script dependencies.
     *
     * Retrieve the list of script dependencies the element requires.
     *
     * @since 1.3.0
     * @access public
     *
     * @return array Element scripts dependencies.
     */
    public function getScriptDepends()
    {
        return $this->depended_scripts;
    }

    /**
     * Enqueue scripts.
     *
     * Registers all the scripts defined as element dependencies and enqueues
     * them. Use `get_script_depends()` method to add custom script dependencies.
     *
     * @since 1.3.0
     * @access public
     */
    final public function enqueueScripts()
    {
        foreach ($this->getScriptDepends() as $script) {
            wp_enqueue_script($script);
        }
    }

    /**
     * Get style dependencies.
     *
     * Retrieve the list of style dependencies the element requires.
     *
     * @since 1.9.0
     * @access public
     *
     * @return array Element styles dependencies.
     */
    public function getStyleDepends()
    {
        return $this->depended_styles;
    }

    /**
     * Enqueue styles.
     *
     * Registers all the styles defined as element dependencies and enqueues
     * them. Use `get_style_depends()` method to add custom style dependencies.
     *
     * @since 1.9.0
     * @access public
     */
    final public function enqueueStyles()
    {
        foreach ($this->getStyleDepends() as $style) {
            wp_enqueue_style($style);
        }
    }

    /**
     * Get element edit tools.
     *
     * Used to retrieve the element edit tools.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return array Element edit tools.
     */
    final public static function getEditTools()
    {
        // if (!Plugin::instance()->role_manager->userCan('design')) {
        //     return [];
        // }

        if (null === static::$_edit_tools) {
            self::initEditTools();
        }

        return static::$_edit_tools;
    }

    /**
     * Add new edit tool.
     *
     * Register new edit tool for the element.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param string   $tool_name Edit tool name.
     * @param string[] $tool_data Edit tool data.
     * @param string   $after     Optional. If tool ID defined, the new edit tool
     *                            will be added after it. If null, the new edit
     *                            tool will be added at the end. Default is null.
     *
     */
    final public static function addEditTool($tool_name, $tool_data, $after = null)
    {
        if (null === static::$_edit_tools) {
            self::initEditTools();
        }

        // Adding the tool at specific position
        // in the tools array if requested
        if ($after) {
            $after_index = array_search($after, array_keys(static::$_edit_tools), true) + 1;

            static::$_edit_tools = array_slice(static::$_edit_tools, 0, $after_index, true) +
            [
                $tool_name => $tool_data,
            ] +
            array_slice(static::$_edit_tools, $after_index, null, true);
        } else {
            static::$_edit_tools[$tool_name] = $tool_data;
        }
    }

    /**
     * @since 2.2.0
     * @access public
     * @static
     */
    final public static function isEditButtonsEnabled()
    {
        return 'on';
    }

    /**
     * Get default edit tools.
     *
     * Retrieve the element default edit tools. Used to set initial tools.
     * By default the element has no edit tools.
     *
     * @since 1.0.0
     * @access protected
     * @static
     *
     * @return array Default edit tools.
     */
    protected static function getDefaultEditTools()
    {
        return [];
    }

    /**
     * Initialize edit tools.
     *
     * Register default edit tools.
     *
     * @since 2.0.0
     * @access private
     * @static
     */
    private static function initEditTools()
    {
        static::$_edit_tools = static::getDefaultEditTools();
    }

    /**
     * Get default child type.
     *
     * Retrieve the default child type based on element data.
     *
     * Note that not all elements support children.
     *
     * @since 1.0.0
     * @access protected
     * @abstract
     *
     * @param array $element_data Element data.
     *
     * @return ElementBase
     */
    abstract protected function _getDefaultChildType(array $element_data);

    /**
     * Before element rendering.
     *
     * Used to add stuff before the element.
     *
     * @since 1.0.0
     * @access public
     */
    public function beforeRender()
    {
    }

    /**
     * After element rendering.
     *
     * Used to add stuff after the element.
     *
     * @since 1.0.0
     * @access public
     */
    public function afterRender()
    {
    }

    /**
     * Get element title.
     *
     * Retrieve the element title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Element title.
     */
    public function getTitle()
    {
        return '';
    }

    /**
     * Get element icon.
     *
     * Retrieve the element icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Element icon.
     */
    public function getIcon()
    {
        return 'eicon-columns';
    }

    /**
     * Whether the reload preview is required.
     *
     * Used to determine whether the reload preview is required or not.
     *
     * @since 1.0.0
     * @access public
     *
     * @return bool Whether the reload preview is required.
     */
    public function isReloadPreviewRequired()
    {
        return false;
    }

    /**
     * @since 2.3.1
     * @access protected
     */
    protected function shouldPrintEmpty()
    {
        return true;
    }

    /**
     * Print element content template.
     *
     * Used to generate the element content template on the editor, using a
     * Backbone JavaScript template.
     *
     * @access protected
     * @since 2.0.0
     *
     * @param string $template_content Template content.
     */
    protected function printTemplateContent($template_content)
    {
        $this->renderEditTools();

        echo $template_content; // XSS ok.
    }

    /**
     * Get child elements.
     *
     * Retrieve all the child elements of this element.
     *
     * @since 1.0.0
     * @access public
     *
     * @return ElementBase[] Child elements.
     */
    public function getChildren()
    {
        if (null === $this->children) {
            $this->initChildren();
        }

        return $this->children;
    }

    /**
     * Get default arguments.
     *
     * Retrieve the element default arguments. Used to return all the default
     * arguments or a specific default argument, if one is set.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $item Optional. Default is null.
     *
     * @return array Default argument(s).
     */
    public function getDefaultArgs($item = null)
    {
        return self::_getItems($this->default_args, $item);
    }

    /**
     * Add new child element.
     *
     * Register new child element to allow hierarchy.
     *
     * @since 1.0.0
     * @access public
     * @param array $child_data Child element data.
     * @param array $child_args Child element arguments.
     *
     * @return ElementBase|false Child element instance, or false if failed.
     */
    public function addChild(array $child_data, array $child_args = [])
    {
        if (null === $this->children) {
            $this->initChildren();
        }

        $child_type = $this->getChildType($child_data);

        if (!$child_type) {
            return false;
        }

        $child = Plugin::$instance->elements_manager->createElementInstance($child_data, $child_args, $child_type);

        if ($child) {
            $this->children[] = $child;
        }

        return $child;
    }

    /**
     * Add render attribute.
     *
     * Used to add attributes to a specific HTML element.
     *
     * The HTML tag is represented by the element parameter, then you need to
     * define the attribute key and the attribute key. The final result will be:
     * `<element attribute_key="attribute_value">`.
     *
     * Example usage:
     *
     * `$this->addRenderAttribute( 'wrapper', 'class', 'custom-widget-wrapper-class' );`
     * `$this->addRenderAttribute( 'widget', 'id', 'custom-widget-id' );`
     * `$this->addRenderAttribute( 'button', [ 'class' => 'custom-button-class', 'id' => 'custom-button-id' ] );`
     *
     * @since 1.0.0
     * @access public
     *
     * @param array|string $element   The HTML element.
     * @param array|string $key       Optional. Attribute key. Default is null.
     * @param array|string $value     Optional. Attribute value. Default is null.
     * @param bool         $overwrite Optional. Whether to overwrite existing
     *                                attribute. Default is false, not to overwrite.
     *
     * @return ElementBase Current instance of the element.
     */
    public function addRenderAttribute($element, $key = null, $value = null, $overwrite = false)
    {
        if (is_array($element)) {
            foreach ($element as $element_key => $attributes) {
                $this->addRenderAttribute($element_key, $attributes, null, $overwrite);
            }

            return $this;
        }

        if (is_array($key)) {
            foreach ($key as $attribute_key => $attributes) {
                $this->addRenderAttribute($element, $attribute_key, $attributes, $overwrite);
            }

            return $this;
        }

        if (empty($this->render_attributes[$element][$key])) {
            $this->render_attributes[$element][$key] = [];
        }

        settype($value, 'array');

        if ($overwrite) {
            $this->render_attributes[$element][$key] = $value;
        } else {
            $this->render_attributes[$element][$key] = array_merge($this->render_attributes[$element][$key], $value);
        }

        return $this;
    }

    /**
     * Get Render Attributes
     *
     * Used to retrieve render attribute.
     *
     * The returned array is either all elements and their attributes if no `$element` is specified, an array of all
     * attributes of a specific element or a specific attribute properties if `$key` is specified.
     *
     * Returns null if one of the requested parameters isn't set.
     *
     * @since 2.2.6
     * @access public
     * @param string $element
     * @param string $key
     *
     * @return array
     */
    public function getRenderAttributes($element = '', $key = '')
    {
        $attributes = $this->render_attributes;

        if ($element) {
            if (!isset($attributes[$element])) {
                return null;
            }

            $attributes = $attributes[$element];

            if ($key) {
                if (!isset($attributes[$key])) {
                    return null;
                }

                $attributes = $attributes[$key];
            }
        }

        return $attributes;
    }

    /**
     * Set render attribute.
     *
     * Used to set the value of the HTML element render attribute or to update
     * an existing render attribute.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array|string $element The HTML element.
     * @param array|string $key     Optional. Attribute key. Default is null.
     * @param array|string $value   Optional. Attribute value. Default is null.
     *
     * @return ElementBase Current instance of the element.
     */
    public function setRenderAttribute($element, $key = null, $value = null)
    {
        return $this->addRenderAttribute($element, $key, $value, true);
    }

    /**
     * Get render attribute string.
     *
     * Used to retrieve the value of the render attribute.
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $element The element.
     *
     * @return string Render attribute string, or an empty string if the attribute
     *                is empty or not exist.
     */
    public function getRenderAttributeString($element)
    {
        if (empty($this->render_attributes[$element])) {
            return '';
        }

        return Utils::renderHtmlAttributes($this->render_attributes[$element]);
    }

    /**
     * Print render attribute string.
     *
     * Used to output the rendered attribute.
     *
     * @since 2.0.0
     * @access public
     *
     * @param array|string $element The element.
     */
    public function printRenderAttributeString($element)
    {
        echo $this->getRenderAttributeString($element); // XSS ok.
    }

    /**
     * Print element.
     *
     * Used to generate the element final HTML on the frontend and the editor.
     *
     * @since 1.0.0
     * @access public
     */
    public function printElement()
    {
        $element_type = $this->getType();

        /**
         * Before frontend element render.
         *
         * Fires before Elementor element is rendered in the frontend.
         *
         * @since 2.2.0
         *
         * @param ElementBase $this The element.
         */
        do_action('elementor/frontend/before_render', $this);

        /**
         * Before frontend element render.
         *
         * Fires before Elementor element is rendered in the frontend.
         *
         * The dynamic portion of the hook name, `$element_type`, refers to the element type.
         *
         * @since 1.0.0
         *
         * @param ElementBase $this The element.
         */
        do_action("elementor/frontend/{$element_type}/before_render", $this);

        ob_start();
        $this->_printContent();
        $content = ob_get_clean();

        $should_render = (!empty($content) || $this->shouldPrintEmpty());

        /**
         * Should the element be rendered for frontend
         *
         * Filters if the element should be rendered on frontend.
         *
         * @since 2.3.3
         *
         * @param bool true The element.
         * @param ElementBase $this The element.
         */
        $should_render = apply_filters("elementor/frontend/{$element_type}/should_render", $should_render, $this);

        if ($should_render) {
            $this->_addRenderAttributes();

            $this->beforeRender();
            echo $content;
            $this->afterRender();

            // $this->enqueueScripts();
            // $this->enqueueStyles();
            add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
            add_action('wp_enqueue_scripts', [$this, 'enqueueStyles']);
        }

        /**
         * After frontend element render.
         *
         * Fires after Elementor element is rendered in the frontend.
         *
         * The dynamic portion of the hook name, `$element_type`, refers to the element type.
         *
         * @since 1.0.0
         *
         * @param ElementBase $this The element.
         */
        do_action("elementor/frontend/{$element_type}/after_render", $this);

        /**
         * After frontend element render.
         *
         * Fires after Elementor element is rendered in the frontend.
         *
         * @since 2.3.0
         *
         * @param ElementBase $this The element.
         */
        do_action('elementor/frontend/after_render', $this);
    }

    /**
     * Get the element raw data.
     *
     * Retrieve the raw element data, including the id, type, settings, child
     * elements and whether it is an inner element.
     *
     * The data with the HTML used always to display the data, but the Elementor
     * editor uses the raw data without the HTML in order not to render the data
     * again.
     *
     * @since 1.0.0
     * @access public
     *
     * @param bool $with_html_content Optional. Whether to return the data with
     *                                HTML content or without. Used for caching.
     *                                Default is false, without HTML.
     *
     * @return array Element raw data.
     */
    public function getRawData($with_html_content = false)
    {
        $data = $this->getData();

        $elements = [];

        foreach ($this->getChildren() as $child) {
            $elements[] = $child->getRawData($with_html_content);
        }

        return [
            'id' => $this->getId(),
            'elType' => $data['elType'],
            'settings' => $data['settings'],
            'elements' => $elements,
            'isInner' => $data['isInner'],
        ];
    }

    /**
     * Get unique selector.
     *
     * Retrieve the unique selector of the element. Used to set a unique HTML
     * class for each HTML element. This way Elementor can set custom styles for
     * each element.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Unique selector.
     */
    public function getUniqueSelector()
    {
        return '.elementor-element-' . $this->getId();
    }

    /**
     * Render element edit tools.
     *
     * Used to generate the edit tools HTML.
     *
     * @since 1.8.0
     * @access protected
     */
    protected function renderEditTools()
    {
        ?>
        <div class="elementor-element-overlay">
            <ul class="elementor-editor-element-settings elementor-editor-<?= $this->getType() ?>-settings">
            <?php foreach (self::getEditTools() as $edit_tool_name => $edit_tool) : ?>
                <li class="elementor-editor-element-setting elementor-editor-element-<?= esc_attr($edit_tool_name) ?>"
                    title="<?= esc_attr($edit_tool['title']) ?>">
                    <i class="eicon-<?= esc_attr($edit_tool['icon']) ?>" aria-hidden="true"></i>
                    <span class="elementor-screen-only"><?= esc_html($edit_tool['title']) ?></span>
                </li>
            <?php endforeach ?>
            </ul>
        </div>
        <?php
    }

    /**
     * Is type instance.
     *
     * Used to determine whether the element is an instance of that type or not.
     *
     * @since 1.0.0
     * @access public
     *
     * @return bool Whether the element is an instance of that type.
     */
    public function isTypeInstance()
    {
        return $this->is_type_instance;
    }

    /**
     * Add render attributes.
     *
     * Used to add attributes to the current element wrapper HTML tag.
     *
     * @since 1.3.0
     * @access protected
     */
    protected function _addRenderAttributes()
    {
        $id = $this->getId();

        $settings = $this->getSettingsForDisplay();
        $frontend_settings = $this->getFrontendSettings();
        $controls = $this->getControls();

        $this->addRenderAttribute('_wrapper', [
            'class' => [
                'elementor-element',
                'elementor-element-' . $id,
            ],
            'data-id' => $id,
            'data-element_type' => $this->getType(),
        ]);

        $class_settings = [];

        foreach ($settings as $setting_key => $setting) {
            if (isset($controls[$setting_key]['prefix_class'])) {
                $class_settings[$setting_key] = $setting;
            }
        }

        foreach ($class_settings as $setting_key => $setting) {
            if (empty($setting) && '0' !== $setting) {
                continue;
            }

            $this->addRenderAttribute('_wrapper', 'class', $controls[$setting_key]['prefix_class'] . $setting);
        }

        if (!empty($settings['animation']) || !empty($settings['_animation'])) {
            // Hide the element until the animation begins
            $this->addRenderAttribute('_wrapper', 'class', 'elementor-invisible');
        }

        if (!empty($settings['_element_id'])) {
            $this->addRenderAttribute('_wrapper', 'id', trim($settings['_element_id']));
        }

        if ($frontend_settings) {
            $this->addRenderAttribute('_wrapper', 'data-settings', json_encode($frontend_settings));
        }

        /**
         * After element attribute rendered.
         *
         * Fires after the attributes of the element HTML tag are rendered.
         *
         * @since 2.3.0
         *
         * @param ElementBase $this The element.
         */
        do_action('elementor/element/after_add_attributes', $this);
    }

    /**
     * Get default data.
     *
     * Retrieve the default element data. Used to reset the data on initialization.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return array Default data.
     */
    protected function getDefaultData()
    {
        $data = parent::getDefaultData();

        return array_merge($data, [
            'elements' => [],
            'isInner' => false,
        ]);
    }

    /**
     * Print element content.
     *
     * Output the element final HTML on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _printContent()
    {
        foreach ($this->getChildren() as $child) {
            $child->printElement();
        }
    }

    /**
     * Get initial config.
     *
     * Retrieve the current element initial configuration.
     *
     * Adds more configuration on top of the controls list and the tabs assigned
     * to the control. This method also adds element name, type, icon and more.
     *
     * @since 1.0.10
     * @access protected
     *
     * @return array The initial config.
     */
    protected function _getInitialConfig()
    {
        $config = [
            'name' => $this->getName(),
            'elType' => $this->getType(),
            'title' => $this->getTitle(),
            'icon' => $this->getIcon(),
            'reload_preview' => $this->isReloadPreviewRequired(),
        ];

        return $config;
    }

    /**
     * Get child type.
     *
     * Retrieve the element child type based on element data.
     *
     * @since 2.0.0
     * @access private
     *
     * @param array $element_data Element ID.
     *
     * @return ElementBase|false Child type or false if type not found.
     */
    private function getChildType($element_data)
    {
        $child_type = $this->_getDefaultChildType($element_data);

        // If it's not a valid widget ( like a deactivated plugin )
        if (!$child_type) {
            return false;
        }

        /**
         * Element child type.
         *
         * Filters the child type of the element.
         *
         * @since 1.0.0
         *
         * @param ElementBase $child_type   The child element.
         * @param array        $element_data The original element ID.
         * @param ElementBase $this         The original element.
         */
        $child_type = apply_filters('elementor/element/get_child_type', $child_type, $element_data, $this);

        return $child_type;
    }

    /**
     * Initialize children.
     *
     * Initializing the element child elements.
     *
     * @since 2.0.0
     * @access private
     */
    private function initChildren()
    {
        $this->children = [];

        $children_data = $this->getData('elements');

        if (!$children_data) {
            return;
        }

        foreach ($children_data as $child_data) {
            if (!$child_data) {
                continue;
            }

            $this->addChild($child_data);
        }
    }

    /**
     * Element base constructor.
     *
     * Initializing the element base class using `$data` and `$args`.
     *
     * The `$data` parameter is required for a normal instance because of the
     * way Elementor renders data when initializing elements.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array      $data Optional. Element data. Default is an empty array.
     * @param array|null $args Optional. Element default arguments. Default is null.
     */
    public function __construct(array $data = [], array $args = null)
    {
        if ($data) {
            $this->is_type_instance = false;
        } elseif ($args) {
            $this->default_args = $args;
        }

        parent::__construct($data);
    }
}
