<div id="page-listing">

	<ul>

		{foreach $aNowBlockFooterCmsColumns as $oNowBlockFooterCmsColumn}
			{if $oNowBlockFooterCmsColumn->active && array_key_exists($oNowBlockFooterCmsColumn->id_now_block_cms_footer_column, $aNowBlockFooterCmsByColumnIds) && count($aNowBlockFooterCmsByColumnIds[$oNowBlockFooterCmsColumn->id_now_block_cms_footer_column]) > 0}
				<li class="hidden-xs">
					<span>{$oNowBlockFooterCmsColumn->name}</span>

					{foreach $aNowBlockFooterCmsByColumnIds[$oNowBlockFooterCmsColumn->id_now_block_cms_footer_column] as $aNowBlockFooterCmsList}
						<ul>
							{foreach $aNowBlockFooterCmsList as $oNowBlockFooterCms}
								{if $oNowBlockFooterCms->active}
									<li>
										{if $oNowBlockFooterCms->type == NowBlockFooterCms::TYPE_LINK}
											<a href="{$oNowBlockFooterCms->link}">
												{$oNowBlockFooterCms->name}
											</a>
										{elseif $oNowBlockFooterCms->type == NowBlockFooterCms::TYPE_CATEGORY}
											<a href="{Context::getContext()->link->getCategoryLink($oNowBlockFooterCms->object)}">
												{if !is_null($oNowBlockFooterCms->name) && $oNowBlockFooterCms->name != ''}
													{$oNowBlockFooterCms->name}
												{else}
													{$oNowBlockFooterCms->object->name}
												{/if}
											</a>
										{elseif $oNowBlockFooterCms->type == NowBlockFooterCms::TYPE_MANUFACTURER}
											<a href="{Context::getContext()->link->getManufacturerLink($oNowBlockFooterCms->object)}">
												{if !is_null($oNowBlockFooterCms->name) && $oNowBlockFooterCms->name != ''}
													{$oNowBlockFooterCms->name}
												{else}
													{$oNowBlockFooterCms->object->name}
												{/if}
											</a>
										{elseif $oNowBlockFooterCms->type == NowBlockFooterCms::TYPE_CMS}
											<a href="{Context::getContext()->link->getCMSLink($oNowBlockFooterCms->object)}">
												{if !is_null($oNowBlockFooterCms->name) && $oNowBlockFooterCms->name != ''}
													{$oNowBlockFooterCms->name}
												{else}
													{$oNowBlockFooterCms->object->meta_title}
												{/if}
											</a>
										{/if}
									</li>
								{/if}
							{/foreach}
						</ul>
					{/foreach}
					<span class="clear"></span>
				</li>
			{/if}
		{/foreach}

	</ul>

	<hr>

	<img src="{$img_dir}/theme/cb-visa-mastercard-paypal.png" alt="{l s='CB / Visa / MasterCard / Paypal' mod='now_block_cms_footer'}" class="footer-payement" />

</div>