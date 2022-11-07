{if isset($tags) AND !empty($tags)}
<div  id="tags_blog_block_left"  class="block block-blog smart-block tags_block">
    <h4 class="smart_blog_sidebar_title">{l s='Tags Post' mod='smartblogtag'}</h4>
    <div class="block_content smart_blog_block_content">
            {foreach from=$tags item="tag"}
            {assign var="options" value=null}
                {$options.tag = $tag.name|urlencode}
                {if $tag!=""}
                    <a href="{$smartbloglink->getSmartBlogTag($tag.slug)|escape:'htmlall':'UTF-8'}">{$tag.name}</a>  
                {/if}
            {/foreach}
   </div>
</div>
{/if}