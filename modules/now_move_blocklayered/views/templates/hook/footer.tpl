{*
* 2014
* Author: LEFEVRE LOIC
* Site: www.ninja-of-web.fr
* Mail: contact@ninja-of-web.fr
*}

<script type="text/javascript">

	var tabOrdered = { };

	{foreach $aFeatureLists as $aFeature}

		{assign var=position value=0}

		tabOrdered[{$aFeature.id_feature|intval}] = { };
		{foreach FeatureValue::getFeatureValues($aFeature.id_feature) as $aFeatureValue}
			tabOrdered[{$aFeatureValue.id_feature|intval}][{$position}] = { 'id_feature_value': {$aFeatureValue.id_feature_value|intval}, 'position': {$aFeatureValue.position|intval} , 'value': "{$aFeatureValue.value}" };
			{assign var=position value=$position+1}
		{/foreach}

	{/foreach}



</script>