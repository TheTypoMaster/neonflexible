
{if ($content_only == 0)}
	{include file="$tpl_dir./breadcrumb.tpl"}
{/if}


{if isset($cms) && !isset($cms_category)}
	<div id="bandeau-cms">
		<div class="container">

			<div class="left">

				<p class="titre-vert">{l s='Tutorial'}</p>

				<h1>
					{$cms->meta_title|escape:'htmlall':'UTF-8'}
				</h1>

				<div class="desc"><p>{$cms->meta_description|truncate:550:'...'}</p></div>

			</div>

			{if file_exists('_PS_ROOT_DIR_'|constant|cat:'/images/cms/'|cat:$cms->id|cat:'.jpg')}
				<div class="right">
					<img src="/images/cms/{$cms->id|intval}.jpg" alt="{$cms->meta_title|escape:'htmlall':'UTF-8'}" title="{$cms->meta_title|escape:'htmlall':'UTF-8'}" id="cmsImage" />
				</div>
			{/if}

		</div>
	</div>

	<div class="container">
		<div class="rte{if $content_only} content_only{/if}">
			{$cms->content}
		</div>
	</div>
{elseif isset($cms_category)}
	<div class="container">
		<div class="block-cms">
			<p class="titre-size-1">{$cms_category->name|escape:'htmlall':'UTF-8'}</p>
			{if isset($sub_category) && !empty($sub_category)}
				<ul class="bullet">
					{foreach from=$sub_category item=subcategory}
						<li>
							<a href="{$link->getCMSCategoryLink($subcategory.id_cms_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}">
								{$subcategory.name|escape:'htmlall':'UTF-8'}
							</a>
						</li>
					{/foreach}
				</ul>
			{/if}
			{if isset($cms_pages) && !empty($cms_pages)}
				<ul class="bullet">
					{foreach from=$cms_pages item=cmspages}
						<li>
							<a href="{$link->getCMSLink($cmspages.id_cms)|escape:'htmlall':'UTF-8'}">
								{$cmspages.meta_title|escape:'htmlall':'UTF-8'}
							</a>
						</li>
					{/foreach}
				</ul>
			{/if}
		</div>
	</div>
{else}
	<div class="container">
		<div class="error">
			{l s='This page does not exist.'}
		</div>
	</div>
{/if}