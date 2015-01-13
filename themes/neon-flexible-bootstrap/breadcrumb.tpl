
{if isset($smarty.capture.path)}
	{assign var='path' value=$smarty.capture.path}
{/if}

{if !isset($noBorder)}
	{assign var=noBorder value=false}
{/if}

<div class="container">
	<div id="breadcrumb" {if in_array(Context::getContext()->controller->php_self, array('category', 'cms')) || $noBorder}class="no-border"{/if}>
		<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<a href="{$base_dir}" title="{l s='Return to Home'}" itemprop="url">
				<span itemprop="title" class="home">
					{l s='Néon flexible'}
					{*<img src="{$img_dir}theme/home-fil-ariane.png" height="26" width="27" alt="{l s='Home'}" />*}
				</span>
			</a>
		</div>

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
</div>