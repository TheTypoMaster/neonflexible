{*
* 2014
* Author: LEFEVRE LOIC
* Site: www.ninja-of-web.fr
* Mail: contact@ninja-of-web.fr
*}

<div style="display: none">
	<div id="display_popin_import_file" class="bootstrap">
		<div class="clear">&nbsp;</div>
		<form action="{$current}&token={$token}" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="file" class="control-label col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title="{l s='Select your CSV file' mod='now_import_accessories'}">
						{l s='Select your CSV file' mod='now_import_accessories'}
					</span>
				</label>
				<div class="col-sm-9">
					<div class="row">
						<div class="col-lg-7">
							<input id="file" type="file" name="file" class="hide" />
							<div class="dummyfile input-group">
								<span class="input-group-addon"><i class="icon-file"></i></span>
								<input id="file-name" type="text" class="disabled" name="filename" readonly />
								<span class="input-group-btn">
									<button id="file-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
										<i class="icon-folder-open"></i> {l s='Choose a file' mod='now_import_accessories'}
									</button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="file" class="control-label col-lg-3"></label>
				<input type="submit" name="submitFileUpload" value="{l s='Upload' mod='now_import_accessories'}" class="button" />
				<p class="preference_description">{l s='Only UTF-8 and ISO-8859-1 encoding are allowed' mod='now_import_accessories'}</p>
			</div>
		</form>
	</div>
</div>