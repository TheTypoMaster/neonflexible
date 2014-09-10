<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 " lang="{$lang_iso}"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" lang="{$lang_iso}"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" lang="{$lang_iso}"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="{$lang_iso}"> <![endif]-->
<html lang="{$lang_iso}">
<head>
	<title>{$meta_title|escape:'htmlall':'UTF-8'}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:html:'UTF-8'}" />
	{/if}
	{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:html:'UTF-8'}" />
	{/if}
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta http-equiv="content-language" content="{$meta_language}" />
	<meta name="generator" content="PrestaShop" />
	<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />
	<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
	<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />

	<!-- Bootstrap -->
	<link href="{$css_dir}bootstrap/bootstrap.min.css" rel="stylesheet" media="screen">
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

</head>

<body {if isset($page_name)}id="{$page_name|escape:'htmlall':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'htmlall':'UTF-8'}{/if}{if $hide_left_column} hide-left-column{/if}{if $hide_right_column} hide-right-column{/if}{if $content_only} content_only{/if}">
	{if !$content_only}

	<div id="header">
		<div class="container">
			<div class="row">
				{hook h="displayBanner"}
			</div>
		</div>

		<div class="container">
			<a href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}">
				<img class="logo" src="{$logo_url}" alt="{$shop_name|escape:'htmlall':'UTF-8'}" {if $logo_image_width}width="{$logo_image_width}"{/if} {if $logo_image_height}height="{$logo_image_height}" {/if} />
			</a>
		</div>

		<div class="navbar-inverse">
			<div class="container">
				{if isset($restricted_country_mode) && $restricted_country_mode}
					<div id="restricted-country">
						<p>{l s='You cannot place a new order from your country.'} <span class="bold">{$geolocation_country}</span></p>
					</div>
				{/if}
				<ul class="nav-header-top hidden-xs" role="navigation">
					<li><a href="">{l s='Panier :'} <span class="nb-products-in-cart">0</span> produits <b class="caret"></b></a></li>
					<li><a href="">{l s='Mon compte'}</a></li>
					<li>
						<div class="list-languages">
							{foreach Language::getLanguages() as $language}
								<a href="">
									<img src="{$img_dir}/theme/lang/{$language.iso_code}.png" alt="{$language.iso_code}" />
								</a>
							{/foreach}
						</div>
						<div class="clearfix"></div>
					</li>
					<li><a href="">{l s='Espace Pro'}</a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
		</div>

		{if isset($HOOK_TOP)}{$HOOK_TOP}{/if}

		<div id="nav-header">
			{hook h="displayNav"}
		</div>
	</div>

	{/if}