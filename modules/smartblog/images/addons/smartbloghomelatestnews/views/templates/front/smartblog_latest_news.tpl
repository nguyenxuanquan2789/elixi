<div class="block smart-blog-home-latest-news">
    <h2 class='title_block smart-title-shape-full-width'>{l s='Latest News' d='Modules.Smartblog.Smartblog_latest_news'}</h2>
    <div class="sdsblog-box-content row smart-blog-news-box-content">
        {if isset($view_data) AND !empty($view_data)}
            {foreach from=$view_data item=post}
                {assign var='img_url' value=$smartbloglink->getImageLink($post.link_rewrite, $post.id, 'home-default')}
                <div id="sds_blog_post" class="col-xs-12 col-sm-4 col-md-4">
                    <div class="smart-blog-home-news-box">
                        <span class="news_module_image_holder news_home_image_holder">
                            {if $img_url != 'false'}
                            <a href="{$smartbloglink->getSmartBlogPostLink($post.id,$post.link_rewrite)}">
                            <img class="replace-2x img-responsive" src="{$img_url}" alt="{$post.title|escape:'html':'UTF-8'}" title="{$post.title|escape:'html':'UTF-8'}"   itemprop="image" />
                            </a>
                            {/if}
                        </span>
                        <div class="smart-blog-home-news-date">
                            <i class="icon icon-calendar"></i>
                            <span class="sds_post_date">{$post.date_added}</span>
                        </div>
                        <h4 class="sds_post_title sds_post_title_home"><a href="{$smartbloglink->getSmartBlogPostLink($post.id,$post.link_rewrite)}">{SmartBlogPost::subStr($post.title,60)}</a></h4>
                    </div>
                </div>
            {/foreach}
        {/if}
     </div>
</div>