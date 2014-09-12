
{if isset($smarty.capture.path)}
	{assign var='path' value=$smarty.capture.path}
{/if}

<div id="breadcrumb">
	<a href="{$base_dir}" title="{l s='Return to Home'}">
		<img src="{$img_dir}theme/home-fil-ariane.png" height="26" width="27" alt="{l s='Home'}" />
	</a>

	{if isset($path) AND $path}
		<span class="navigation-pipe" {if isset($category) && isset($category->id_category) && $category->id_category == 1}style="display:none;"{/if}>
			{$navigationPipe|escape:'html':'UTF-8'}
		</span>
		{if !$path|strpos:'span'}
			<span class="navigation-page">{$path}</span>
		{else}
			{$path}
		{/if}
	{/if}

</div>