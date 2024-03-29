{capture name=path}<a href="{$link->getPageLink('my-account', true)|escape:'html'}">{l s='My account'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Your personal information'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}


<div class="container">
	<h1 class="titre-size-1">{l s='Your personal information'}</h1>

	{include file="$tpl_dir./errors.tpl"}

	{if isset($confirmation) && $confirmation}
		<p class="success">
			{l s='Your personal information has been successfully updated.'}
			{if isset($pwd_changed)}<br />{l s='Your password has been sent to your email:'} {$email}{/if}
		</p>
	{/if}

	<p>{l s='Please be sure to update your personal information if it has changed.'}</p>
	<p class="required"><sup>*</sup>{l s='Required field'}</p>
	<form action="{$link->getPageLink('identity', true)|escape:'html'}" method="post" class="std">
		<fieldset>
			<p class="radio">
				<span class="label">{l s='Title'}</span>
				{foreach from=$genders key=k item=gender}
					<input type="radio" name="id_gender" id="id_gender{$gender->id}" value="{$gender->id|intval}" {if isset($smarty.post.id_gender) && $smarty.post.id_gender == $gender->id}checked="checked"{/if} />
					<label for="id_gender{$gender->id}" class="top">{$gender->name}</label>
				{/foreach}
			</p>
			<p class="required text">
				<label for="firstname">{l s='First name'} <sup>*</sup></label>
				<input type="text" id="firstname" name="firstname" value="{$smarty.post.firstname}" />
			</p>
			<p class="required text">
				<label for="lastname">{l s='Last name'} <sup>*</sup></label>
				<input type="text" name="lastname" id="lastname" value="{$smarty.post.lastname}" />
			</p>
			<p class="required text">
				<label for="email">{l s='Email'} <sup>*</sup></label>
				<input type="text" name="email" id="email" value="{$smarty.post.email}" />
			</p>
			<p class="required text">
				<label for="old_passwd">{l s='Current Password'} <sup>*</sup></label>
				<input type="password" name="old_passwd" id="old_passwd" />
			</p>
			<p class="password">
				<label for="passwd">{l s='New Password'}</label>
				<input type="password" name="passwd" id="passwd" />
			</p>
			<p class="password">
				<label for="confirmation">{l s='Confirmation'}</label>
				<input type="password" name="confirmation" id="confirmation" />
			</p>
			<p class="select">
				<label>{l s='Date of Birth'}</label>
				<select name="days" id="days">
					<option value="">-</option>
					{foreach from=$days item=v}
						<option value="{$v}" {if ($sl_day == $v)}selected="selected"{/if}>{$v}&nbsp;&nbsp;</option>
					{/foreach}
				</select>
				{*
					{l s='January'}
					{l s='February'}
					{l s='March'}
					{l s='April'}
					{l s='May'}
					{l s='June'}
					{l s='July'}
					{l s='August'}
					{l s='September'}
					{l s='October'}
					{l s='November'}
					{l s='December'}
				*}
				<select id="months" name="months">
					<option value="">-</option>
					{foreach from=$months key=k item=v}
						<option value="{$k}" {if ($sl_month == $k)}selected="selected"{/if}>{l s=$v}&nbsp;</option>
					{/foreach}
				</select>
				<select id="years" name="years">
					<option value="">-</option>
					{foreach from=$years item=v}
						<option value="{$v}" {if ($sl_year == $v)}selected="selected"{/if}>{$v}&nbsp;&nbsp;</option>
					{/foreach}
				</select>
			</p>
			{if $newsletter}
				<p class="checkbox">
					<input type="checkbox" id="newsletter" name="newsletter" value="1" {if isset($smarty.post.newsletter) && $smarty.post.newsletter == 1} checked="checked"{/if} autocomplete="off"/>
					<label for="newsletter">{l s='Sign up for our newsletter!'}</label>
				</p>
				<p class="checkbox">
					<input type="checkbox" name="optin" id="optin" value="1" {if isset($smarty.post.optin) && $smarty.post.optin == 1} checked="checked"{/if} autocomplete="off"/>
					<label for="optin">{l s='Receive special offers from our partners!'}</label>
				</p>
			{/if}
			<p class="submit cart_navigation clearfix">
				<button type="submit" class="button btn btn-default standard-checkout button-medium" name="submitIdentity">
					<span>{l s='Save'}</span>
				</button>
			</p>
			<p id="security_informations">
				{l s='[Insert customer data privacy clause here, if applicable]'}
			</p>
		</fieldset>
	</form>
</div>