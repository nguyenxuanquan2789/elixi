{**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 *}
<!doctype html>
<html lang="{$iso_code}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	{if isset($vec_content)}
		<title>{$vec_content->title}</title>
		<meta name="description" content="{trim(strip_tags($vec_content->content))}">
	{/if}
	<meta name="viewport" content="width=device-width, initial-scale=1">
	{if !empty($favicon)}
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$smarty.const._PS_IMG_}{$favicon}?{$favicon_update_time}">
		<link rel="shortcut icon" type="image/x-icon" href="{$smarty.const._PS_IMG_}{$favicon}?{$favicon_update_time}">
	{/if}
	<style>
	html, body { margin: 0; padding: 0; }
	</style>
	<script>
	var baseDir = {json_encode($smarty.const.__PS_BASE_URI__)|cefilter};
	</script>
</head>
<body id="maintenance" class="lang-{$iso_code} page-maintenance">
	<main>
		{$HOOK_MAINTENANCE|cefilter}
	</main>
</body>
</html>