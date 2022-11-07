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

class TemplateLibraryXClassesXImportImages
{
    const DIR = 'cms/';
    const PLACEHOLDER = 'placeholder.png';

    private static $imported = [];

    public function import($attachment)
    {
        static $allowed_ext = ['jpg', 'jpe', 'jpeg', 'png', 'gif', 'bmp', 'tif', 'tiff', 'svg'];

        $url = $attachment['url'];
        if (stripos($url, 'https://') === 0) {
            $url = 'http' . \Tools::substr($url, 5);
        }

        if (isset(self::$imported[$url])) {
            // Image was already imported
            return self::$imported[$url];
        }

        if (count($cms = explode('/img/cms/', $url)) > 1) {
            // Get filename with subdir
            $filename = $cms[1];
        } else {
            $filename = basename($url);

            if (self::PLACEHOLDER == $filename) {
                // Do not import placeholder
                return self::$imported[$url] = false;
            }
        }

        $file_content = wp_remote_get($url);
        if (empty($file_content)) {
            // Image isn't available
            return self::$imported[$url] = false;
        }

        $file_info = pathinfo($filename);
        if (!in_array(\Tools::strToLower($file_info['extension']), $allowed_ext)) {
            // Image extension isn't allowed
            return self::$imported[$url] = false;
        }

        if ($file_info['dirname'] !== '.' && !is_dir(_PS_IMG_DIR_ . self::DIR . $file_info['dirname'])) {
            // Create subdir
            if (!@mkdir(_PS_IMG_DIR_ . self::DIR . $file_info['dirname'], 0775, true)) {
                // Can not create subdir
                $filename = $file_info['basename'];
            }
        }

        $file_path = _PS_IMG_DIR_ . self::DIR . $filename;
        if (file_exists($file_path)) {
            // Filename already exists
            $existing_content = \Tools::file_get_contents($file_path);

            if ($file_content === $existing_content) {
                // Same image already exists
                return self::$imported[$url] = [
                    'id' => 0,
                    'url' => basename(_PS_IMG_) . '/' . self::DIR . $filename,
                ];
            }

            // Add unique filename
            $dirname = $file_info['dirname'] !== '.' && $filename !== $file_info['basename'] ? $file_info['dirname'] . '/' : '';
            $filename = $dirname . $file_info['filename'] . '_' . Utils::generateRandomString() . '.' . $file_info['extension'];
            $file_path = _PS_IMG_DIR_ . self::DIR . $filename;
        }

        if (!file_put_contents($file_path, $file_content)) {
            // Image saved successfuly
            return self::$imported[$url] = [
                'id' => 0,
                'url' => basename(_PS_IMG_) . '/' . self::DIR . $filename,
            ];
        }

        // Fallback
        return $attachment;
    }
}
