
<fieldset class="account_creation">
	<h1 class="titre-size-1">{l s='Referral program' mod='referralprogram'}</h1>
	<p class="text">
		<label for="referralprogram">{l s='E-mail address of your sponsor' mod='referralprogram'}</label>
		<input type="text" size="52" maxlength="128" id="referralprogram" name="referralprogram" value="{if isset($smarty.post.referralprogram)}{$smarty.post.referralprogram|escape:'html':'UTF-8'}{/if}" />
	</p>
</fieldset>