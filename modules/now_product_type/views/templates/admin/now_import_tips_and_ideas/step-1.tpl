{*
* 2014
* Author: LEFEVRE LOIC
* Site: www.ninja-of-web.fr
* Mail: contact@ninja-of-web.fr
*}

{if $show_toolbar}
	{include file="page_header_toolbar.tpl" toolbar_btn=$toolbar_btn toolbar_scroll=$toolbar_scroll title=$title}
	{include file="helpers/modules_list/modal.tpl" }
{/if}

<div class="leadin">{block name="leadin"}{/block}</div>

{include file="$module_path/steps.tpl" selected=$current_step pagination=$pagination}

<div class="now_product_type">
	<p>{l s='The import module of ideas or typs allows you to connect a product with one or more ideas or tips.' mod='now_product_type'}</p>
	<p>{l s='To begin, please import a. CSV file.' mod='now_product_type'}</p>

	<script type="text/javascript">function showBanNow() { $('#ninja-of-web').animate( { "margin-right":'-260px' } , 500).attr('class', 'hover'); };function hideBanNow() { $('#ninja-of-web').animate( { "margin-right":'-690px' } , 500).removeAttr('class'); };</script>
	<div id="ninja-of-web">
		<a class="open-now" onclick="javascript:showBanNow();"></a>
		<a class="close-now" onclick="javascript:hideBanNow();"></a>
		<p>Ninja Of Web</p>
		<p>Développeur web</p>
		<p><a href="mailto:contact@ninja-of-web.fr" title="NinjaOfweb">contact@ninja-of-web.fr</a></p>
		<p><a href="http://www.ninja-of-web.fr" title="NinjaOfweb">www.ninja-of-web.fr</a></p>
	</div>
</div>