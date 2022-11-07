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
 * Elementor elements manager.
 *
 * Elementor elements manager handler class is responsible for registering and
 * initializing all the supported elements.
 *
 * @since 1.0.0
 */
class ElementsManager
{
    /**
     * Element types.
     *
     * Holds the list of all the element types.
     *
     * @access private
     *
     * @var ElementBase[]
     */
    private $_element_types;

    /**
     * Element categories.
     *
     * Holds the list of all the element categories.
     *
     * @access private
     *
     * @var
     */
    private $categories;

    /**
     * Elements constructor.
     *
     * Initializing Elementor elements manager.
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        $this->requireFiles();
    }

    /**
     * Create element instance.
     *
     * This method creates a new element instance for any given element.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array        $element_data Element data.
     * @param array        $element_args Optional. Element arguments. Default is
     *                                   an empty array.
     * @param ElementBase $element_type Optional. Element type. Default is null.
     *
     * @return ElementBase|null Element instance if element created, or null
     *                           otherwise.
     */
    public function createElementInstance(array $element_data, array $element_args = [], ElementBase $element_type = null)
    {
        if (null === $element_type) {
            if ('widget' === $element_data['elType']) {
                $element_type = Plugin::$instance->widgets_manager->getWidgetTypes($element_data['widgetType']);
            } else {
                $element_type = $this->getElementTypes($element_data['elType']);
            }
        }

        if (!$element_type) {
            return null;
        }

        $args = array_merge($element_type->getDefaultArgs(), $element_args);

        $element_class = $element_type->getClassName();

        try {
            $element = new $element_class($element_data, $args);
        } catch (\Exception $e) {
            return null;
        }

        return $element;
    }

    /**
     * Get element categories.
     *
     * Retrieve the list of categories the element belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Element categories.
     */
    public function getCategories()
    {
        if (null === $this->categories) {
            $this->initCategories();
        }

        return $this->categories;
    }

    /**
     * Add element category.
     *
     * Register new category for the element.
     *
     * @since 1.7.12
     * @since 2.0.0 The third parameter was deprecated.
     * @access public
     *
     * @param string $category_name       Category name.
     * @param array  $category_properties Category properties.
     */
    public function addCategory($category_name, $category_properties)
    {
        if (null === $this->categories) {
            $this->getCategories();
        }

        if (!isset($this->categories[$category_name])) {
            $this->categories[$category_name] = $category_properties;
        }
    }

    /**
     * Register element type.
     *
     * Add new type to the list of registered types.
     *
     * @since 1.0.0
     * @access public
     *
     * @param ElementBase $element Element instance.
     *
     * @return bool Whether the element type was registered.
     */
    public function registerElementType(ElementBase $element)
    {
        $this->_element_types[$element->getName()] = $element;

        return true;
    }

    /**
     * Unregister element type.
     *
     * Remove element type from the list of registered types.
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $name Element name.
     *
     * @return bool Whether the element type was unregister, or not.
     */
    public function unregisterElementType($name)
    {
        if (!isset($this->_element_types[$name])) {
            return false;
        }

        unset($this->_element_types[$name]);

        return true;
    }

    /**
     * Get element types.
     *
     * Retrieve the list of all the element types, or if a specific element name
     * was provided retrieve his element types.
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $element_name Optional. Element name. Default is null.
     *
     * @return null|ElementBase|ElementBase[] Element types, or a list of all the element
     *                             types, or null if element does not exist.
     */
    public function getElementTypes($element_name = null)
    {
        if (is_null($this->_element_types)) {
            $this->initElements();
        }

        if (null !== $element_name) {
            return isset($this->_element_types[$element_name]) ? $this->_element_types[$element_name] : null;
        }

        return $this->_element_types;
    }

    /**
     * Get element types config.
     *
     * Retrieve the config of all the element types.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Element types config.
     */
    public function getElementTypesConfig()
    {
        $config = [];

        foreach ($this->getElementTypes() as $element) {
            $config[$element->getName()] = $element->getConfig();
        }

        return $config;
    }

    /**
     * Render elements content.
     *
     * Used to generate the elements templates on the editor.
     *
     * @since 1.0.0
     * @access public
     */
    public function renderElementsContent()
    {
        foreach ($this->getElementTypes() as $element_type) {
            $element_type->printTemplate();
        }
    }

    /**
     * Init elements.
     *
     * Initialize Elementor elements by registering the supported elements.
     * Elementor supports by default `section` element and `column` element.
     *
     * @since 2.0.0
     * @access private
     */
    private function initElements()
    {
        $this->_element_types = [];

        foreach (['section', 'column'] as $element_name) {
            $class_name = __NAMESPACE__ . '\Element' . $element_name;

            $this->registerElementType(new $class_name());
        }

        /**
         * After elements registered.
         *
         * Fires after Elementor elements are registered.
         *
         * @since 1.0.0
         */
        do_action('elementor/elements/elements_registered');
    }

    /**
     * Init categories.
     *
     * Initialize the element categories.
     *
     * @since 1.7.12
     * @access private
     */
    private function initCategories()
    {
        $this->categories = [
            'basic' => [
                'title' => __('Basic'),
                'icon' => 'eicon-font',
            ],
            'general' => [
                'title' => __('General'),
                'icon' => 'eicon-font',
            ],
            'premium' => [
                'title' => __('Advanced'),
                'icon' => 'fa fa-shopping-bag',
            ],
            'theme-elements' => [
                'title' => __('Site'),
                'active' => false,
            ],
        ];

        /**
         * When categories are registered.
         *
         * Fires after basic categories are registered, before PrestaShop
         * category have been registered.
         *
         * This is where categories registered by external developers are
         * added.
         *
         * @since 2.0.0
         *
         * @param ElementsManager $this Elements manager instance.
         */
        do_action('elementor/elements/categories_registered', $this);
    }

    /**
     * Require files.
     *
     * Require Elementor element base class and column, section and repeater
     * elements.
     *
     * @since 1.0.0
     * @access private
     */
    private function requireFiles()
    {
        require_once _VEC_PATH_ . 'includes/base/element-base.php';

        require _VEC_PATH_ . 'includes/elements/column.php';
        require _VEC_PATH_ . 'includes/elements/section.php';
        require _VEC_PATH_ . 'includes/elements/repeater.php';
    }
}
