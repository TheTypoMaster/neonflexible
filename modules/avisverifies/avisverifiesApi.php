<?php

// Faire une recherche de "ERREUR" sur ce script pour trouver les points à reprendre

/**
 * API Avis Verifies
 * Author : NetReviews SAS
 * Contact : contact@avis-verifies.com
 * Version : 1.0
 */


require('../../config/config.inc.php');
require('../../init.php');
include('avisverifies.php');


$POST_DATA = $_POST;

// ## Vérification de la bonne réception des datas POST | EXIT si erreur renvoyée

if(!isset($POST_DATA) OR empty($POST_DATA)){

	$reponse['debug'] ="Aucun variable POST reçues";
	$reponse['return'] = 2; //A definir
	$reponse['query'] = $query;

	echo AvisVerifiesModel::AC_encode_base64(serialize($reponse));
	exit;
}

// ## Vérification de l'état du module | EXIT si erreur renvoyée

$îsActiveVar = isActiveModule($POST_DATA);

if($îsActiveVar['return'] != 1){
	echo AvisVerifiesModel::AC_encode_base64(serialize($isActiveVar));
	exit;
}

// ## Vérification des Identifiants Clients | EXIT si erreur renvoyée

$checkSecurityVar = checkSecurityData($POST_DATA);

if($checkSecurityVar['return'] != 1 ){
	echo AvisVerifiesModel::AC_encode_base64(serialize($checkSecurityVar));
	exit;
}

/* ############ DEBUT DU TRAITEMENT ############*/ 

// ## Switch en fonction de commande demandé par AvisVerifies

switch ($POST_DATA['query']) {
	case 'isActiveModule':
		$toReply = isActiveModule($POST_DATA);
		break;
	case 'setModuleConfiguration' : 
		$toReply = setModuleConfiguration($POST_DATA);
		break;	
	case 'getModuleAndSiteConfiguration' : 
		$toReply = getModuleAndSiteConfiguration($POST_DATA);
		break;
	case 'getOrders' : 
		$toReply = getOrders($POST_DATA);
		break;
	case 'setProductsReviews' : 
		$toReply = setProductsReviews($POST_DATA);
		break;	
	case 'setTraductionFiles' : 
		$toReply = setTraductionFiles($POST_DATA);
		break;
	case 'truncateTables' : 
		$toReply = truncateTables($POST_DATA);
		break;
	default:
		break;
}


// Affichage du retour des fonctions pour récupération du résultat par AvisVerifies

echo AvisVerifiesModel::AC_encode_base64(serialize($toReply));

/**
 * Vérifie les identifiants API client
 * Tous les appels de l'API dépendent du retour positif ou négatif de cette fonction
 * @param $POST_DATA
 * @return $reponse : code erreur + erreur
 */

function checkSecurityData(&$POST_DATA) {

	//get($key, $id_lang = null, $id_shop_group = null, $id_shop = null)	

	$unserialized_message = unserialize(AvisVerifiesModel::AC_decode_base64($POST_DATA['message']));		

	if(_PS_VERSION_ >= 1.5){
		//$reponse['message'] = Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')."Votre Prestashop est configuré en multisite mais l'idenfifiant du site à configurer n'a pas été renseigné. Nous ne pouvons pas communiquer avec votre boutique, revenez à l'étape 2 pour renseigner l'identifiant";
		if(Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1){

			if(!isset($unserialized_message['id_shop']) OR empty($unserialized_message['id_shop'])){

				$reponse['debug'] = $unserialized_message ;				
				$reponse['return'] = 2;	
				$reponse['query'] = 'checkSecurityData';	//nom de la fonction mis en dur car cette query n'est jamais appaelé mais elle est nécessaire pour le tableau d'erreur coté AV
				return $reponse;

			}

		}
		
	}

	if(isset($unserialized_message['id_shop'])){
		$local_idWebsite = Configuration::get('AVISVERIFIES_IDWEBSITE', null, null, $unserialized_message['id_shop']);
		$local_secureKey = Configuration::get('AVISVERIFIES_CLESECRETE', null, null, $unserialized_message['id_shop']);
	}
	else{
		$local_idWebsite = Configuration::get('AVISVERIFIES_IDWEBSITE');
		$local_secureKey = Configuration::get('AVISVERIFIES_CLESECRETE');
	}

	

	// Vérification si identifiants non vide
	if(!$local_idWebsite OR !$local_secureKey) {

		$reponse['debug'] = "Identifiants clients non renseignés sur le module";
		$reponse['message'] = "Identifiants clients non renseignés sur le module";
		$reponse['return'] = 3; //A definir
		$reponse['query'] = 'checkSecurityData';	//nom de la fonction mis en dur car cette query n'est jamais appaelé mais elle est nécessaire pour le tableau d'erreur coté AV
		return $reponse;
	}
	//Vérification si idWebsite OK
	elseif($unserialized_message['idWebsite'] != $local_idWebsite) {
		$reponse['message'] = "Clé Website incorrecte";
		$reponse['debug'] = "Clé Website incorrecte";		
		$reponse['debug'] .= "\n Clé Local Client : ".$local_idWebsite;
		$reponse['debug'] .= "\n Clé AvisVerifies : ".$unserialized_message['idWebsite'];
		$reponse['return'] = 4; //A definir
		$reponse['query'] = 'checkSecurityData';
		return $reponse;
	}
	//Vérification si Signature OK
	elseif(SHA1($POST_DATA['query'].$local_idWebsite.$local_secureKey) != $unserialized_message['sign']){
		$reponse['message'] = "La signature est incorrecte";		
		$reponse['debug'] = "La signature est incorrecte";		
		$reponse['debug'] .= "\n Signature Client : ".SHA1($POST_DATA['query'].$local_idWebsite.$local_secureKey);	
		$reponse['debug'] .= "\n Signature AvisVerifies : ".$unserialized_message['sign'];		
		$reponse['return'] = 5; //A definir
		$reponse['query'] = 'checkSecurityData';	//nom de la fonction mis en dur car cette query n'est jamais appaelé mais elle est nécessaire pour le tableau d'erreur coté AV	
		return $reponse;
	}
	
	$reponse['message'] = "Identifiants Client Ok";	
	$reponse['debug'] = "Identifiants Client Ok";	
	$reponse['return'] = 1; //A definir
	$reponse['sign'] = SHA1($POST_DATA['query'].$local_idWebsite.$local_secureKey); 
	$reponse['query'] = 'checkSecurityData';	//nom de la fonction mis en dur car cette query n'est jamais appaelé mais elle est nécessaire pour le tableau d'erreur coté AV
	return $reponse;

}


/* ############ FIN DU TRAITEMENT ############*/ 



/**############ FONCTIONS ############ **/


/**
 * Mise à jour de la configuration du site 
 *
 * @param $POST_DATA
 * Config Prestashop mis à jour : 
 * AVISVERIFIES_PROCESSINIT : (varchar) onorder ou onorderstatuschange | Evenement qui initie la demande d'avis
 * AVISVERIFIES_ORDERSTATESCHOOSEN : (array) tableau des statuts choisis pour envoi des demandes d'avis 
 * AVISVERIFIES_GETPRODREVIEWS : (varchar) yes ou no | Récupérer les avis produits
 * AVISVERIFIES_DISPLAYPRODREVIEWS : (varchar) yes ou no | Afficher les avis produits sur les fiches
 * AVISVERIFIES_SCRIPTFIXE_ALLOWED : (varchar) yes ou non | Afficher le widget fixe
 * AVISVERIFIES_SCRIPTFLOAT_ALLOWED: (varchar) yes ou non | Afficher le widget flottant
 * AVISVERIFIES_SCRIPTFIXE : (varchar) script Js | JS affichant le widget fixe
 * AVISVERIFIES_SCRIPTFIXE_POSITION : (varchar) left ou right | Position du widget fixe
 * AVISVERIFIES_SCRIPTFLOAT : (varchar) script Js | JS affichant le widget flottant
 * AVISVERIFIES_FORBIDDEN_EMAIL : (array) Liste des extentions d'emails pour lesquels il ne faut pas envoyer de demandes d'avis (exemple : marketplace)
 * @return $reponse : code erreur + erreur
 */

function setModuleConfiguration(&$POST_DATA) {

	$unserialized_message = unserialize(AvisVerifiesModel::AC_decode_base64($POST_DATA['message']));

	if(!empty($unserialized_message)){	

		if(isset($unserialized_message['id_shop'])){ //Cas du multisite, l'id_shop a configuré a été envoyé
			
			//Structure pour multisite: updateValue($key, $values, $html = false, $id_shop_group = null, $id_shop = null)

			Configuration::updateValue('AVISVERIFIES_PROCESSINIT', $unserialized_message['init_reviews_process'], false, null, $unserialized_message['id_shop']);

			//Implode si plusieurs éléments donc is_array
			$ORDERSTATESCHOOSEN = (is_array($unserialized_message['id_order_status_choosen'])) ? implode(';', $unserialized_message['id_order_status_choosen']) : $unserialized_message['id_order_status_choosen'];

			Configuration::updateValue('AVISVERIFIES_ORDERSTATESCHOOSEN', $ORDERSTATESCHOOSEN, false, null, $unserialized_message['id_shop']);
			Configuration::updateValue('AVISVERIFIES_DELAY', $unserialized_message['delay'], false, null, $unserialized_message['id_shop']);

			Configuration::updateValue('AVISVERIFIES_GETPRODREVIEWS', $unserialized_message['get_product_reviews'], false, null, $unserialized_message['id_shop']);
			Configuration::updateValue('AVISVERIFIES_DISPLAYPRODREVIEWS',  $unserialized_message['display_product_reviews'], false, null, $unserialized_message['id_shop']);

			Configuration::updateValue('AVISVERIFIES_SCRIPTFIXE_ALLOWED',  $unserialized_message['display_fixe_widget'], false, null, $unserialized_message['id_shop']);
			Configuration::updateValue('AVISVERIFIES_SCRIPTFIXE_POSITION',  $unserialized_message['position_fixe_widget'], false, null, $unserialized_message['id_shop']);

			Configuration::updateValue('AVISVERIFIES_SCRIPTFLOAT_ALLOWED',  $unserialized_message['display_float_widget'], false, null, $unserialized_message['id_shop']);

			Configuration::updateValue('AVISVERIFIES_URLCERTIFICAT', $unserialized_message['url_certificat'], false, null, $unserialized_message['id_shop']);

			//Implode si plusieurs éléments donc is_array
			$FORBIDDENEMAIL = (is_array($unserialized_message['forbidden_mail_extension'])) ? implode(';',$unserialized_message['forbidden_mail_extension']) : $unserialized_message['forbidden_mail_extension'];
			Configuration::updateValue('AVISVERIFIES_FORBIDDEN_EMAIL', $FORBIDDENEMAIL, false, null, $unserialized_message['id_shop']); 

			Configuration::updateValue('AVISVERIFIES_SCRIPTFIXE',str_replace(array("\r\n", "\n"), '',  $unserialized_message['script_fixe_widget']), true, null, $unserialized_message['id_shop']);

			Configuration::updateValue('AVISVERIFIES_SCRIPTFLOAT',str_replace(array("\r\n", "\n"), '',  $unserialized_message['script_float_widget']), true, null, $unserialized_message['id_shop']);

			$reponse['sign'] = SHA1($POST_DATA['query'].Configuration::get('AVISVERIFIES_IDWEBSITE', null, null, $unserialized_message['id_shop']).Configuration::get('AVISVERIFIES_CLESECRETE', null, null, $unserialized_message['id_shop']));
			$reponse['message'] = _getModuleAndSiteInfos($unserialized_message['id_shop']);
		}
		else{

			Configuration::updateValue('AVISVERIFIES_PROCESSINIT', $unserialized_message['init_reviews_process']);

			//Implode si plusieurs éléments donc is_array
			$ORDERSTATESCHOOSEN = (is_array($unserialized_message['id_order_status_choosen'])) ? implode(';', $unserialized_message['id_order_status_choosen']) : $unserialized_message['id_order_status_choosen'];

			Configuration::updateValue('AVISVERIFIES_ORDERSTATESCHOOSEN', $ORDERSTATESCHOOSEN);
			Configuration::updateValue('AVISVERIFIES_DELAY', $unserialized_message['delay']);

			Configuration::updateValue('AVISVERIFIES_GETPRODREVIEWS', htmlentities($unserialized_message['get_product_reviews']));
			Configuration::updateValue('AVISVERIFIES_DISPLAYPRODREVIEWS',  htmlentities($unserialized_message['display_product_reviews']));

			Configuration::updateValue('AVISVERIFIES_SCRIPTFIXE_ALLOWED',  htmlentities($unserialized_message['display_fixe_widget']));
			Configuration::updateValue('AVISVERIFIES_SCRIPTFIXE_POSITION',  htmlentities($unserialized_message['position_fixe_widget']));
			Configuration::updateValue('AVISVERIFIES_SCRIPTFLOAT_ALLOWED',  htmlentities($unserialized_message['display_float_widget']));

			Configuration::updateValue('AVISVERIFIES_URLCERTIFICAT',htmlentities($unserialized_message['url_certificat']));

			//Implode si plusieurs éléments donc is_array
			$FORBIDDENEMAIL = (is_array($unserialized_message['forbidden_mail_extension'])) ? implode(';',$unserialized_message['forbidden_mail_extension']) : $unserialized_message['forbidden_mail_extension'];
			Configuration::updateValue('AVISVERIFIES_FORBIDDEN_EMAIL', $FORBIDDENEMAIL); 

		
			Configuration::updateValue('AVISVERIFIES_SCRIPTFIXE',str_replace(array("\r\n", "\n"), '',  $unserialized_message['script_fixe_widget']), true);

			
			Configuration::updateValue('AVISVERIFIES_SCRIPTFLOAT',str_replace(array("\r\n", "\n"), '',  $unserialized_message['script_float_widget']), true);


			$reponse['sign'] = SHA1($POST_DATA['query'].Configuration::get('AVISVERIFIES_IDWEBSITE').Configuration::get('AVISVERIFIES_CLESECRETE'));

			$reponse['message'] = _getModuleAndSiteInfos();

		}		
	
		$reponse['debug'] = "La configuration du site a été mise à jour";		
		$reponse['return'] = 1; //A definir		
		$reponse['query'] = $POST_DATA['query'];
		
	}
	else{
		$reponse['debug'] ="Aucune données reçues par le site dans $_POST[message]";
		$reponse['message'] ="Aucune données reçues par le site dans $_POST[message]";
		$reponse['return'] = 2; //A definir
		$reponse['sign'] = SHA1($POST_DATA['query'].Configuration::get('AVISVERIFIES_IDWEBSITE').Configuration::get('AVISVERIFIES_CLESECRETE'));
		$reponse['query'] = $POST_DATA['query'];
	}
	
	return $reponse;

}



function setTraductionFiles(&$POST_DATA){

	$unserialized_message = unserialize(AvisVerifiesModel::AC_decode_base64($POST_DATA['message']));


	$filePath_fr = _PS_MODULE_DIR_.'avisverifies/fr.php';
	$filePath_es = _PS_MODULE_DIR_.'avisverifies/es.php';
	$filePath_15_fr = _PS_MODULE_DIR_.'avisverifies/translations/fr.php';
	$filePath_15_es = _PS_MODULE_DIR_.'avisverifies/translations/es.php';

	$filePath_fr = fopen($filePath_fr, 'w');
	fwrite($filePath_fr,unserialize(AvisVerifiesModel::AC_decode_base64($unserialized_message['message']['fr'])));

	$filePath_es = fopen($filePath_es, 'w');
	fwrite($filePath_es,unserialize(AvisVerifiesModel::AC_decode_base64($unserialized_message['message']['es'])));

	$filePath_15_fr = fopen($filePath_15_fr, 'w');
	fwrite($filePath_15_fr,unserialize(AvisVerifiesModel::AC_decode_base64($unserialized_message['message']['fr'])));

	$filePath_15_es = fopen($filePath_15_es, 'w');
	fwrite($filePath_15_es,unserialize(AvisVerifiesModel::AC_decode_base64($unserialized_message['message']['es'])));


	$reponse['return'] = 1;

	foreach ($unserialized_message['message'] as $key => $value) {
		$reponse['message'][] = "Le fichier de traduction [".$key."]  a été correctement installé";
	}
	
	$reponse['debug']['fr'] = $unserialized_message['message']['fr'];
	$reponse['debug']['es'] = $unserialized_message['message']['es'];
	return $reponse;

}

/**
 * Efface le contenu des tables av_products_reviews et av_products_average
 *
 * @param $POST_DATA : paramètres envoyés par AV
 * @return $reponse : array contenant des infos de debugage
 */


function truncateTables(&$POST_DATA){

	$unserialized_message = unserialize(AvisVerifiesModel::AC_decode_base64($POST_DATA['message']));

	$query[] = 'TRUNCATE TABLE '._DB_PREFIX_.'av_products_reviews;' ;
	$query[] = 'TRUNCATE TABLE '._DB_PREFIX_.'av_products_average' ;

	$reponse['return']=1;
	$reponse['debug'] ="Tables vidées";
	$reponse['message'] ="Tables vidées";

	foreach ($query as $k => $sql){
		if(!Db::getInstance()->Execute($sql)) {
			$this->errors = $this->l('SQL ERROR : $sql | La table n\'a pas été créé');
			$reponse['return']=2;
			$reponse['debug'] ="Tables non vidées";
			$reponse['message'] ="Tables non vidées";
		}
	}
	

	$reponse['sign'] = SHA1($POST_DATA['query'].Configuration::get('AVISVERIFIES_IDWEBSITE').Configuration::get('AVISVERIFIES_CLESECRETE'));
	$reponse['query'] = $unserialized_message['query']; 
	return $reponse;
	
}



/**
 * Vérifie si le module est installé/activé
 *
 * @param $POST_DATA : paramètres envoyés par AV
 * @return Etat
 */

function isActiveModule(&$POST_DATA){

	$active = false;
	$unserialized_message = unserialize(AvisVerifiesModel::AC_decode_base64($POST_DATA['message']));


	if(isset($unserialized_message['id_shop']) && !empty($unserialized_message['id_shop'])){
		
		$id_module = Db::getInstance()->getValue("SELECT id_module FROM "._DB_PREFIX_."module WHERE name = 'avisverifies'");
		if (Db::getInstance()->getValue("SELECT id_module FROM "._DB_PREFIX_."module_shop WHERE id_module = ".(int)$id_module." AND id_shop = ".(int)$unserialized_message['id_shop']))
			$active = true;
	
	}
	else{

		if(Db::getInstance()->getValue("SELECT active FROM "._DB_PREFIX_."module WHERE name LIKE 'avisverifies'" ))
			$active = true;
	}
	

	if(empty($active)){
		$reponse['debug'] ="Modulé Désactivé";
		$reponse['return']=2; //Module désactivé
		$reponse['query'] = 'isActiveModule'; 
		return $reponse;

	}

	$reponse['debug'].="Modulé Installé et activé";
	$reponse['sign'] = SHA1($POST_DATA['query'].Configuration::get('AVISVERIFIES_IDWEBSITE').Configuration::get('AVISVERIFIES_CLESECRETE'));
	$reponse['return'] = 1; //Module OK
	$reponse['query'] = $query; 

	return $reponse;
}

/**
 * Récupère la configuration du module et du site 
 *
 * @param $POST_DATA : paramètres envoyés par AV
 * @return $reponse : array contenant des infos de debugage
 */

function getModuleAndSiteConfiguration(&$POST_DATA) {

	$unserialized_message = unserialize(AvisVerifiesModel::AC_decode_base64($POST_DATA['message']));		
	
	if(isset($unserialized_message['id_shop']) && !empty($unserialized_message['id_shop'])){
		
		$reponse['message'] = _getModuleAndSiteInfos($unserialized_message['id_shop']);
		$reponse['sign'] = SHA1($unserialized_message['query'].Configuration::get('AVISVERIFIES_IDWEBSITE', null, null,$unserialized_message['id_shop'] ).Configuration::get('AVISVERIFIES_CLESECRETE', null, null,$unserialized_message['id_shop']));

	}
	else{

		$reponse['message'] = _getModuleAndSiteInfos();
		$reponse['sign'] = SHA1($unserialized_message['query'].Configuration::get('AVISVERIFIES_IDWEBSITE').Configuration::get('AVISVERIFIES_CLESECRETE'));
	}

	$reponse['query'] = $unserialized_message['query'];

	if(empty($reponse['message'] )){
		$reponse['return'] = 2;
	}
	else{
		$reponse['return'] = 1;
	}
	

	return $reponse;

}


/**
 * Récupère les commandes
 *
 * @param $query : $POST_DATA
 * @return commandes (array)
 */

function getOrders(&$POST_DATA){



	$post_message = unserialize(AvisVerifiesModel::AC_decode_base64($POST_DATA["message"]));

	if(isset($post_message['id_shop']) && !empty($post_message['id_shop'])) {		

		$allowed_products = Configuration::get('AVISVERIFIES_GETPRODREVIEWS', null, null, $post_message['id_shop']); //récupérer les avis produits ou non 
		$process_choosen = Configuration::get('AVISVERIFIES_PROCESSINIT', null, null, $post_message['id_shop']); //onorder or onorderstatuschange
		$status_choosen = Configuration::get('AVISVERIFIES_ORDERSTATESCHOOSEN', null, null, $post_message['id_shop']); //status choisis
		$forbidden_mail_extensions = explode(';', Configuration::get('AVISVERIFIES_FORBIDDEN_EMAIL', null, null, $post_message['id_shop'])); //emails interdit
	}
	else{
		$allowed_products = Configuration::get('AVISVERIFIES_GETPRODREVIEWS'); //récupérer les avis produits ou non 
		$process_choosen = Configuration::get('AVISVERIFIES_PROCESSINIT'); //onorder or onorderstatuschange
		$status_choosen = Configuration::get('AVISVERIFIES_ORDERSTATESCHOOSEN'); //status choisis
		$forbidden_mail_extensions = explode(';', Configuration::get('AVISVERIFIES_FORBIDDEN_EMAIL')); //emails interdit
	}

	if(isset($post_message['force']) && $post_message['force'] == 1) {	 //Forcer la récupération de toutes les commandes			

		if(isset($post_message['date_deb']) && !empty($post_message['date_deb']) && isset($post_message['date_fin']) && !empty($post_message['date_fin'])) { //Pour forcer la récupération, il faut renseigner une plage de date

			$query_id_shop = (isset($post_message['id_shop']) && !empty($post_message['id_shop'])) ? 'AND o.id_shop = '.$post_message['id_shop'] : '';				

			$orders_list = Db::getInstance()->ExecuteS("SELECT o.date_add as date_order, o.id_order, oh.av_flag, oh.av_horodate_get, o.id_customer, oh.date_add as date_last_status_change, oh.id_order_state
														FROM  "._DB_PREFIX_."order_history oh
														LEFT JOIN "._DB_PREFIX_."orders o
														ON o.id_order = oh.id_order
														WHERE (oh.av_flag IS NULL OR oh.av_flag = 0) 
														AND o.date_add >= '".date('Y-m-d H:i:s',$post_message['date_deb'])."'
														AND o.date_add <= '".date('Y-m-d H:i:s',$post_message['date_fin'])."'
														".$query_id_shop);


			$reponse['debug']['mode'] = "[forcé] ".Db::getInstance()->numRows()." commandes récupérées en force du ".date('d/m/Y H:i:s',$post_message['date_deb'])." au ". date('d/m/Y H:i:s',$post_message['date_fin']);
			$reponse['debug'][] = "SELECT o.date_add as date_order, o.id_order, oh.av_flag, oh.av_horodate_get, o.id_customer, oh.date_add as date_last_status_change, oh.id_order_state
														FROM  "._DB_PREFIX_."order_history oh
														LEFT JOIN "._DB_PREFIX_."orders o
														ON o.id_order = oh.id_order
														WHERE (oh.av_flag IS NULL OR oh.av_flag = 0)
														AND o.date_add >= '".date('Y-m-d H:i:s',$post_message['date_deb'])."'
														AND o.date_add <= '".date('Y-m-d H:i:s',$post_message['date_fin'])."'
														".$query_id_shop;
		}
		else{
			$reponse['debug'][] = "Aucune période renseignée pour la récupération des commandes en mode forcé";
			return $reponse;
		}
	}
	elseif($process_choosen == ONORDER){	

		if(isset($post_message['id_shop']) && !empty($post_message['id_shop'])){		

			
			$query = "	SELECT oh.date_add as date_last_status_change, oh.id_order, oh.id_order_state, o.date_add as date_order, oh.av_horodate_get, o.id_customer
						FROM  "._DB_PREFIX_."order_history oh
						LEFT JOIN "._DB_PREFIX_."orders o
						ON oh.id_order = o.id_order
						WHERE (oh.av_flag IS NULL OR oh.av_flag = 0) 
						AND o.id_shop = ".$post_message['id_shop'];
		}
		else{

			$query = "	SELECT oh.date_add as date_last_status_change, oh.id_order, oh.id_order_state, o.date_add as date_order, oh.av_horodate_get, o.id_customer
						FROM  "._DB_PREFIX_."order_history oh
						LEFT JOIN "._DB_PREFIX_."orders o
						ON oh.id_order = o.id_order
						WHERE (oh.av_flag IS NULL OR oh.av_flag = 0)";		

		}

		$orders_list = Db::getInstance()->ExecuteS($query);

		$reponse['debug'][] = $query;	
		$reponse['debug']['mode'] = "[onorder] ". Db::getInstance()->numRows()." commandes récupérées";
		
	}
	elseif($process_choosen == ONORDERSTATUSCHANGE){	

		$arra_status_choosen = explode(';', $status_choosen);
		$count_status_choosen = count($arra_status_choosen);		

		if(!empty($arra_status_choosen) && $count_status_choosen >= 1){			

			if ($count_status_choosen > 1) {
				
				$having = "";

				foreach ($arra_status_choosen as $key => $status) {

					if($key == 0){
						$having .= " AND (oh.id_order_state = " . $status;
					}
					elseif($key > 0 && $key < ($count_status_choosen - 1)) {
						$having .= " OR oh.id_order_state = " . $status;
					}
					else{
						$having .= " OR oh.id_order_state = " . $status . ")";
					}
				
				}
				
			}
			else{

				$having = " AND oh.id_order_state = ".$arra_status_choosen[0];

			}	


			if(isset($post_message['id_shop']) && !empty($post_message['id_shop'])){			

				$query = "		SELECT oh.date_add as date_last_status_change, oh.id_order, oh.id_order_state, o.date_add as date_order, oh.av_horodate_get, o.id_customer
								FROM  "._DB_PREFIX_."order_history oh
								LEFT JOIN "._DB_PREFIX_."orders o
								ON oh.id_order = o.id_order
								WHERE (oh.av_flag IS NULL OR oh.av_flag = 0)
								AND o.id_shop = ".$post_message['id_shop']
								.$having;
			}
			else{

				$query = "		SELECT oh.date_add as date_last_status_change, oh.id_order, oh.id_order_state, o.date_add as date_order, oh.av_horodate_get, o.id_customer
								FROM  "._DB_PREFIX_."order_history oh
								LEFT JOIN "._DB_PREFIX_."orders o
								ON oh.id_order = o.id_order
								WHERE (oh.av_flag IS NULL OR oh.av_flag = 0)"
								.$having;		

			}

			$orders_list = Db::getInstance()->ExecuteS($query);

			$reponse['debug'][] = $query;
			$reponse['debug']['mode'] = "[onorderstatuschange] ".Db::getInstance()->numRows()." commandes récupérées avec statut ".$status_choosen;
			
		}
		else{
			$reponse['debug'][] = "Aucun statut n'a été renseigné pour la récupération des commandes en fonction de leur statut";
			$reponse['return'] = 2;
			return $reponse;
		}

	}
	else{
		$reponse['debug'][] = "Aucun évènement onorder ou onorderstatuschange n'a été renseigné pour la récupération des commandes";
		$reponse['return'] = 3;
		return $reponse;
	}

	$orders_list_toreturn = array();		
	$forbidden_orders = 0;

	foreach ($orders_list as $order) {		


		//$reponse['debug'][] = "SELECT oh.id_order FROM "._DB_PREFIX_."order_history oh LEFT JOIN "._DB_PREFIX_."orders o ON oh.id_order = o.id_order WHERE o.id_shop = ".$post_message['id_shop']."  AND oh.id_order = ".$order['id_order']." AND oh.av_flag = 1";
		
		//Vérifie si cette commande n'a pas déjà été flaggué (cas d'un changement de statut après flag par AV)	
		if(isset($post_message['id_shop']) && !empty($post_message['id_shop'])){
			$already_flag_order_query = "SELECT oh.id_order FROM "._DB_PREFIX_."order_history oh LEFT JOIN "._DB_PREFIX_."orders o ON oh.id_order = o.id_order WHERE o.id_shop = ".$post_message['id_shop']."  AND oh.id_order = ".$order['id_order']." AND oh.av_flag = 1";
			$already_flag_order = Db::getInstance()->getValue($already_flag_order_query);
		}
		else{
			$already_flag_order_query = "SELECT oh.id_order FROM "._DB_PREFIX_."order_history oh WHERE oh.id_order = ".$order['id_order']." AND oh.av_flag = 1";
			$already_flag_order = Db::getInstance()->getValue($already_flag_order_query);
		}				

		$reponse['debug']['test_cmd_blok'][$order['id_order']] = $already_flag_order ; 
		

		$o_customer = new Customer($order['id_customer']);		
		$customer_email_extension =  explode('@', $o_customer->email);

		if(!in_array($customer_email_extension[1],  $forbidden_mail_extensions)){	

			if(empty($already_flag_order) && !$orders_list_toreturn[$order['id_order']]){
				$array_order = array(
					
					'id_order' => $order['id_order'],
					'id_customer' => $order['id_customer'],
					'date_order' => strtotime($order['date_order']), //date timestamp de la table orders
					'date_order_formatted' => $order['date_order'], //date de la table orders formatté			
					'date_last_status_change' => $order['date_last_status_change'], //date de la table order_history de dernier changement de statut
					'date_av_getted_order' => $order['av_horodate_get'], //date de la table order_history de récup par AV
					'is_flag' => $order['flag'], //si la commande est déjà flaggué		
					'state_order' => $order['id_order_state'],
					'firstname_customer' => $o_customer->firstname,
					'lastname_customer' => $o_customer->lastname,
					'email_customer' => $o_customer->email,
					'products' => array()

					);

				
				if(!empty($allowed_products) && $allowed_products == 'yes'){ // Ajout des produits au tableau si autorisé

					$o_order = new Order($order['id_order']);
					$products_in_order = $o_order->getProducts();

					$array_products = array();			

					foreach ($products_in_order as $element) {

						$product = array(
							'id_product' => $element['product_id'],
							'name_product' => $element['product_name']
						);

						array_push($array_products, $product);
						unset($product);
					}

					$array_order['products'] = $array_products;
					unset($array_products);
				}					
				
				$orders_list_toreturn[$order['id_order']] = $array_order;

				
			}
			else{
				$reponse['message']['Commandes_bloques'][] = $order['id_order']; // Commande bloqué, déjà flagg par AV mais un autre enregistrement non flagg est présent dans order_history
			}

			
		}
		else{

			$reponse['message']['Emails_Interdits'][] = 'Commande n°'.$order['id_order'].' Email:'.$o_customer->email;
			$forbidden_orders++;			

		}
		//unset($array_order);

		//Les commandes récupérées sont flaggés à 1 avec horodate de récupération sauf si le client a récupéré les commandes (pour test) depuis le backoffice avis vérifiés
		if(!isset($post_message['no_flag']) OR $post_message['no_flag'] == 0)	
			Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'order_history SET av_horodate_get = "'.time().'", av_flag = 1 WHERE id_order = '.$order['id_order']);
		

	}
		
	//$reponse['debug']['to_flag'] = (!isset($post_message['to_flag']) OR $post_message['to_flag'] == 0) ? 'Aucun flag de commandes modifiés' : 'Flag de commandes modifiés' ;	

	$reponse['return'] = 1;
	$reponse['sign'] = (isset($post_message['id_shop']) && !empty($post_message['id_shop'])) ? SHA1($POST_DATA['query'].Configuration::get('AVISVERIFIES_IDWEBSITE',null,null,$post_message['id_shop']).Configuration::get('AVISVERIFIES_CLESECRETE',null,null,$post_message['id_shop'])) : SHA1($POST_DATA['query'].Configuration::get('AVISVERIFIES_IDWEBSITE').Configuration::get('AVISVERIFIES_CLESECRETE')); 
	$reponse['query'] = $POST_DATA['query'];
	$reponse['message']['nb_orders'] = count($orders_list_toreturn);
	$reponse['message']['delay'] = (isset($post_message['id_shop']) && !empty($post_message['id_shop'])) ? Configuration::get('AVISVERIFIES_DELAY',null,null,$post_message['id_shop']) : Configuration::get('AVISVERIFIES_DELAY');	
	$reponse['message']['nb_orders_bloques'] = count($reponse['message']['Commandes_bloques']);
	$reponse['message']['list_orders'] = $orders_list_toreturn;
	$reponse['debug']['force'] = $post_message['force'];
	$reponse['debug']['no_flag'] = $post_message['no_flag'];
	
	return $reponse;
	
}




/**
 * Récupération et mise à jour des avis produits transmis par AvisVerifies.
 *
 * @param $post_data : Données POST passées par AvisVerifies (CRON)
 * @return 
 */

function setProductsReviews(&$post_data) {

	$microtime_deb = microtime();
	
	$message = unserialize(AvisVerifiesModel::AC_decode_base64($post_data["message"]));

	$reviews = (isset($message['data']) && !empty($message['data'])) ? $message['data'] : null ;

	$arra_line_reviews = (!empty($reviews)) ? explode("\n",$reviews) : array(); //Array des lignes (séparateur \n)

	$count_line_reviews = count($arra_line_reviews);

	$count_update_new = 0;
	$count_delete = 0;
	$count_error = 0;

	
	foreach ($arra_line_reviews as $line_review) {

		
		$arra_column = explode("\t",$line_review); //Récupération des colonnes pour chaque ligne, dans un array (séparateur \t = tabulation)
		
		$count_column = count($arra_column);

		if(!empty($arra_column[0]) ){ //Vérifie si NEW ou UPDATE ou DELETE existe


			if($arra_column[0] == 'NEW' OR $arra_column[0] == 'UPDATE') {

				
				if(isset($arra_column[11]) && $arra_column[11] > 0){ //Vérification de la présence d'échanges (nombre d'échange stocké dans 11)

					if(($arra_column[11] * 3 + 12) == $count_column){ //Teste si le nom de paramètres est correct : 13 paramètres sont passés puis 3 par échange
					
						for($i = 0 ; $i < $arra_column[11] ; $i++){

							$arra_column['discussion'][] =  
												array(
													'horodate' => $arra_column[11 + ($i * 3) + 1],
													'origine' => $arra_column[11 + ($i * 3) + 2],
													'commentaire' => $arra_column[11 + ($i * 3) + 3],
												);					
						}

						/*Db::getInstance()->Execute("REPLACE INTO "._DB_PREFIX_."av_products_reviews (id_product_av, ref_product, rate, review, horodate, customer_name, discussion)
													VALUES ('".$arra_column[2]."',
														'".$arra_column[4]."',
														'".$arra_column[7]."',
														'".$arra_column[6]."',
														'".$arra_column[5]."',
														'".ucfirst($arra_column[8][0]).". " .ucfirst($arra_column[9])."',
														'".AvisVerifiesModel::AC_encode_base64(serialize($arra_column["discussion"]))."'
													)");*/

						Db::getInstance()->Execute("REPLACE INTO "._DB_PREFIX_."av_products_reviews (id_product_av, ref_product, rate, review, horodate, customer_name, discussion)
													VALUES ('".$arra_column[2]."',
														'".$arra_column[4]."',
														'".$arra_column[7]."',
														'".urlencode($arra_column[6])."',
														'".$arra_column[5]."',
														'".urlencode(ucfirst($arra_column[8][0]).". " .ucfirst($arra_column[9]))."',
														'".AvisVerifiesModel::AC_encode_base64(serialize($arra_column["discussion"]))."'
													)");

						$count_update_new++;

						//if(!Db::getInstance()->numRows()) { $count_error++ ; }  else { $count_update_new += Db::getInstance()->numRows(); } //Incrémente le compteur si l'enregistrement a été fait 
					}

					else {
						$reponse['debug'][$arra_column[2]] = 'Nombre de paramètres passés par la ligne incohérents (Nb échanges : '.$arra_column[11].')  : '.$count_column;
						$count_error++;
					}
					
				}
				elseif((!isset($arra_column[11]) OR empty($arra_column[11]) OR $arra_column[11] == 0)){ // Pas d'échange

					if(($arra_column[11] * 3 + 12) == count($arra_column)) {

						/*Db::getInstance()->Execute("REPLACE INTO "._DB_PREFIX_."av_products_reviews (id_product_av, ref_product, rate, review, horodate, customer_name, discussion)
													VALUES ('".$arra_column[2]."',
														'".$arra_column[4]."',
														'".$arra_column[7]."',
														'".$arra_column[6]."',
														'".$arra_column[5]."',
														'".ucfirst($arra_column[8][0]).". " .ucfirst($arra_column[9])."',
														null
													)");*/


						//$reponse['debug'] = ;
						Db::getInstance()->Execute("REPLACE INTO "._DB_PREFIX_."av_products_reviews (id_product_av, ref_product, rate, review, horodate, customer_name, discussion)
													VALUES ('".$arra_column[2]."',
														'".$arra_column[4]."',
														'".$arra_column[7]."',
														'".urlencode($arra_column[6])."',
														'".$arra_column[5]."',
														'".urlencode(ucfirst($arra_column[8][0]).". " .ucfirst($arra_column[9]))."',
														null
													)");
						 
						$count_update_new++;

						//if(!Db::getInstance()->numRows()) { $count_error++ ; }  else { $count_update_new += Db::getInstance()->numRows(); } //Incrémente le compteur si l'enregistrement a été fait 
					}
					
					else {
						$reponse['debug'][$arra_column[2]] = 'Nombre de paramètres passés par la ligne incohérents (Nb échanges : '.$arra_column[11].')  : '.$count_column;
						$count_error++;
					}
				}





			}
			elseif($arra_column[0] == 'DELETE'){
				
				Db::getInstance()->Execute("DELETE FROM "._DB_PREFIX_."av_products_reviews WHERE id_product_av = '".$arra_column[2]."' AND ref_product = '".$arra_column[4]."'") ;
				$count_delete++;
				
			}
			
			elseif($arra_column[0] == 'AVG'){
				//AVG id_product_av ref_product rate nb_reviews

				Db::getInstance()->Execute("REPLACE INTO "._DB_PREFIX_."av_products_average (id_product_av, ref_product, rate, nb_reviews, horodate_update)
											VALUES ('".$arra_column[1]."',
													'".$arra_column[2]."',
													'".$arra_column[3]."',
													'".$arra_column[4]."',
													'".time()."'
												)
											") ;
				$count_update_new++;
			}
			else {
				$reponse['debug'][$arra_column[2]] = 'Aucune action (NEW, UPDATE, DELETE) envoyée : ['.$arra_column[0].']';
				$count_error++;
			}


		}	
		
	}

	
	//$total_query = Db::getInstance()->numRows(); // pb

	$microtime_fin = microtime();

	//$reponse['debug'] = "Enregistrements traités : " . count($arra_line_new_reviews);	

	$reponse['return'] = 1;
	$reponse['sign'] = (isset($post_message['id_shop']) && !empty($post_message['id_shop'])) ? SHA1($POST_DATA['query'].Configuration::get('AVISVERIFIES_IDWEBSITE',null,null,$post_message['id_shop']).Configuration::get('AVISVERIFIES_CLESECRETE',null,null,$post_message['id_shop'])) : SHA1($POST_DATA['query'].Configuration::get('AVISVERIFIES_IDWEBSITE').Configuration::get('AVISVERIFIES_CLESECRETE')); 
	$reponse['query'] = $POST_DATA['query'];
	$reponse['message']['lignes_recues'] = $arra_line_reviews;
	$reponse['message']['nb_update_new'] = $count_update_new;
	$reponse['message']['nb_delete'] = $count_delete;
	$reponse['message']['nb_errors'] = $count_error;
	//$reponse['message']['total_query'] = $total_query ;
	$reponse['message']['microtime'] = $microtime_fin - $microtime_deb;

	if($count_line_reviews != ($count_update_new + $count_delete + $count_error)){
		$reponse['debug'][] = "Une erreur s'est produite. Le nombre de lignes reçues ne correspond pas au nombre de lignes traitées par l'API. Des données ont quand même pu être enregistré";
	}
	
	//$reponse['message']['list_orders'] = $orders_list_toreturn;

	return $reponse;

}



/**
 * Récupère les infos module et site
 * Fonction privée, ne pas utiliser directement, cette fonction est utilisé dans setModuleConfiguration et getModuleConfiguration
 * @param $POST_DATA
 * @return array contenant la configuration du module et du site 
 */

function _getModuleAndSiteInfos($id_shop = null){

	$module_version = new AvisVerifies;
	$module_version = $module_version->version;

	$order_statut_list = OrderState::getOrderStates((int)Configuration::get('PS_LANG_DEFAULT'));

	$perms = fileperms(_PS_MODULE_DIR_.'avisverifies');

	if (($perms & 0xC000) == 0xC000) {
	    // Socket
	    $info = 's';
	} elseif (($perms & 0xA000) == 0xA000) {
	    // Lien symbolique
	    $info = 'l';
	} elseif (($perms & 0x8000) == 0x8000) {
	    // Régulier
	    $info = '-';
	} elseif (($perms & 0x6000) == 0x6000) {
	    // Block special
	    $info = 'b';
	} elseif (($perms & 0x4000) == 0x4000) {
	    // Dossier
	    $info = 'd';
	} elseif (($perms & 0x2000) == 0x2000) {
	    // Caractère spécial
	    $info = 'c';
	} elseif (($perms & 0x1000) == 0x1000) {
	    // pipe FIFO
	    $info = 'p';
	} else {
	    // Inconnu
	    $info = 'u';
	}

	// Autres
	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
	            (($perms & 0x0800) ? 's' : 'x' ) :
	            (($perms & 0x0800) ? 'S' : '-'));

	// Groupe
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
	            (($perms & 0x0400) ? 's' : 'x' ) :
	            (($perms & 0x0400) ? 'S' : '-'));

	// Tout le monde
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
	            (($perms & 0x0200) ? 't' : 'x' ) :
	            (($perms & 0x0200) ? 'T' : '-'));

	if(isset($id_shop) && !empty($id_shop)){

		$explode_secret_key = explode('-',Configuration::get('AVISVERIFIES_CLESECRETE', null, null, $id_shop));

		$return = array(
			'Version_PS' => _PS_VERSION_,
			'Version_Module' => $module_version,		
			'idWebsite' => Configuration::get('AVISVERIFIES_IDWEBSITE', null, null, $id_shop),
			'Nb_Multiboutique' => '',
			'Websites' => '',
			'Id_Website_encours' => '',
			'Cle_Secrete' => $explode_secret_key[0].'-xxxx-xxxx-'.$explode_secret_key[3],
			'Delay' => Configuration::get('AVISVERIFIES_DELAY', null, null, $id_shop),
			'Initialisation_du_Processus' => Configuration::get('AVISVERIFIES_PROCESSINIT', null, null, $id_shop),
			'Statut_choisi' => Configuration::get('AVISVERIFIES_ORDERSTATESCHOOSEN', null, null, $id_shop),
			'Recuperation_Avis_Produits' => Configuration::get('AVISVERIFIES_GETPRODREVIEWS', null, null, $id_shop),
			'Affiche_Avis_Produits' => Configuration::get('AVISVERIFIES_DISPLAYPRODREVIEWS', null, null, $id_shop),
			'Affichage_Widget_Flottant' => Configuration::get('AVISVERIFIES_SCRIPTFLOAT_ALLOWED', null, null, $id_shop),
			'Script_Widget_Flottant' => Configuration::get('AVISVERIFIES_SCRIPTFLOAT', null, null, $id_shop),
			'Affichage_Widget_Fixe' => Configuration::get('AVISVERIFIES_SCRIPTFIXE_ALLOWED', null, null, $id_shop),
			'Position_Widget_Fixe' => Configuration::get('AVISVERIFIES_SCRIPTFIXE_POSITION', null, null, $id_shop),
			'Script_Widget_Fixe' => Configuration::get('AVISVERIFIES_SCRIPTFIXE', null, null, $id_shop),
			'Emails_Interdits' => Configuration::get('AVISVERIFIES_FORBIDDEN_EMAIL', null, null, $id_shop),
			'Liste_des_statuts' => $order_statut_list,
			'Droit_du_dossier_AV' => $info,
			'Date_Recuperation_Config' => date('Y-m-d H:i:s')

		);


	}
	else{

		$explode_secret_key = explode('-',Configuration::get('AVISVERIFIES_CLESECRETE'));

		$return = array(
			'Version_PS' => _PS_VERSION_,
			'Version_Module' => $module_version,		
			'idWebsite' => Configuration::get('AVISVERIFIES_IDWEBSITE'),
			'Nb_Multiboutique' => '',
			'Websites' => '',
			'Id_Website_encours' => '',
			'Cle_Secrete' => $explode_secret_key[0].'-xxxx-xxxx-'.$explode_secret_key[3],
			'Delay' => Configuration::get('AVISVERIFIES_DELAY'),
			'Initialisation_du_Processus' => Configuration::get('AVISVERIFIES_PROCESSINIT'),
			'Statut_choisi' => Configuration::get('AVISVERIFIES_ORDERSTATESCHOOSEN'),
			'Recuperation_Avis_Produits' => Configuration::get('AVISVERIFIES_GETPRODREVIEWS'),
			'Affiche_Avis_Produits' => Configuration::get('AVISVERIFIES_DISPLAYPRODREVIEWS'),
			'Affichage_Widget_Flottant' => Configuration::get('AVISVERIFIES_SCRIPTFLOAT_ALLOWED'),
			'Script_Widget_Flottant' => Configuration::get('AVISVERIFIES_SCRIPTFLOAT'),
			'Affichage_Widget_Fixe' => Configuration::get('AVISVERIFIES_SCRIPTFIXE_ALLOWED'),
			'Position_Widget_Fixe' => Configuration::get('AVISVERIFIES_SCRIPTFIXE_POSITION'),
			'Script_Widget_Fixe' => Configuration::get('AVISVERIFIES_SCRIPTFIXE'),
			'Emails_Interdits' => Configuration::get('AVISVERIFIES_FORBIDDEN_EMAIL'),
			'Liste_des_statuts' => $order_statut_list,
			'Droit_du_dossier_AV' => $info,
			'Date_Recuperation_Config' => date('Y-m-d H:i:s')

		);

	}

	

	if(_PS_VERSION_ >= 1.5){
		$return['Nb_Multiboutique'] = Shop::getTotalShops();
		$return['Websites'] = Shop::getShops();
		//$return['Id_Website_encours'] = $this->context->$shop->id;		
	}

	return $return;

}







?>