<style type="text/css">


#avisverifies_module label{
	
	margin-right: 15px;
	
	
}

#avisverifies_module input[type=text] {
	float:left;
	margin-right: 20px;
	margin-bottom: 10px;
}



#avisverifies_module  .floatleft{
	float: left;
}


#avisverifies_module  #export{
	margin-left: 10px;
}

#avisverifies_module  p.help{
	
	font-style: italic;
	color:#9E9E9E;
	font-size: 11px;
}

#avisverifies_module  p.help.withfloat{
	float:left;
	
}

#avisverifies_module  p.help.inline{
	display:inline;
	
}

#avisverifies_module #order-statut-list{
	list-style:none;
	float:left;
	margin-top:0;
	padding: 0;
}


#avisverifies_module .field-line{
	margin-bottom: 10px;
	display: block;
}

#avisverifies_module label.label-pointer{
	float: none;
	text-align: right;
	font-weight: normal;
}

#avisverifies_module a{
	color:#F9791C;
	font-weight: bold;
}

</style>


{if $errors}
	<div class="alert error">
		<ul>

			{foreach from=$errors item=error}
				{$error}<br>
			{/foreach}
		</ul>
	</div>
{/if}

{if $warnings}
	<div class="alert warning">
		{foreach from=$warnings item=warning}
			{$warning}<br>
		{/foreach}
	</div>
{/if}

{if $validates}
	<div class="conf confirm">
		{foreach from=$validates item=validate}
			{$validate}<br>
		{/foreach}
	</div>
{/if}

<div>

<div id="avisverifies_module">
	<fieldset>
		<legend>{l s='Informations' mod='avisverifies'}</legend>
		<div class='informations'>
			<p>{l s='Le module AvisVerifies vous permet de mettre en place sur vos fiches produits les avis de vos clients, afficher les widgets AvisVerifies et d\'envoyer automatiquement les demandes d\'avis auprès de vos clients à chaque passage de commande.' mod='avisverifies' mod='avisverifies'}</p>
			<p>{l s='Attention : Vous devez avant toute chose vous inscrire sur' mod='avisverifies'} <a href="{l s='url_avisverifies_espace_client' mod='avisverifies'}" target="_blank">{l s='www.avis-verifies.com' mod='avisverifies'}</a> {l s='pour commencer une phase d\'essai gratuite et sans engagement' mod='avisverifies' mod='avisverifies'}</p>
			
		
		</div>
			
	</fieldset>
	<br>

	<fieldset>
		<legend>{l s='Exporter mes commandes' mod='avisverifies'}</legend>
		<div class='export'>
			<p>{l s='Exporter les commandes déjà passées sur votre boutique pour récolter les avis de ces clients et obtenir rapidement votre certificat AvisVerifies.' mod='avisverifies'}</p>
			<ul>
				<li>{l s='Sans avis produits : vos clients seront interrogés sur leur satisfaction concernant la commande (obligatoire)' mod='avisverifies'}</li>
				<li>{l s='Avec avis produits : vos clients seront interrogés sur leur satisfaction concernant la commande (obligatoire) et sur leur satisfaction concernant les produits commandés' mod='avisverifies'}</li>
			</ul>
			<br>
			<form method="post" action="{$url_back}" enctype="multipart/form-data">
				<label class="floatleft">{l s='Depuis' mod='avisverifies'}</label>
				<select id="duree" name="duree" class="floatleft">
					<option value="1w">{l s='1 semaine' mod='avisverifies'}</option>
					<option value="2w">{l s='2 semaines' mod='avisverifies'}</option>
					<option value="1m">{l s='1 mois' mod='avisverifies'}</option>
					<option value="2m">{l s='2 mois' mod='avisverifies'}</option>
					<option value="3m">{l s='3 mois' mod='avisverifies'}</option>
					<option value="4m">{l s='4 mois' mod='avisverifies'}</option>
					<option value="5m">{l s='5 mois' mod='avisverifies'}</option>
					<option value="6m">{l s='6 mois' mod='avisverifies'}</option>
					<option value="7m">{l s='7 mois' mod='avisverifies'}</option>
					<option value="8m">{l s='8 mois' mod='avisverifies'}</option>
					<option value="9m">{l s='9 mois' mod='avisverifies'}</option>
					<option value="10m">{l s='10 mois' mod='avisverifies'}</option>
					<option value="11m">{l s='11 mois' mod='avisverifies'}</option>
					<option value="12m">{l s='12 mois' mod='avisverifies'}</option>
				</select>

				

				<div class="clear"></div>

				<label class="">{l s='Récupérer les avis produits' mod='avisverifies'}</label>

				<select id="productreviews" name="productreviews" class="floatleft">

					<option value="1">{l s='Oui' mod='avisverifies'}</option>
					<option value="0">{l s='Non' mod='avisverifies'}</option>

				</select>
			
				<div class="clear"></div>
				
				<center><input type="submit"  name="submit_export" id="submit_export" value="{l s='Exporter' mod='avisverifies'}" class="button"></center>


			</form>
			
			
		
		</div>
			
	</fieldset>

	<br>
	<fieldset>
		<legend>{l s='Configuration' mod='avisverifies'}</legend>
		<div class='config'>

			
			<p>{l s='Rendez-vous sur votre' mod='avisverifies'} <a href="{l s='url_avisverifies_espace_client' mod='avisverifies'}" target="_blank">{l s='espace client avis-verifies.com' mod='avisverifies'}</a> {l s='pour connaitre ces identifiants' mod='avisverifies'}</p>
			
			<form method="post" action="{$url_back}" enctype="multipart/form-data">
				
				 
				<label>{l s='CLE SECRETE' mod='avisverifies'}</label><input type="text" name="avisverifies_clesecrete" id="avisverifies_clesecrete" value="{$current_avisverifies_clesecrete}"/>
				<div class="clear"></div>

				<label>{l s='ID WEBSITE' mod='avisverifies'}</label><input type="text" name="avisverifies_idwebsite" id="avisverifies_idwebsite" value="{$current_avisverifies_idwebsite}"/>
				<div class="clear"></div>

				<center><input type="submit"  name="submit_configuration" id="submit_configuration" value="{l s='Sauvegarder' mod='avisverifies'}" class="button"></center>
				

			</form>
			
			
		
		</div>
			
	</fieldset>

	<br>
	<!--
	<fieldset>
		<legend>{l s='Configuration actuelle' mod='avisverifies'}</legend>
		<div class='config'>

			<p>Rendez-vous sur votre <a href="http://www.avis-verifies.com/index.php?page=mod_espace_client" target="_blank">espace client avis-verifies.com</a> pour connaitre ces identifiants</p>
			
			<form method="post" action="{$url_back}" enctype="multipart/form-data">
				
				<label>ID WEBSITE</label><input type="text" name="avisverifies_idwebsite" id="avisverifies_idwebsite" value="{$current_avisverifies_idwebsite}"/>
				<div class="clear"></div>

				<label>CLE SECRETE</label><input type="text" name="avisverifies_clesecrete" id="avisverifies_clesecrete" value="{$current_avisverifies_clesecrete}"/>
				<div class="clear"></div>
				<center><input type="submit"  name="submit_configuration" id="submit_configuration" value="{l s='Sauvegarder' mod='avisverifies'}" class="button"></center>
				

			</form>
			
			
		
		</div>
			
	</fieldset>

	-->

	<br>

</div>