<?php
if (!defined('_PS_VERSION_')) {
	exit;
}

function upgrade_module_4_0_5($object)
{

	Configuration::updateGlobalValue('sborderby', 'id_smart_blog_post');
	Configuration::updateGlobalValue('sborder', 'DESC');
	
	return true;
}