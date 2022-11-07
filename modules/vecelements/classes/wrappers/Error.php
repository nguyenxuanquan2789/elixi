<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

class WPError
{
    private $code;

    private $message;

    public function __construct($code = '', $message = '', $data = '')
    {
        if (!$code) {
            return;
        }
        if ($data) {
            throw new \RuntimeException('todo');
        }
        $this->code = $code;
        $this->message = $message;
    }

    public function getErrorMessage($code = '')
    {
        if (!$this->code) {
            return '';
        }
        if ($code) {
            throw new \RuntimeException('todo');
        }
        return $this->code . ($this->message ? " - {$this->message}" : '');
    }
}

function is_wp_error($error)
{
    return $error instanceof WPError;
}

function _doing_it_wrong($function, $message = '', $version = '')
{
    die(\Tools::displayError($function . ' was called incorrectly. ' . $message . ' ' . $version));
}
