<?php
$orders = array(
    array(
        'id_order' => "ASC", 
        'order' => 'Ascending' 
    ),
    array(
        'id_order' => "DESC", 
        'order' => 'Descending' 
    )
);
$date_format = array(
        1 => array('id' =>1 , 'name' => 'October 20, 2010'),
        2 => array('id' =>2 , 'name' => '20/10/2010'),
        3 => array('id' =>3 , 'name' => '10/20/2010'),
        4 => array('id' =>4 , 'name' => '20-10-2010'),
        5 => array('id' =>5 , 'name' => '10-20-2010'),
        
    );
// start tab general
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('General'),
        'icon' => 'icon-cogs'
    ),
    'input' => array(
        array(
            'type' => 'infoheading',
            'label' => $this->l('Blog archive'),
            'name'=> 'blog-archive'
        ),
        array(
            'type'     => 'text',
            'label'    => $this->trans('Number of posts per page', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartpostperpage',
            'size'     => 15,
            'required' => true,
            'class'    => 'fixed-width-xl'
        ),
        array(
            'type'     => 'select',
            'label'    => $this->trans('Order By', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'sborderby',
            'options' => [
                'query' => $this->getOrderBylist(),
                'id' => 'id_orderby',
                'name' => 'orderby',
            ],
        ),
        array(
            'type'     => 'select',
            'label'    => $this->trans('Order', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'sborder',
            'options' => [
                'query' => $orders,
                'id' => 'id_order',
                'name' => 'order',
            ],
        ),
        array(
            'type'     => 'select',
            'label'    => $this->trans('Columns', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartpostperrow',
            'options' => [
                'query' => array(
                    array(
                        'id' => '1', 
                        'item' => '1' 
                    ),
                    array(
                        'id' => '2', 
                        'item' => '2' 
                    ),
                    array(
                        'id' => '3', 
                        'item' => '3' 
                    ),
                    array(
                        'id' => '4', 
                        'item' => '4' 
                    )
                ),
                'id' => 'id',
                'name' => 'item',
            ],
        ),
        array(
            'type'     => 'switch',
            'label'    => $this->trans('Show Category', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartdisablecatimg',
            'required' => false,
            'desc'     => 'Show category image and description on category page',
            'is_bool'  => true,
            'values'   => array(
                array(
                    'id'    => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
                ),
                array(
                    'id'    => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
                ),
            ),
        ),
        array(
            'type' => 'infoheading',
            'label' => $this->l('Blog single'),
            'name'=> 'blog-single'
        ),
        array(
            'type'     => 'switch',
            'label'    => $this->trans('Enable Comment', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartenablecomment',
            'required' => false,
            'is_bool'  => true,
            'values'   => array(
                array(
                    'id'    => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
                ),
                array(
                    'id'    => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
                ),
            ),
        ),
        array(
            'type'     => 'switch',
            'label'    => $this->trans('Allow Guest Comment', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartenableguestcomment',
            'required' => false,
            'is_bool'  => true,
            'values'   => array(
                array(
                    'id'    => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
                ),
                array(
                    'id'    => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
                ),
            ),
        ),
        array(
            'type'     => 'switch',
            'label'    => $this->trans('Auto accepted comment', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartacceptcomment',
            'required' => false,
            'is_bool'  => true,
            'values'   => array(
                array(
                    'id'    => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
                ),
                array(
                    'id'    => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
                ),
            ),
        ),
        array(
            'type'     => 'switch',
            'label'    => $this->trans('Enable Captcha', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartcaptchaoption',
            'required' => false,
            'is_bool'  => true,
            'values'   => array(
                array(
                    'id'    => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
                ),
                array(
                    'id'    => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
                ),
            ),
        ),
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'tinymce' => true,
    'legend' => array(
        'title' => $this->l('URL & SEO'),
    ),
    'input' => array(
        array(
            'type'     => 'text',
            'label'    => $this->trans('Meta Title', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartblogmetatitle',
            'size'     => 70,
            'required' => true,
        ),
        array(
            'type'     => 'text',
            'label'    => $this->trans('Meta Keyword', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartblogmetakeyword',
            'size'     => 70,
            'required' => false,
        ),
        array(
            'type'     => 'textarea',
            'label'    => $this->trans('Meta Description', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartblogmetadescrip',
            'rows'     => 7,
            'cols'     => 66,
            'required' => true,
        ),
        array(
            'type'     => 'text',
            'label'    => $this->trans('Main Blog Url', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartmainblogurl',
            'size'     => 15,
            'required' => true,
            'desc'     => '<p class="alert alert-info"><a href="' . $this->blog_url . '">' . $this->blog_url . '</a></p>',
        ),
        array(
            'type'     => 'switch',
            'label'    => $this->trans('Use .html with Friendly Url', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartusehtml',
            'required' => false,
            'is_bool'  => true,
            'values'   => array(
                array(
                    'id'    => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
                ),
                array(
                    'id'    => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
                ),
            ),
        ),
        array(
            'type'     => 'radio',
            'label'    => $this->trans('Blog Page Url Pattern', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartblogurlpattern',
            'required' => false,
            'class'    => 't',
            'values'   => array(
                array(
                    'id'    => 'smartblogurlpattern_a',
                    'value' => 1,
                    'label' => $this->trans('alias/{slug}html ( ex: alias/share-the-love-for-prestashop-1-6.html)', [], 'Modules.Smartblog.Smartblog'),
                ),
                array(
                    'id'    => 'smartblogurlpattern_b',
                    'value' => 2,
                    'label' => $this->trans('alias/{id_post}_{slug}html ( ex: alias/1_share-the-love-for-prestashop-1-6.html)', [], 'Modules.Smartblog.Smartblog'),
                ),
            ),
        ),
        
        array(
            'type'     => 'switch',
            'label'    => $this->trans('Index in Search Engine', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartsearchengine',
            'required' => false,
            'is_bool'  => true,
            'values'   => array(
                array(
                    'id'    => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
                ),
                array(
                    'id'    => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
                ),
            ),
        ),

    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'tinymce' => true,
    'legend' => array(
        'title' => $this->l('Blog display'),
    ),
    'input' => array(
        array(
            'type' => 'image-select',
            'label' => $this->l('Blog display'),
            'name' => 'smartstyle',
            'default_value' => 1,
            'options' => array(
                'query' => array(
                    array(
                        'id_option' => 1,
                        'name' => $this->l('Style 1'),
                        'img' => 'img1.png',
                        ),
                    array(
                        'id_option' => 2,
                        'name' => $this->l('Style 2'),
                        'img' => 'img2.png',
                        ),
                    array(
                        'id_option' => 3,
                        'name' => $this->l('Style 3'),
                        'img' => 'img3.png',
                        ),
                    array(
                        'id_option' => 4,
                        'name' => $this->l('Style 4'),
                        'img' => 'img4.png',
                        ),
                ),
                'id' => 'id_option',
                'name' => 'name',
            ),
        ),
        array(
            'type'     => 'text',
            'label'    => $this->trans('Date format', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartdataformat',
            'size'     => 15,
            'required' => true,     
        ),
        array(
            'type'     => 'switch',
            'label'    => $this->trans('Show Author Name', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartshowauthor',
            'required' => false,
            'is_bool'  => true,
            'values'   => array(
                array(
                    'id'    => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
                ),
                array(
                    'id'    => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
                ),
            ),
        ),
        array(
            'type'     => 'switch',
            'label'    => $this->trans('Show Post Viewed', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartshowviewed',
            'required' => false,
            'is_bool'  => true,
            'values'   => array(
                array(
                    'id'    => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
                ),
                array(
                    'id'    => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
                ),
            ),
        ),
        array(
            'type'     => 'switch',
            'label'    => $this->trans('Show Author Name Style', [], 'Modules.Smartblog.Smartblog'),
            'desc'     => 'YES : \'First Name Last Name\'<br> NO : \'Last Name First Name\'',
            'name'     => 'smartshowauthorstyle',
            'required' => false,
            'values'   => array(
                array(
                    'id'    => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('First Name, Last Name', [], 'Modules.Smartblog.Smartblog'),
                ),
                array(
                    'id'    => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Last Name, First Name', [], 'Modules.Smartblog.Smartblog'),
                ),
            ),
        ),
        array(
            'type'     => 'switch',
            'label'    => $this->trans('Show \'No Image\'', [], 'Modules.Smartblog.Smartblog'),
            'name'     => 'smartshownoimg',
            'required' => false,
            'is_bool'  => true,
            'values'   => array(
                array(
                    'id'    => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Enabled', [], 'Modules.Smartblog.Smartblog'),
                ),
                array(
                    'id'    => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Disabled', [], 'Modules.Smartblog.Smartblog'),
                ),
            ),
        ),
        

    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);