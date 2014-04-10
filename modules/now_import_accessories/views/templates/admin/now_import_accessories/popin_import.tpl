{*
* 2014
* Author: LEFEVRE LOIC
* Site: www.ninja-of-web.fr
* Mail: contact@ninja-of-web.fr
*}

<div style="display: none">
	<div id="display_popin_import_file" style="padding-left: 10px; background-color: #EBEDF4; border: 1px solid #CCCED7">
		<div class="clear">&nbsp;</div>
		<form action="{$current}&token={$token}" method="post" enctype="multipart/form-data">
			<label class="clear" style="width:210px; text-align: left;">{l s='Select your CSV file' mod='now_import_accessories'} </label>
			<div class="margin-form" style="padding-left:210px;">
				<input name="import_file" type="file" />
			</div>
			<div class="margin-form" style="padding-left:215px;">
				<input type="submit" name="submitFileUpload" value="{l s='Upload' mod='now_import_accessories'}" class="button" />
				<p class="preference_description">{l s='Only UTF-8 and ISO-8859-1 encoding are allowed' mod='now_import_accessories'}</p>
			</div>
		</form>
	</div>
</div>