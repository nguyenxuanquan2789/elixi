{if $node.name == ''}
    {if $node.children|@count > 0}
        {foreach from=$node.children item=child name=categoryTreeBranch}
            {if $smarty.foreach.categoryTreeBranch.last}
                {include file="module:smartblogcategories/category-tree-branch.tpl" node=$child last='true' select='false'}
            {else}
                {include file="module:smartblogcategories/category-tree-branch.tpl" node=$child last='false' select='false'}
            {/if}
        {/foreach}
    {/if}
{else}
    <option value="{$node.link}" class="category_{$node.id}{if isset($last) && $last == 'true'} last{/if}">
        {$node.level_depth nofilter}{$node.level_depth nofilter}-{$node.name|escape:'html':'UTF-8'}
    </option>    
        {if $node.children|@count > 0}
            {foreach from=$node.children item=child name=categoryTreeBranch}
                {if $smarty.foreach.categoryTreeBranch.last}
                    {include file="module:smartblogcategories/category-tree-branch.tpl" node=$child last='true' select='false'}
                {else}
                    {include file="module:smartblogcategories/category-tree-branch.tpl" node=$child last='false' select='false'}
                {/if}
            {/foreach}
        {/if}
{/if}