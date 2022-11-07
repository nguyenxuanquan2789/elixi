<style>
.icon-AdminSmartBlog:before{
  content: "\f14b";
   }
   
 
</style>
<link rel="stylesheet" type="text/css" href="{$smartmodules_dir}modules/smartblog/views/css/bootstrap-grid.css">
<link rel="stylesheet" type="text/css" href="{$smartmodules_dir}modules/smartblog/views/css/admin.css">
<script type="text/javascript">
		{if isset($PS_ALLOW_ACCENTED_CHARS_URL) && $PS_ALLOW_ACCENTED_CHARS_URL}
			var PS_ALLOW_ACCENTED_CHARS_URL = 1;
		{else}
			var PS_ALLOW_ACCENTED_CHARS_URL = 0;
		{/if}
</script>
{if $hascrazy == "0"}
	<script type="text/html" id="edit_with_crazy">
		<a href="https://classydevs.com/crazy-elements/?utm_source=smartblog_advertise&utm_medium=smartblog_advertise&utm_campaign=smartblog_advertise&utm_term=smartblog_advertise"  id="edit_with_crazy_link" class="button button-primary button-hero" target="_blank"><img src="{$icon_url}" alt="Crazy Elements Logo"> {l s='Get Crazy Elements and Edit Your Post Content More Beautifully' d='Modules.Crazyelements.Addjs'}</a>
	</script>
{/if}