<div id="bloc-reinsurrance">

	<div class="container">
		<ul>
			{foreach $aItems as $aItem}
				<li>
					<img src="{$module_dir}{$aItem['file_name']}" alt="{$aItem['name']}" />
					{if !is_null($aItem['id_cms'])}
						<h6>
							{if is_null($aItem['name']) && !is_null($aItem['cms_name'])}
								{assign var=nameReinsurance value=$aItem['cms_name']}
							{else}
								{assign var=nameReinsurance value=$aItem['name']}
							{/if}
							<a href="{Context::getContext()->link->getCMSLink($aItem['id_cms'])}" title="{$nameReinsurance}">
								{$nameReinsurance}
							</a>
						</h6>
						<p>
							<a href="{Context::getContext()->link->getCMSLink($aItem['id_cms'])}" title="{$aItem['description']|truncate:60:'...'}">
								{$aItem['description']}
							</a>
						</p>
					{elseif !is_null($aItem['link'])}
						<h6>
							<a href="{$aItem['link']}" title="{$aItem['name']}">
								{$aItem['name']}
							</a>
						</h6>
						<p>
							<a href="{$aItem['link']}" title="{$aItem['description']|truncate:60:'...'}">
								{$aItem['description']}
							</a>
						</p>
					{else}
						<h6>{$aItem['name']}</h6>
						<p>{$aItem['description']}</p>
					{/if}
				</li>
			{/foreach}
		</ul>
	</div>

</div>