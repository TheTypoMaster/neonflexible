<?php

/*
* 2013 AvisVerifies 
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author NetReviews SAS <contact@avis-verifies.com>
*  @copyright  2013 NetReviews SAS
*  @version  Release: $Revision: 7 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of NetReviews SAS
*/

if (!defined('_PS_VERSION_'))
	exit;



require_once _PS_MODULE_DIR_."avisverifies/models/AvisVerifiesModel.php";

define('ONORDER', 'onorder');
define('ONORDERSTATUSCHANGE', 'onorderstatuschange');	

class AvisVerifies extends Module
{

	private $_html = '';
	public $id_lang;
	public $iso_lang;	
	public $validates = array(); // Confirmation messages
	public $warnings = array(); 
	public $errors = array(); // Critical errors
	public $stats_product;

	

	function __construct(){		

		

		$this->name = 'avisverifies';
		$this->tab = 'advertising_marketing';
		$this->author = 'NetReviews';
		$this->version = "7.0.9";
		$this->need_instance = 0;
		$this->module_key = 'a65tt6ygert4azer34ru523re4rryuvt';

		parent::__construct();

		$this->displayName = $this->l('AvisVerifies');
		$this->description = $this->l('Ajouter le module AvisVerifies');
		//$this->warning = 'test;'; // Affiche un warning en haut de la page de module,need_instace doit être à 1

		

		if (self::isInstalled($this->name))
		{
			$this->id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
			$this->iso_lang = pSQL(Language::getIsoById($this->id_lang));				
						
		}

		
		// Retrocompatibility
		$this->initContext();

	}



	// Retrocompatibility 1.4/1.5
	private function initContext()
	{
	  if (class_exists('Context'))
	    $this->context = Context::getContext();
	  else
	  {
	    global $smarty, $cookie;
	    $this->context = new StdClass();
	    $this->context->smarty = $smarty;
	    $this->context->cookie = $cookie;
	  }
	}


	public function install()
	{		
		

		if(!$this->installDatabase() || !parent::install()){
			$this->errors = array('Installation error / Database configuration error');
			return false;
		}

		if(_PS_VERSION_ < 1.5){
			$this->registerHook('productTabContent');
			$this->registerHook('orderConfirmation');
			$this->registerHook('productTab');
			$this->registerHook('extraRight');
			//$this->registerHook('extraLeft');
			$this->registerHook('header');
			$this->registerHook('rightColumn');
			$this->registerHook('leftColumn');
		//	$this->registerHook('backOfficeHeader');
			//if(!$this->registerHook('updateOrderStatus')) $arra_errors[] = _l('une erreur durant la greffe sur le hook updateOrderStatus est survenue') ; 
			if(!$this->registerHook('OrderConfirmation')) ; 
		}else{
			$this->registerHook('displayProductTabContent');
			$this->registerHook('displayProductTab');
			$this->registerHook('displayRightColumnProduct');
			$this->registerHook('displayLeftColumnProduct');
			$this->registerHook('displayHeader');
			$this->registerHook('displayRightColumn');
			$this->registerHook('displayLeftColumn');
			//if(!$this->registerHook('actionOrderStatusUpdate')) $arra_errors[] = _l('une erreur durant la greffe sur le hook actionOrderStatusUpdate est survenue') ; 
			if(!$this->registerHook('displayOrderConfirmation')); 
		//	$this->registerHook('displayBackOfficeHeader');
		
		}	

		// Création des variables de configuration PS
		
		Configuration::updateValue('AVISVERIFIES_IDWEBSITE', '');
		Configuration::updateValue('AVISVERIFIES_CLESECRETE', '');
		Configuration::updateValue('AVISVERIFIES_PROCESSINIT', '');
		Configuration::updateValue('AVISVERIFIES_ORDERSTATESCHOOSEN', '');
		Configuration::updateValue('AVISVERIFIES_DELAY', '');

		Configuration::updateValue('AVISVERIFIES_GETPRODREVIEWS', '');
		Configuration::updateValue('AVISVERIFIES_DISPLAYPRODREVIEWS', '');
	
		Configuration::updateValue('AVISVERIFIES_CSVFILENAME','Export_AV_01-01-1970-default.csv');

		Configuration::updateValue('AVISVERIFIES_SCRIPTFLOAT','');
		Configuration::updateValue('AVISVERIFIES_SCRIPTFLOAT_ALLOWED','');

		Configuration::updateValue('AVISVERIFIES_SCRIPTFIXE','');
		Configuration::updateValue('AVISVERIFIES_SCRIPTFIXE_ALLOWED','');

		Configuration::updateValue('AVISVERIFIES_URLCERTIFICAT','');
		Configuration::updateValue('AVISVERIFIES_FORBIDDEN_EMAIL','');

		return true;
	}


	private function _postProcess(){

		if (Tools::isSubmit('submit_export'))
		{		
		
			try {
				$o_AV = new AvisVerifiesModel;

				if(Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1){
										
					$header_colums = $this->l('refcommande;email;nom;prenom;datecommande;delaiavis;refproduit;categorie;description;urlficheproduit;id_order_state;id_shop')."\r\n"; //Attention, ne pas utiliser de quote simple ' sinon ca ne fonctionne pas \r..
					$return_export = $o_AV->Export($this->context->shop->getContextShopID(),$header_colums);	
				}
				else {
					$header_colums = $this->l('refcommande;email;nom;prenom;datecommande;delaiavis;refproduit;categorie;description;urlficheproduit;id_order_state')."\r\n"; //Attention, ne pas utiliser de quote simple ' sinon ca ne fonctionne pas pour le \r..
					$return_export = $o_AV->Export(null,$header_colums);	
				}

				if(@file_exists($return_export[2])){
					$this->validates[] = sprintf($this->l('%s commandes ont été exportées.'),$return_export[1]).'<a href="../modules/avisverifies/'.$return_export[0].'"> '.$this->l('Cliquez ici pour télécharger le fichier').'</a>';
				}
				else{
					$this->errors[] = $this->l('Le fichier d\'export n\'a pas été créé car une erreur s\'est produite. Vous devez attribuer les droits 777 sur le dossier "avisverifies" de votre dossier "module"' ).$return_export[2];
				}
			
				
				
			} catch (Exception $e) {
				$this->errors[] = $e->getMessage();
			}			
			
		}

		if(Tools::isSubmit('submit_configuration')){
		
			Configuration::updateValue('AVISVERIFIES_IDWEBSITE', Tools::getValue('avisverifies_idwebsite'));
			Configuration::updateValue('AVISVERIFIES_CLESECRETE', Tools::getValue('avisverifies_clesecrete'));	
			$this->validates[] = $this->l('Les informations ont été enregistrés');
		

		}



	}


	public function getContent() {

		global $currentIndex, $smarty;

		if (!empty($_POST))
			$this->_postProcess();	
		

		if(_PS_VERSION_ >= 1.5){

			// There are 3 kinds of shop context : shop, group shop and general
			//CONTEXT_SHOP = 1;
			//CONTEXT_GROUP = 2;
			//CONTEXT_ALL = 4;
			
			if(Shop::getContext() == 4 OR Shop::getContext() == 2){

				$this->errors[] = $this->l('Attention : Ce module ne peut être configuré dans une configuration multi-site, vous devez d\'abord sélectionner ci-dessus la boutique à configurer');
			
			}
			
		}


		$smarty->assign(array(
				'validates' => $this->validates,
				'warnings' => $this->warnings,
				'errors' => $this->errors,
				'current_avisverifies_urlapi' => Configuration::get('AVISVERIFIES_URLAPI'),
				'current_avisverifies_idwebsite' => Configuration::get('AVISVERIFIES_IDWEBSITE'),
				'current_avisverifies_clesecrete' => Configuration::get('AVISVERIFIES_CLESECRETE'),		
				'url_back' => Tools::safeOutput($currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules')),			
				
		));
		

	
		if(_PS_VERSION_ < 1.5)
			return $this->display(__FILE__, 'views/templates/hook/avisverifies-backoffice.tpl');
		else
			return $this->display(__FILE__, 'avisverifies-backoffice.tpl');


	}



	public function hookHeader($params){
		
		$widgetFlottantCode = "";

		if(_PS_VERSION_ < 1.5){

			Tools::addCSS(($this->_path).'css/avisverifies-style-front.css', 'all'); 
			Tools::addJS(($this->_path).'js/avisverifies.js', 'all');

			if (Configuration::get('AVISVERIFIES_SCRIPTFLOAT_ALLOWED') != 'yes')
				return "";

			if (Configuration::get('AVISVERIFIES_SCRIPTFLOAT'))
				$widgetFlottantCode .= "\n".stripslashes(html_entity_decode(Configuration::get('AVISVERIFIES_SCRIPTFLOAT')));
		}
		else{
			$this->context->controller->addCSS(($this->_path).'css/avisverifies-style-front.css', 'all');
			$this->context->controller->addJS(($this->_path).'js/avisverifies.js', 'all');

			if (Configuration::get('AVISVERIFIES_SCRIPTFLOAT_ALLOWED', null, null, $this->context->shop->getContextShopID()) != 'yes')
				return "";

			if (Configuration::get('AVISVERIFIES_SCRIPTFLOAT'))
				$widgetFlottantCode .= "\n".stripslashes(html_entity_decode(Configuration::get('AVISVERIFIES_SCRIPTFLOAT', null, null, $this->context->shop->getContextShopID())));

		}

		return $widgetFlottantCode;
	}


	public function hookProductTab($params)
	{
		global $smarty, $cookie;

		if(_PS_VERSION_ < 1.5){
    		$display_prod_reviews = configuration::get('AVISVERIFIES_DISPLAYPRODREVIEWS');     	
    	}
    	else{    		
       		$display_prod_reviews = configuration::get('AVISVERIFIES_DISPLAYPRODREVIEWS', null, null, $this->context->shop->getContextShopID());        	
    	} 

		$id_product = (int)(Tools::getValue('id_product'));

		$o_av = new AvisVerifiesModel();

		$this->stats_product = $o_av->getStatsProduct($id_product);
			
		if($this->stats_product['nb_reviews'] < 1 OR $display_prod_reviews != 'yes') return ""; //Si Aucun avis, on retourne vide
		
		$smarty->assign(array('count_reviews' => $this->stats_product['nb_reviews']));
		
		if(_PS_VERSION_ < 1.5){			
			return ($this->display(__FILE__, '/views/templates/hook/avisverifies-tab.tpl'));

		}else{
			return ($this->display(__FILE__, 'avisverifies-tab.tpl'));
		}
	}



	public function hookProductTabContent($params){ //ATTENTION : la plupart des modifications effectuées ici doivent être reportées dans ajax-load.php

		global $smarty, $cookie;
		
		if(_PS_VERSION_ < 1.5){
    		$display_prod_reviews = configuration::get('AVISVERIFIES_DISPLAYPRODREVIEWS');     	
    	}
    	else{    		
       		$display_prod_reviews = configuration::get('AVISVERIFIES_DISPLAYPRODREVIEWS', null, null, $this->context->shop->getContextShopID());        	
    	} 

		$shop_name = Configuration::get('PS_SHOP_NAME');
		$id_product = (int)(Tools::getValue('id_product'));

		$o_av = new AvisVerifiesModel();

		$stats_product = $this->stats_product;;
		
		if($stats_product['nb_reviews'] < 1 OR $display_prod_reviews != 'yes') return ""; //Si Aucun avis, on retourne vide	

		$reviews = $o_av->getProductReviews($id_product, false, 0);			

		$reviews_list = array(); // Initialisation du tableau contenant les données relatives aux avis		
		
		foreach($reviews as $k => $review) {

			//Réaffection des variables aux variables à destination du template
			$my_review['ref_produit'] = $review['ref_product'];		
			$my_review['id_product_av'] = $review['id_product_av'];		
			$my_review['rate'] = $review['rate'];
			$my_review['avis'] = urldecode($review['review']);
			$my_review['horodate'] = date('d/m/Y',$review['horodate']);
			$my_review['customer_name'] = urldecode($review['customer_name']);
			$my_review['discussion'] = '';

			$unserialized_discussion = unserialize(AvisVerifiesModel::AC_decode_base64($review['discussion']));
			
			if($unserialized_discussion){

				foreach($unserialized_discussion as $k_discussion => $each_discussion) {
					
					$my_review['discussion'][$k_discussion]['commentaire'] = $each_discussion['commentaire'];
					$my_review['discussion'][$k_discussion]['horodate'] = date('d/m/Y', $each_discussion['horodate']);	

					if($each_discussion['origine'] == 'ecommercant') {
						$my_review['discussion'][$k_discussion]['origine'] = $shop_name;
					}
					elseif($each_discussion['origine'] == 'internaute'){
						$my_review['discussion'][$k_discussion]['origine'] = $my_review['customer_name'];
					}
					else{
						$my_review['discussion'][$k_discussion]['origine'] = $this->l('Modérateur');
					}


				}
			}	

			array_push($reviews_list, $my_review);		

		}
	
		$controller = new FrontController();
		$controller->pagination((int)$stats_product['nb_reviews']);
	

		$smarty->assign(array(				
			'current_url' =>  $_SERVER['REQUEST_URI'],				
			'reviews' => $reviews_list,
			'count_reviews' => $stats_product['nb_reviews'],
			'average_rate' => round($stats_product['rate'],1),
			'average_rate_percent' => $stats_product['rate'] * 20,
			'url_certificat' => Configuration::get('AVISVERIFIES_URLCERTIFICAT')
		));


		if(_PS_VERSION_ < 1.5){			
			return ($this->display(__FILE__, '/views/templates/hook/avisverifies-tab-content.tpl'));
			
		}else{
			return ($this->display(__FILE__, 'avisverifies-tab-content.tpl'));
		}

		

	}

	public function hookOrderConfirmation($params)
    {              

    	// Recuperation de l'ID_Website
    	
    	if(_PS_VERSION_ < 1.5){
    		$id_website = configuration::get('AVISVERIFIES_IDWEBSITE');
       		$secret_key = configuration::get('AVISVERIFIES_CLESECRETE');

    	}
    	else{
    		
       		$id_website = configuration::get('AVISVERIFIES_IDWEBSITE', null, null, $this->context->shop->getContextShopID());
        	$secret_key = configuration::get('AVISVERIFIES_CLESECRETE', null, null, $this->context->shop->getContextShopID());
    	}        

        
        if (!empty($id_website) && !empty($secret_key)){
       
            $id_order = Tools::getValue('id_order');
        
            $order = new Order((int)($id_order));
           
            $orderTotal = (100 * $order->total_paid);

            return "<img height='1' hspace='0' src='http://www.netreviews.eu/index.php?action=act_order&idWebsite=".$id_website."&langue=fr_FR&refCommande=".$id_order."&montant=".$orderTotal."' /> ";
           
        }

    }

    public function hookRightColumn($params){

    	$widgetCode = "\n";

    	if(_PS_VERSION_ < 1.5){
			
			if (Configuration::get('AVISVERIFIES_SCRIPTFIXE_ALLOWED') != 'yes' OR Configuration::get('AVISVERIFIES_SCRIPTFIXE_POSITION') != 'right')
				return "";

			if (Configuration::get('AVISVERIFIES_SCRIPTFIXE'))
				$widgetCode .= "\n<div align=\"center\">".stripslashes(html_entity_decode(Configuration::get('AVISVERIFIES_SCRIPTFIXE')))."</div><br clear=\"left\" /><br />";
		}
		else{
			
			if (Configuration::get('AVISVERIFIES_SCRIPTFIXE_ALLOWED', null, null, $this->context->shop->getContextShopID()) != 'yes' OR Configuration::get('AVISVERIFIES_SCRIPTFIXE_POSITION', null, null, $this->context->shop->getContextShopID()) != 'right')
				return "";

			if (Configuration::get('AVISVERIFIES_SCRIPTFIXE'))
				$widgetCode .= "\n<div align=\"center\">".stripslashes(html_entity_decode(Configuration::get('AVISVERIFIES_SCRIPTFIXE', null, null, $this->context->shop->getContextShopID())))."</div><br clear=\"left\" /><br />";

		}

		return $widgetCode;

    }

	public function hookLeftColumn($params){

    	$widgetCode = "\n";

    	if(_PS_VERSION_ < 1.5){
			
			if (Configuration::get('AVISVERIFIES_SCRIPTFIXE_ALLOWED') != 'yes' OR Configuration::get('AVISVERIFIES_SCRIPTFIXE_POSITION') != 'left')
				return "";

			if (Configuration::get('AVISVERIFIES_SCRIPTFIXE'))
				$widgetCode .= "\n<div align=\"center\">".stripslashes(html_entity_decode(Configuration::get('AVISVERIFIES_SCRIPTFIXE')))."</div><br clear=\"left\" /><br />";
		}
		else{
			
			if (Configuration::get('AVISVERIFIES_SCRIPTFIXE_ALLOWED', null, null, $this->context->shop->getContextShopID()) != 'yes' OR Configuration::get('AVISVERIFIES_SCRIPTFIXE_POSITION', null, null, $this->context->shop->getContextShopID()) != 'left')
				return "";

			if (Configuration::get('AVISVERIFIES_SCRIPTFIXE'))
				$widgetCode .= "\n<div align=\"center\">".stripslashes(html_entity_decode(Configuration::get('AVISVERIFIES_SCRIPTFIXE', null, null, $this->context->shop->getContextShopID())))."</div><br clear=\"left\" /><br />";

		}

		return $widgetCode;

    }

    public function hookExtraRight(){

    	global $smarty, $cookie;
		
		if(_PS_VERSION_ < 1.5){
    		$display_prod_reviews = configuration::get('AVISVERIFIES_DISPLAYPRODREVIEWS');     	
    	}
    	else{    		
       		$display_prod_reviews = configuration::get('AVISVERIFIES_DISPLAYPRODREVIEWS', null, null, $this->context->shop->getContextShopID());        	
    	} 

		$shop_name = Configuration::get('PS_SHOP_NAME');
		$id_product = (int)(Tools::getValue('id_product'));

		$o_av = new AvisVerifiesModel();

		$o = new AvisVerifiesModel();
		$reviews = $o->getStatsProduct($id_product);
		
		if($reviews['nb_reviews'] < 1 OR $display_prod_reviews != 'yes') return ""; //Si Aucun avis, on retourne vide	    			

		$smarty->assign(array(
									
						'av_nb_reviews' => $reviews['nb_reviews'],
						'av_rate' =>  $reviews['rate']
					));


    	return $this->display(__FILE__ , 'tpl/ExtraRight.tpl');

	
	    	

    }   


	public function displayProductTabContent(){
		return $this->hookProductTabContent();
	}
	
	public function displayProductTab($params){
		return $this->hookProductTab($params);
	}
	
	public function displayRightColumnProduct($params){
		return $this->hookExtraRight($params);
	}	

	public function hookDisplayHeader($params){
		return $this->hookHeader($params);
	}
	
	public function hookdisplayOrderConfirmation($params){
		return $this->hookOrderConfirmation($params);
	}


	public function hookdisplayRightColumn($params){
		return $this->hookRightColumn($params);
	}
	public function hookdisplayLeftColumn($params){
		return $this->hookLeftColumn($params);
	}


	
	//public function hookDisplayBackOfficeHeader($params){
	//	return $this->hookBackOfficeHeader($params);
	//}


   public function uninstall(){   	

		Configuration::deleteByName('AVISVERIFIES_IDWEBSITE');
		Configuration::deleteByName('AVISVERIFIES_CLESECRETE');
		Configuration::deleteByName('AVISVERIFIES_PROCESSINIT');
		Configuration::deleteByName('AVISVERIFIES_ORDERSTATESCHOOSEN');

		Configuration::deleteByName('AVISVERIFIES_DELAY');

		Configuration::deleteByName('AVISVERIFIES_GETPRODREVIEWS');
		Configuration::deleteByName('AVISVERIFIES_DISPLAYPRODREVIEWS');
	
		Configuration::deleteByName('AVISVERIFIES_CSVFILENAME');

		Configuration::deleteByName('AVISVERIFIES_SCRIPTFLOAT');
		Configuration::deleteByName('AVISVERIFIES_SCRIPTFLOAT_ALLOWED');

		Configuration::deleteByName('AVISVERIFIES_SCRIPTFIXE');
		Configuration::deleteByName('AVISVERIFIES_SCRIPTFIXE_POSITION');
		Configuration::deleteByName('AVISVERIFIES_SCRIPTFIXE_ALLOWED');

		Configuration::deleteByName('AVISVERIFIES_URLCERTIFICAT');
		Configuration::deleteByName('AVISVERIFIES_FORBIDDEN_EMAIL');

		if(_PS_VERSION_ < 1.5){
			$this->unregisterHook('productTabContent');
			$this->unregisterHook('productTab');
			$this->unregisterHook('extraRight');
			$this->unregisterHook('extraLeft');
			$this->unregisterHook('header');
			$this->unregisterHook('rightColumn');
			$this->unregisterHook('leftColumn');
			$this->unregisterHook('backOfficeHeader');
			$this->unregisterHook('OrderConfirmation');
			//$this->unregisterHook('updateOrderStatus');
		}else{
			$this->unregisterHook('displayProductTabContent');
			$this->unregisterHook('displayProductTab');
			$this->unregisterHook('displayRightColumnProduct');
			$this->unregisterHook('displayLeftColumnProduct');
			$this->unregisterHook('displayHeader');
			$this->unregisterHook('displayRightColumn');
			$this->unregisterHook('displayLeftColumn');
			$this->unregisterHook('displayBackOfficeHeader');
			$this->unregisterHook('displayOrderConfirmation');
			//$this->unregisterHook('actionOrderStatusUpdate');
		
		}


		if(!parent::uninstall() || !$this->uninstallDatabase()){
			$this->errors = $this->l('La table n\'a pas pu être supprimée');
			return false;
		}

		return true;
   	}


	 /**
	 * Créer la table av_products_reviews
	 * @return boolean if succeed
	 */

	public function installDatabase()
	{

		
		$query[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'av_products_reviews;';
		$query[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'av_products_average;';
	
		$query[] = 'ALTER TABLE '._DB_PREFIX_.'order_history ADD av_horodate_get varchar(30) DEFAULT NULL;';
		$query[] = 'ALTER TABLE '._DB_PREFIX_.'order_history ADD av_flag BOOLEAN DEFAULT 0;';

		$query[] = '
					CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'av_products_reviews (
					  `id_product_av` varchar(36) NOT NULL,
					  `ref_product` varchar(20) NOT NULL,
					  `rate` varchar(5) NOT NULL,
					  `review` text NOT NULL,
					  `customer_name` varchar(30) NOT NULL,
					  `horodate` text NOT NULL,
					  `discussion` text,
					  `lang` varchar(5),
					  PRIMARY KEY (`id_product_av`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

		$query[] = '
					CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'av_products_average` (
					  `id_product_av` varchar(36) NOT NULL,
					  `ref_product` varchar(20) NOT NULL,
					  `rate` varchar(5) NOT NULL,
					  `nb_reviews` int(10) NOT NULL,
					  `horodate_update` text NOT NULL,
					  `id_lang` varchar(5) DEFAULT NULL,
					  PRIMARY KEY (`ref_product`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;';


		$query[] = ' UPDATE `'._DB_PREFIX_.'order_history` SET av_flag = 1;';



		foreach ($query as $k => $sql){
			if(!Db::getInstance()->Execute($sql)) {
				$this->errors = $this->l('SQL ERROR : $sql | La table n\'a pas été créé');
				return false;
			}
		}

		return true;
	}

  
  	/**
	 * Supprimer la table av_products_reviews
	 * Supprimer le champs avisverifies_horodate
	 * @return boolean if succeed
	 */

	public function uninstallDatabase()
	{

		$query = array();
		
		$query[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'av_products_reviews';	
		$query[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'av_products_average';	
		$query[] = 'ALTER TABLE '._DB_PREFIX_.'order_history DROP av_horodate_get';
		$query[] = 'ALTER TABLE '._DB_PREFIX_.'order_history DROP av_flag';		

		foreach ($query as $key => $sql){
			if(!Db::getInstance()->Execute($sql)){
				$this->errors = $this->l('SQL ERROR : $sql > La table n\'existe pas.');
				return false;
			}
		}
	
		return true;
	}

	
}

	