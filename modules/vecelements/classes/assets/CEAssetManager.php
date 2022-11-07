<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

use PrestaShop\PrestaShop\Adapter\Configuration as ConfigurationAdapter;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;
use Symfony\Component\Filesystem\Filesystem;

defined('_PS_VERSION_') or exit;

require_once _VEC_PATH_ . 'classes/assets/CEStylesheetManager.php';
require_once _VEC_PATH_ . 'classes/assets/CEJavascriptManager.php';
require_once _VEC_PATH_ . 'classes/assets/CECccReducer.php';

class CEAssetManager
{
    protected $controller;

    protected $stylesheetManager;
    protected $javascriptManager;

    protected $template;

    public static function instance()
    {
        static $instance;

        if (null === $instance) {
            $instance = new self();

            VEC\add_action('wp_head', 'wp_enqueue_scripts', 1);
            VEC\do_action('wp_register_scripts');
        }
        return $instance;
    }

    protected function __construct()
    {
        $ctx = Context::getContext();
        $config = new ConfigurationAdapter();
        $this->controller = $ctx->controller;

        $managers = [
            'stylesheetManager' => 'CEStylesheetManager',
            'javascriptManager' => 'CEJavascriptManager',
        ];
        foreach ($managers as $prop => $class) {
            $managerRef = new ReflectionProperty($this->controller, $prop);
            $managerRef->setAccessible(true);
            $manager = $managerRef->getValue($this->controller);

            $listRef = new ReflectionProperty($manager, 'list');
            $listRef->setAccessible(true);

            $this->$prop = new $class([
                _PS_THEME_URI_,
                _PS_PARENT_THEME_URI_,
                __PS_BASE_URI__,
            ], $config, $listRef->getValue($manager));

            $managerRef->setValue($this->controller, $this->$prop);
        }

        $reducerRef = new ReflectionProperty($this->controller, 'cccReducer');
        $reducerRef->setAccessible(true);
        $this->cccReducer = new CECccReducer(
            _PS_THEME_DIR_ . 'assets/cache/',
            $config,
            new Filesystem()
        );
        $reducerRef->setValue($this->controller, $this->cccReducer);

        $this->template = &Closure::bind(function &() {
            return ${'this'}->template;
        }, $this->controller, $this->controller)->__invoke();

        $ctx->smarty->registerPlugin('modifier', 'ce' . 'filter', [$this, 'modifierFilter']);
        $ctx->smarty->registerFilter('output', [$this, 'outputFilter']);
    }

    public function registerStylesheet($id, $path, $params = [])
    {
        static $ccc;

        if (empty($params['server']) || 'remote' !== $params['server']) {
            $separator = strrpos($path, '?') === false ? '?' : '&';

            is_null($ccc) && $ccc = \Configuration::get('PS_CSS_THEME_CACHE');

            if (!$ccc) {
                if (!empty($params['ver']) || '&' === $separator) {
                    $params['server'] = 'remote';

                    $path = __PS_BASE_URI__ . $path;
                    empty($params['ver']) or $path .= "{$separator}v={$params['ver']}";
                }
            } elseif ('&' === $separator) {
                $path = explode('?', $path, 2)[0];
            }
        }
        $this->controller->registerStylesheet($id, $path, $params);
    }

    public function registerJavascript($id, $path, $params = [])
    {
        static $ccc;

        if (empty($params['server']) || 'remote' !== $params['server']) {
            $separator = strrpos($path, '?') === false ? '?' : '&';

            is_null($ccc) && $ccc = Configuration::get('PS_JS_THEME_CACHE');

            if (!$ccc) {
                if (!empty($params['ver']) || '&' === $separator) {
                    $params['server'] = 'remote';

                    $path = __PS_BASE_URI__ . $path;
                    empty($params['ver']) or $path .= "{$separator}v={$params['ver']}";
                }
            } elseif ('&' === $separator) {
                $path = explode('?', $path, 2)[0];
            }
        }
        $this->controller->registerJavascript($id, $path, $params);
    }

    public function modifierFilter($str)
    {
        echo $str;
    }

    public function outputFilter($out, $tpl)
    {
        if ($this->template === $tpl->template_resource || 'errors/maintenance.tpl' === $tpl->template_resource) {
            $assets = $this->fetchAssets();

            if (false !== $pos = stripos($out, '<script')) {
                $out = substr_replace($out, $assets->head, $pos, 0);
            } elseif (false !== $pos = stripos($out, '</head')) {
                $out = substr_replace($out, $assets->head, $pos, 0);
            }

            if (false !== $pos = strripos($out, '</footer>')) {
                $out = substr_replace($out, $assets->bottom, $pos + 9, 0);
            } elseif (false !== $pos = strripos($out, '</body')) {
                $out = substr_replace($out, $assets->bottom, $pos, 0);
            }
        }
        return $out;
    }

    public function fetchAssets()
    {
        $smarty = Context::getContext()->smarty;
        $assets = new stdClass();

        ob_start();
        VEC\do_action('wp_head');
        $assets->head = ob_get_clean();

        ob_start();
        VEC\do_action('wp_footer');
        $assets->bottom = ob_get_clean();

        $styles = $this->stylesheetManager->listAll();

        foreach ($styles['external'] as $id => &$style) {
            if (isset(VEC\Helper::$inline_styles[$id])) {
                $styles['inline'][$id] = [
                    'content' => implode("\n", VEC\Helper::$inline_styles[$id])
                ];
            }
        }

        $scripts = $this->javascriptManager->listAll();
        $js_defs = Media::getJsDef();

        if (!empty($smarty->tpl_vars['js_custom_vars'])) {
            foreach ($smarty->tpl_vars['js_custom_vars']->value as $key => &$val) {
                unset($js_defs[$key]);
            }
        }

        Configuration::get('PS_CSS_THEME_CACHE') && $styles = $this->cccReducer->reduceCss($styles);
        Configuration::get('PS_JS_THEME_CACHE') && $scripts = $this->cccReducer->reduceJs($scripts);

        $smarty->assign([
            'stylesheets' => &$styles,
            'javascript' => &$scripts['head'],
            'js_custom_vars' => &$js_defs,
        ]);
        $assets->head = $smarty->fetch(_VEC_TEMPLATES_ . 'front/theme/_partials/assets.tpl') . $assets->head;

        $smarty->assign([
            'stylesheets' => [],
            'javascript' => &$scripts['bottom'],
            'js_custom_vars' => [],
        ]);
        $assets->bottom = $smarty->fetch(_VEC_TEMPLATES_ . 'front/theme/_partials/assets.tpl') . $assets->bottom;

        return $assets;
    }
}
