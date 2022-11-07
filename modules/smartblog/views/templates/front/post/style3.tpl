<div itemtype="#" itemscope="" class="sdsarticleCat style-3 {$class}">
    <div id="smartblogpost-{$post.id_post|escape:'htmlall':'UTF-8'}" class="smart-blog-post-item">
        <div class="sdsarticle-img">
             {if isset($ispost) && !empty($ispost)}
            <a itemprop="url"
                href="{$smartbloglink->getSmartBlogPostLink($post.id_post,$post.cat_link_rewrite)|escape:'htmlall':'UTF-8'}"
                title="{$post.meta_title|escape:'htmlall':'UTF-8'}" class="imageFeaturedLink">

            {/if}
            {if $post.image.url}
                <img itemprop="image" alt="{$post.meta_title|escape:'htmlall':'UTF-8'}"
                src="{$post.image.url}" width="{$post.image.width}" height="{$post.image.height}" loading="lazy"
                class="imageFeatured"> 
            {/if}

            {if isset($ispost) && !empty($ispost)}
            </a>
            {/if} 
            <div class="smart-blog-inner">
                <div class="smart-blog-posts-info">
                    {$assocCats = BlogCategory::getPostCategoriesFull($post.id_post)}
                    {$catCounts = 0}
                    {if !empty($assocCats)}
                        <span>
                            {foreach $assocCats as $catid=>$assoCat}
                                {if $catCounts > 0}, {/if}
                                {$catlink=[]}
                                {$catlink.id_category = $assoCat.id_category}
                                {$catlink.slug = $assoCat.link_rewrite}
                                <a
                                    href="{$smartbloglink->getSmartBlogCategoryLink($assoCat.id_category,$assoCat.link_rewrite)|escape:'htmlall':'UTF-8'}">
                                    {$assoCat.name|escape:'htmlall':'UTF-8'}
                                </a>
                                {$catCounts = $catCounts + 1}
                            {/foreach}
                        </span>
                    {/if}
                    {if $smartshowauthor ==1}
                    <span itemprop="author">{l s='Posted by'  d='Modules.Smartblog.Category_loop'}&nbsp;<i class="vecicon-person-circle-outline"></i>&nbsp; {if $smartshowauthorstyle != 0}{$post.firstname|escape:'htmlall':'UTF-8'}
                        {$post.lastname|escape:'htmlall':'UTF-8'}{else}{$post.lastname|escape:'htmlall':'UTF-8'}
                        {$post.firstname|escape:'htmlall':'UTF-8'}{/if}
                    </span> {/if}
                    <span>{$post.date_added}</span>
                    {if Configuration::get('smartenablecomment') == 1}
                    <span class="comment">
                        <a href="{$smartbloglink->getSmartBlogPostLink($post.id_post,$post.link_rewrite)|escape:'htmlall':'UTF-8'}#articleComments"
                    title="{$post.totalcomment|escape:'htmlall':'UTF-8'} Comments">{if $post.totalcomment}{$post.totalcomment}{else}0{/if}{l s=' Comments' d='Modules.Smartblog.Category_loop'}</a></span>{if $smartshowviewed ==1}&nbsp; <span class="smart-bg-views">
                        {$post.viewed|intval}</i>{l s=' views' d='Modules.Smartblog.Category_loop'}
                    {/if}</span>
                    {/if}
                </div>
                <div class='title_block smart-blog-posts-title'><a title="{$post.meta_title|escape:'htmlall':'UTF-8'}"
                        href="{$smartbloglink->getSmartBlogPostLink($post.id_post,$post.link_rewrite)|escape:'htmlall':'UTF-8'}">{$post.meta_title|escape:'htmlall':'UTF-8'}</a>
                </div>
            </div>
        </div> 
        <div class="smart-blog-content">
            <div class="smart-blog-post-readmore-btn">
                <a title="{$post.meta_title|escape:'htmlall':'UTF-8'}"
                    href="{$smartbloglink->getSmartBlogPostLink($post.id_post,$post.link_rewrite)|escape:'htmlall':'UTF-8'}"
                    class="r_more btn-primary"><span>{l s='Read More'  d='Modules.Smartblog.Category_loop'}</span> <i class="vecicon-android-arrow-dropright-circle"></i></a>
            </div>
        </div>
    </div>
</div>