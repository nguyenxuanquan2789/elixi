<?php

use \CE\Plugin;

class AdminVecThemeoptionsController extends ModuleAdminController {

	private $images;
    private $templates;
    private $destination = _PS_IMG_DIR_.'cms/';
    private $pagebuilder_module = 'creativeelements';

    public function __construct()
    {
        parent::__construct();
        $this->templates = 'https://tungxu.site/prestashop/import-data/';
		if ((bool)Tools::getValue('ajax')){
			$this->ajaxImportData(Tools::getValue('layout'));
		}else{
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').'&configure=vecthemeoptions');
		}
        
    }

    function ajaxImportData($layout){

    	// require_once _PS_MODULE_DIR_.$this->pagebuilder_module.'/'.$this->pagebuilder_module.'.php';
    	// $files = array(
    	// 	'header.json', 'home.json', 'footer.json'
    	// );
        
     //    foreach ($files as $file)
     //    {
     //        $_FILES['file']['tmp_name'] = $this->templates. $layout. '/'. $file;
     //        $response = \CE\Plugin::instance()->templates_manager->importTemplate();

     //        if (is_object($response))
     //        {
     //            die('Error when import templates');
     //        }
     //    }
        
        $prefixname  = 'vecthemeoptions';
    	if($layout == 'digital1'){
    		//Theme settings
    		Configuration::updateValue($prefixname . 'p_display', 1);
            $images = array(
                1 => 'http://demo2.posthemes.com/pos_vasia/layout2/42-home_default/the-adventure-begins-framed-poster.jpg',
                2 => 'http://demo2.posthemes.com/pos_vasia/layout2/84-home_default/premium-long-sleeve-shirt.jpg',
            );
    	}
    	if($layout == 'digital2'){
    		//Theme settings
    		Configuration::updateValue($prefixname . 'p_display', 2);
    		$images = array(
                1 => 'http://demo2.posthemes.com/pos_vasia/layout2/57-home_default/men-pocketable-parka.jpg',
                2 => 'http://demo2.posthemes.com/pos_vasia/layout2/96-home_default/scarf-print-revere-shirt.jpg',
            );
    	}
    	if($layout == 'digital3'){
    		//Theme settings
    		Configuration::updateValue($prefixname . 'p_display', 3);
    		$images = array(
                1 => 'http://demo2.posthemes.com/pos_vasia/layout2/72-home_default/oversized-crew-neck-t-shirt.jpg',
                2 => 'http://demo2.posthemes.com/pos_vasia/layout2/76-home_default/mountain-fox-vector-graphics.jpg',
            );
    	}
        $error = false;
        foreach($images as $image){
            if(! $this->importImageFromURL($image, false)){
                $error = true;
            }
        }
	
    	$this->ajaxDie(json_encode(array(
            'success' => true,
            'data' => [
                'message' => $error ? $this->l('Error with import images.') : $this->l('Import successfully'),
            ]
        )));
    }

    protected function importImageFromURL($url, $regenerate = true)
    {
        $origin_image = pathinfo($url);
        $origin_name = $origin_image['filename'];
        $tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');
  
        $path = _PS_IMG_DIR_ . 'cms/';

        $url = urldecode(trim($url));
        $parced_url = parse_url($url);

        if (isset($parced_url['path'])) {
            $uri = ltrim($parced_url['path'], '/');
            $parts = explode('/', $uri);
            foreach ($parts as &$part) {
                $part = rawurlencode($part);
            }
            unset($part);
            $parced_url['path'] = '/' . implode('/', $parts);
        }

        if (isset($parced_url['query'])) {
            $query_parts = [];
            parse_str($parced_url['query'], $query_parts);
            $parced_url['query'] = http_build_query($query_parts);
        }

        if (!function_exists('http_build_url')) {
            require_once _PS_TOOL_DIR_ . 'http_build_url/http_build_url.php';
        }

        $url = http_build_url('', $parced_url);

        $orig_tmpfile = $tmpfile;

        if (Tools::copy($url, $tmpfile)) {
            // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
            if (!ImageManager::checkImageMemoryLimit($tmpfile)) {
                @unlink($tmpfile);

                return false;
            }

            $tgt_width = $tgt_height = 0;
            $src_width = $src_height = 0;
            $error = 0;
            ImageManager::resize($tmpfile, $path . $origin_name .'.jpg', null, null, 'jpg', false, $error, $tgt_width, $tgt_height, 5, $src_width, $src_height);
   
        } else {
            echo 'cant copy image';
            @unlink($orig_tmpfile);

            return false;
        }
        unlink($orig_tmpfile);

        return true;
    }
}
