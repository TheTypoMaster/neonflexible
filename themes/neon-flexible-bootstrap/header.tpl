<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 " lang="{$lang_iso}"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" lang="{$lang_iso}"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" lang="{$lang_iso}"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="{$lang_iso}"> <![endif]-->
<html lang="{$lang_iso}">
<head>
	<title>{$meta_title|escape:'htmlall':'UTF-8'}</title>
	<meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.0, initial-scale=1.0">


	{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:html:'UTF-8'}" />
	{/if}
	{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:html:'UTF-8'}" />
	{/if}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{*<meta http-equiv="content-language" content="{$meta_language}" />*}
	<meta name="generator" content="PrestaShop" />

	{* Les pages suivantes ne serotn pas accessible pas les moteurs *}
	{if in_array(Context::getContext()->controller->php_self, array('my-account', 'identity', 'addresses', 'address', 'history', 'discount', 'order-slip', 'order'))}
		{assign var="nobots"	value=true}
		{assign var="nofollow"	value=true}
	{/if}

	<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />


	<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
	<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />

	<!-- Bootstrap -->
	<link href="{$css_dir}bootstrap/bootstrap.css" rel="stylesheet" media="screen">
	<link href="{$css_dir}example/carousel.css" rel="stylesheet" media="screen">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="http://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
	<script src="http://getbootstrap.com/docs-assets/js/respond.min.js"></script>
	<![endif]-->

	{if isset($css_files)}
		{foreach from=$css_files key=css_uri item=media}
			<link href="{$css_uri}" rel="stylesheet" type="text/css" media="{$media}" />
		{/foreach}
	{/if}

	{$HOOK_HEADER}

	<!--[if gte IE 9]>
	<style type="text/css">
		.gradient {
			filter: none;
		}
	</style>
	<![endif]-->

</head>

<body {if isset($page_name)}id="{$page_name|escape:'htmlall':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'htmlall':'UTF-8'}{/if}{if $hide_left_column} hide-left-column{/if}{if $hide_right_column} hide-right-column{/if}{if $content_only} content_only{/if}" itemscope itemtype="http://schema.org/WebPage">
	{if !$content_only}

	<div id="header">
		<div class="container">
			<div class="row">
				{hook h="displayBanner"}
			</div>
		</div>
		<div class="navbar-inverse">
			<div class="container">
				{if isset($restricted_country_mode) && $restricted_country_mode}
					<div id="restricted-country">
						<p>{l s='You cannot place a new order from your country.'} <span class="bold">{$geolocation_country}</span></p>
					</div>
				{/if}
				{hook h="displayNav"}
				<div class="clearfix"></div>
			</div>
		</div>
        
        <div class="container">
        	<div class="col-sm-6 col-lg-3" id="header_logo">
			{*{if Context::getContext()->controller->php_self == 'index'}
            
				<h1>
					<a href="{Context::getContext()->link->getPageLink('index')}" title="{l s='%s, just imagine' sprintf=$shop_name|escape:'htmlall':'UTF-8'}">
						<img class="logo" src="{$logo_url}" alt="{l s='%s, just imagine' sprintf=$shop_name|escape:'htmlall':'UTF-8'}" />
					</a>
				</h1>
			{else}*}
				<a href="{Context::getContext()->link->getPageLink('index')}" title="{l s='%s, just imagine' sprintf=$shop_name|escape:'htmlall':'UTF-8'}">
					<img class="logo" src="{$logo_url}" alt="{l s='%s, just imagine' sprintf=$shop_name|escape:'htmlall':'UTF-8'}" />
				</a>
			{*{/if}*}
            </div>
            
		{if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
        </div>		
        {hook h="DisplayMenu"}
	</div>

	{/if}