{capture name=path}<a href="{$link->getPageLink('authentication', true)|escape:'html'}" title="{l s='Authentication'}" rel="nofollow">{l s='Authentication'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Forgot your password'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<div class="container">
	<p class="titre-size-1">{l s='Forgot your password?'}</p>

	{include file="$tpl_dir./errors.tpl"}

	{if isset($confirmation) && $confirmation == 1}
		<p class="success">{l s='Your password has been successfully reset and a confirmation has been sent to your email address:'} {if isset($customer_email)}{$customer_email|escape:'htmlall':'UTF-8'|stripslashes}{/if}</p>
	{elseif isset($confirmation) && $confirmation == 2}
		<p class="success">{l s='A confirmation email has been sent to your address:'} {if isset($customer_email)}{$customer_email|escape:'htmlall':'UTF-8'|stripslashes}{/if}</p>
	{else}
		<p>{l s='Please enter the email address you used to register. We will then send you a new password. '}</p>
		<form action="{$request_uri|escape:'htmlall':'UTF-8'}" method="post" class="std" id="form_forgotpassword">
			<fieldset>
				<p class="text">
					<label for="email">{l s='Email'}</label>
					<input type="text" id="email" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email|escape:'htmlall':'UTF-8'|stripslashes}{/if}" />
				</p>
				<p class="submit">
					<input type="submit" class="button-rose" value="{l s='Retrieve Password'}" />
				</p>
			</fieldset>
		</form>
	{/if}
</div>