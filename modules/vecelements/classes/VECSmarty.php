<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

class VECSmarty
{
    protected static $tpls = [];

    protected static function getTemplate($path)
    {
        if (isset(self::$tpls[$path])) {
            return self::$tpls[$path];
        }

        $tpl = Context::getContext()->smarty->createTemplate($path);
        VEC\do_action('smarty/before_fetch', $tpl->smarty);
        $tpl->fetch();
        VEC\do_action('smarty/after_fetch', $tpl->smarty);

        return self::$tpls[$path] = $tpl;
    }

    public static function call($path, $func, $params = [], $nocache = true)
    {
        $tpl = self::getTemplate($path);
        VEC\do_action('smarty/before_call', $tpl->smarty);
        isset($tpl->smarty->ext->_tplFunction)
            ? $tpl->smarty->ext->_tplFunction->callTemplateFunction($tpl, $func, $params, $nocache)
            : call_user_func("smarty_template_function_$func", $tpl, $params)
        ;
        VEC\do_action('smarty/after_call', $tpl->smarty);
    }

    public static function capture($path, $func, $params = [], $nocache = true)
    {
        ob_start();

        self::call($path, $func, $params, $nocache);

        return ob_get_clean();
    }

    public static function get($path, $buffer)
    {
        $tpl = self::getTemplate($path);

        return isset($tpl->smarty->ext->_capture)
            ? $tpl->smarty->ext->_capture->getBuffer($tpl, $buffer)
            : Smarty::$_smarty_vars['capture'][$buffer]
        ;
    }

    public static function write($path, $buffer)
    {
        $tpl = self::getTemplate($path);

        echo isset($tpl->smarty->ext->_capture)
            ? $tpl->smarty->ext->_capture->getBuffer($tpl, $buffer)
            : Smarty::$_smarty_vars['capture'][$buffer]
        ;
    }

    public static function printf($path, $buffer)
    {
        $args = func_get_args();
        array_shift($args);
        $args[0] = self::get($path, $buffer);

        call_user_func_array(__FUNCTION__, $args);
    }

    public static function sprintf($path, $buffer)
    {
        $args = func_get_args();
        array_shift($args);
        $args[0] = self::get($path, $buffer);

        return call_user_func_array(__FUNCTION__, $args);
    }
}

function ce__($text, $module = 'vecelements')
{
    return VEC\translate($text, $module);
}
