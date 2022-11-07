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

use VEC\CoreXCommonXApp as CommonApp;
use VEC\CoreXDocumentsManager as DocumentsManager;
use VEC\CoreXFilesXManager as FilesManager;
use VEC\CoreXModulesManager as ModulesManager;
use VEC\CoreXSettingsXManager as SettingsManager;
use VEC\CoreXSettingsXPageXManager as PageSettingsManager;
use VEC\ModulesXHistoryXRevisionsManager as RevisionsManager;

require_once _VEC_PATH_ . 'classes/wrappers/Helper.php';

/**
 * Elementor plugin.
 *
 * The main plugin handler class is responsible for initializing Elementor. The
 * class registers and all the components required to run the plugin.
 *
 * @since 1.0.0
 */
class Plugin
{
    /**
     * Instance.
     *
     * Holds the plugin instance.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @var Plugin
     */
    public static $instance = null;

    /**
     * Database.
     *
     * Holds the plugin database.
     *
     * @since 1.0.0
     * @access public
     *
     * @var DB
     */
    public $db;

    /**
     * Controls manager.
     *
     * Holds the plugin controls manager.
     *
     * @since 1.0.0
     * @access public
     *
     * @var ControlsManager
     */
    public $controls_manager;

    /**
     * Documents manager.
     *
     * Holds the documents manager.
     *
     * @since 2.0.0
     * @access public
     *
     * @var DocumentsManager
     */
    public $documents;

    /**
     * Schemes manager.
     *
     * Holds the plugin schemes manager.
     *
     * @since 1.0.0
     * @access public
     *
     * @var SchemesManager
     */
    public $schemes_manager;

    /**
     * Elements manager.
     *
     * Holds the plugin elements manager.
     *
     * @since 1.0.0
     * @access public
     *
     * @var ElementsManager
     */
    public $elements_manager;

    /**
     * Widgets manager.
     *
     * Holds the plugin widgets manager.
     *
     * @since 1.0.0
     * @access public
     *
     * @var WidgetsManager
     */
    public $widgets_manager;

    /**
     * Revisions manager.
     *
     * Holds the plugin revisions manager.
     *
     * @since 1.0.0
     * @access public
     *
     * @var RevisionsManager
     */
    public $revisions_manager;

    /**
     * Maintenance mode.
     *
     * Holds the plugin maintenance mode.
     *
     * @since 1.0.0
     * @access public
     *
     * @var MaintenanceMode
     */
    public $maintenance_mode;

    /**
     * Page settings manager.
     *
     * Holds the page settings manager.
     *
     * @since 1.0.0
     * @access public
     *
     * @var PageSettingsManager
     */
    public $page_settings_manager;

    /**
     * Dynamic tags manager.
     *
     * Holds the dynamic tags manager.
     *
     * @since 1.0.0
     * @access public
     *
     * @var DynamicTagsManager
     */
    public $dynamic_tags;

    // public $settings;

    // public $role_manager;

    // public $admin;

    // public $tools;

    /**
     * Preview.
     *
     * Holds the plugin preview.
     *
     * @since 1.0.0
     * @access public
     *
     * @var Preview
     */
    public $preview;

    /**
     * Editor.
     *
     * Holds the plugin editor.
     *
     * @since 1.0.0
     * @access public
     *
     * @var Editor
     */
    public $editor;

    /**
     * Frontend.
     *
     * Holds the plugin frontend.
     *
     * @since 1.0.0
     * @access public
     *
     * @var Frontend
     */
    public $frontend;

    /**
     * Heartbeat.
     *
     * Holds the plugin heartbeat.
     *
     * @since 1.0.0
     * @access public
     *
     * @var Heartbeat
     */
    public $heartbeat;

    /**
     * Template library manager.
     *
     * Holds the template library manager.
     *
     * @since 1.0.0
     * @access public
     *
     * @var TemplateLibrary\Manager
     */
    public $templates_manager;

    /**
     * Skins manager.
     *
     * Holds the skins manager.
     *
     * @since 1.0.0
     * @access public
     *
     * @var SkinsManager
     */
    public $skins_manager;

    /**
     * Files Manager.
     *
     * Holds the files manager.
     *
     * @since 2.1.0
     * @access public
     *
     * @var FilesManager
     */
    public $files_manager;

    /**
     * Modules manager.
     *
     * Holds the modules manager.
     *
     * @since 1.0.0
     * @access public
     *
     * @var ModulesManager
     */
    public $modules_manager;

    // public $debugger;

    // public $inspector;

    /**
     * @var CommonApp
     */
    public $common;

    // public $logger;

    // public $upgrade;

    /**
     * Clone.
     *
     * Disable class cloning and throw an error on object clone.
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object. Therefore, we don't want the object to be cloned.
     *
     * @access public
     * @since 1.0.0
     */
    public function __clone()
    {
        // Cloning instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Something went wrong.'), '1.0.0');
    }

    /**
     * Wakeup.
     *
     * Disable unserializing of the class.
     *
     * @access public
     * @since 1.0.0
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Something went wrong.'), '1.0.0');
    }

    /**
     * Instance.
     *
     * Ensures only one instance of the plugin class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return Plugin An instance of the class.
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();

            /**
             * Elementor loaded.
             *
             * Fires when Elementor was fully loaded and instantiated.
             *
             * @since 1.0.0
             */
            do_action('elementor/loaded');

            do_action('init');
        }
        return self::$instance;
    }

    /**
     * Init.
     *
     * Initialize Elementor Plugin. Register Elementor support for all the
     * supported post types and initialize Elementor components.
     *
     * @since 1.0.0
     * @access public
     */
    public function init()
    {
        // $this->addCptSupport();

        $this->initComponents();

        /**
         * Elementor init.
         *
         * Fires on Elementor init, after Elementor has finished loading but
         * before any headers are sent.
         *
         * @since 1.0.0
         */
        do_action('elementor/init');

        \Hook::exec('actionVecElementsInit');
    }

    // public function onRestApiInit();

    /**
     * Init components.
     *
     * Initialize Elementor components. Register actions, run setting manager,
     * initialize all the components that run elementor, and if in admin page
     * initialize admin components.
     *
     * @since 1.0.0
     * @access private
     */
    private function initComponents()
    {
        // $this->inspector = new Inspector();
        // $this->debugger = $this->inspector;

        SettingsManager::run();

        $this->db = new DB();
        $this->controls_manager = new ControlsManager();
        $this->documents = new DocumentsManager();
        $this->schemes_manager = new SchemesManager();
        $this->elements_manager = new ElementsManager();
        $this->widgets_manager = new WidgetsManager();
        $this->skins_manager = new SkinsManager();
        $this->files_manager = new FilesManager();
        // $this->settings = new Settings();
        // $this->tools = new Tools();
        $this->editor = new Editor();
        $this->preview = new Preview();
        $this->frontend = new Frontend();
        $this->templates_manager = new TemplateLibraryXManager();
        // $this->maintenance_mode = new MaintenanceMode();
        // $this->dynamic_tags = new DynamicTagsManager();
        $this->modules_manager = new ModulesManager();
        // $this->role_manager = new Core\RoleManager\Role_Manager();
        // $this->system_info = new System_Info\Main();
        $this->revisions_manager = new RevisionsManager();

        User::init();
        Api::init();
        // Tracker::init();

        // $this->upgrade = new Core\Upgrade\Manager();

        if (is_admin()) {
            $this->heartbeat = new Heartbeat();
            // $this->wordpress_widgets_manager = new WordPressWidgetsManager();
            // $this->admin = new Admin();
            // $this->beta_testers = new BetaTesters();

            // if (Utils::isAjax()) {
            //     new ImagesManager();
            // }
            $this->initCommon();
        }
    }

    /**
     * @since 2.3.0
     * @access public
     */
    public function initCommon()
    {
        $this->common = new CommonApp();

        $this->common->initComponents();
    }

    // private function addCptSupport()

    /**
     * Register autoloader.
     *
     * Elementor autoloader loads all the classes needed to run the plugin.
     *
     * @since 1.6.0
     * @access private
     */
    private function registerAutoloader()
    {
        require _VEC_PATH_ . '/includes/autoloader.php';

        Autoloader::run();
    }

    /**
     * Plugin constructor.
     *
     * Initializing Elementor plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private function __construct()
    {
        $this->registerAutoloader();

        // $this->logger = LogManager::instance();

        // Maintenance::init();
        // Compatibility::registerActions();

        add_action('init', [$this, 'init'], 0);
        // add_action('rest_api_init', [$this, 'on_rest_api_init']);
    }
}
