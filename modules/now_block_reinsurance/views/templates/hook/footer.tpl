<div id="bloc-reinsurrance">

	<div class="container">
		<ul>
			{foreach $aItems as $aItem}
				<li>
					<img src="{$module_dir}{$aItem['file_name']}" alt="{$aItem['name']}" />

					{if !is_null($aItem['id_cms'])}
						{if is_null($aItem['name']) && !is_null($aItem['cms_name'])}
							{assign var=nameReinsurance value=$aItem['cms_name']}
						{else}
							{assign var=nameReinsurance value=$aItem['name']}
						{/if}
					{else}
						{assign var=nameReinsurance value=$aItem['name']}
					{/if}

					<h6>{$nameReinsurance}</h6>
					<p>{$aItem['description']}</p>

					<span class="clearBoth"></span>

					{if !is_null($aItem['id_cms'])}
						<a href="{Context::getContext()->link->getCMSLink($aItem['id_cms'])}" title="{$nameReinsurance}" class="link"></a>
					{elseif !is_null($aItem['link'])}
						<a href="{$aItem['link']}" title="{$nameReinsurance}" class="link"></a>
					{/if}
				</li>
			{/foreach}
		</ul>
	</div>

</div>