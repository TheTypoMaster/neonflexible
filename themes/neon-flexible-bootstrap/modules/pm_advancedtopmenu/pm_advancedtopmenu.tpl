<!-- MODULE PM_AdvancedTopMenu || Presta-Module.com -->
{if !isset($advtmThemeCompatibility) || (isset($advtmThemeCompatibility) && $advtmThemeCompatibility)}<div class="clear"></div>{/if}
<div id="adtm_menu">
	<div {if isset($advtmResponsiveContainerClasses) && !empty($advtmResponsiveContainerClasses)} class="{$advtmResponsiveContainerClasses}"{/if}>
		<div id="adtm_menu_inner" class="clearfix">
			<ul id="menu">
				{if isset($advtmResponsiveMode) && $advtmResponsiveMode}
					<li class="li-niveau1 advtm_menu_toggle">
						<a class="a-niveau1 adtm_toggle_menu_button"><span class="advtm_menu_span adtm_toggle_menu_button_text">{if isset($advtmResponsiveToggleText) && !empty($advtmResponsiveToggleText)}{$advtmResponsiveToggleText}{else}{l s='Nos produits'}{/if}<b class="caret"></b></span></a>
					</li>
				{/if}
				{foreach from=$advtm_menus item=menu name=loop}
					{if ($menu.privacy eq 2 && $isLogged) || ($menu.privacy eq 1 && !$isLogged) || (!$menu.privacy)}
						{assign var='menuHaveSub' value=$advtm_columns_wrap[$menu.id_menu]|count}
						<li class="li-niveau1 advtm_menu_{$menu.id_menu|intval} {if $menuHaveSub} sub{/if}">
							{$advtm_obj->getLinkOutputValue($menu,'menu',true,$menuHaveSub,true)}

							{if $menuHaveSub}<b class="caret"></b><!--<![endif]-->
							<!--[if lte IE 6]><table><tr><td><![endif]-->
							<div class="adtm_sub">
								{if trim($advtm_obj->realStripTags4Smarty($menu.value_over,'<object><img>'))}
									{$menu.value_over}
								{/if}
								<table class="columnWrapTable"><tr>
										{foreach from=$advtm_columns_wrap[$menu.id_menu] item=column_wrap name=loop2}
											{if ($column_wrap.privacy eq 2 && $isLogged) || ($column_wrap.privacy eq 1 && !$isLogged) || (!$column_wrap.privacy)}
												<td class="adtm_column_wrap_td advtm_column_wrap_td_{$column_wrap.id_wrap|intval}">
													<div class="adtm_column_wrap advtm_column_wrap_{$column_wrap.id_wrap|intval}">
														{if trim($advtm_obj->realStripTags4Smarty($column_wrap.value_over,'<object><img>'))}
															{$column_wrap.value_over}
														{/if}
														{*<div class="adtm_column_wrap_sizer">&nbsp;</div>*}
														{foreach from=$advtm_columns[$column_wrap.id_wrap] item=column name=loop3}
															{if ($column.privacy eq 2 && $isLogged) || ($column.privacy eq 1 && !$isLogged) || (!$column.privacy)}
																{assign var='menuColumnWrapValue' value=$advtm_obj->getLinkOutputValue($column,'column',true)}
																{if trim($advtm_obj->realStripTags4Smarty($column.value_over,'<object><img>'))}
																	{$column.value_over}
																{/if}
																<div class="adtm_column adtm_column_{$column.id_column|intval}">
																	{if $menuColumnWrapValue && preg_match('#href=\"\" title=\"\"#', $menuColumnWrapValue) != 1}<span class="column_wrap_title">{$menuColumnWrapValue}</span>{/if}
																	{assign var='columnHaveElement' value=$advtm_elements[$column.id_column]|count}
																	{if $columnHaveElement}
																		<ul class="adtm_elements adtm_elements_{$column.id_column|intval}">
																			{foreach from=$advtm_elements[$column.id_column] item=element name=loop3}
																				{if ($element.privacy eq 2 && $isLogged) || ($element.privacy eq 1 && !$isLogged) || (!$element.privacy)}
																					<li class="couleur_metier_{$element.position|intval}_on gradient">{$advtm_obj->getLinkOutputValue($element,'element',true)}</li>
																				{/if}
																			{/foreach}
																		</ul>
																	{/if}
																</div>
																{if trim($advtm_obj->realStripTags4Smarty($column.value_under,'<object><img>'))}
																	{$column.value_under}
																{/if}
															{/if}

														{/foreach}
														{if  trim($advtm_obj->realStripTags4Smarty($column_wrap.value_under,'<object><img>'))}
															{$column_wrap.value_under}
														{/if}
													</div>
												</td>
											{/if}
										{/foreach}
									</tr></table>
								{if trim($advtm_obj->realStripTags4Smarty($menu.value_under,'<object><img>'))}
									{$menu.value_under}
								{/if}
							</div>
							<!--[if lte IE 6]></td></tr></table></a><![endif]-->
							{/if}</li>
					{/if}
				{/foreach}
			</ul>
		</div>
	</div>
</div>
<!-- /MODULE PM_AdvancedTopMenu || Presta-Module.com -->