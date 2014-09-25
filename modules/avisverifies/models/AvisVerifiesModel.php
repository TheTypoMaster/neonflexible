<?php

class AvisVerifiesModel extends ObjectModel{

	protected $table = 'av_products_reviews';		
	protected $identifier = 'id_product_av';

	public $reviews_by_page;

	function __construct(){

		$this->reviews_by_page = 10; 
		//Attention, le frontcontroller pagination utilisé dans le fichier principal au niveau du producttabcontent impose un nombre de 10 pour la pagination (correspondant au nb de produit/page)
		//En changeant ce nombre, les pages dans la pagination seront fausses


	}
	

	public function getProductReviews($id_product, $count_reviews = false, $p = 1) {

		global $cookie;

		/*if(isset($cookie) && !empty($cookie->id_lang)){
			$id_lang = $cookie->id_lang;
		}else{
			$id_lang = Configuration::get('PS_LANG_DEFAULT');
		}*/

		$p = (int)($p);
		$n = $this->reviews_by_page;

        if ($p <= 1) $p = 1;
        if ($n != null AND $n <= 0) $n = 10;

       	
		if($count_reviews) {
			return Db::getInstance()->getRow('SELECT COUNT(ref_product) as nbreviews FROM '._DB_PREFIX_.'av_products_reviews WHERE ref_product = '.$id_product);
		}
		else {
			
			return Db::getInstance()->ExecuteS('	SELECT * FROM '._DB_PREFIX_.'av_products_reviews 
													WHERE ref_product = '.$id_product.' ORDER BY horodate DESC
													'.($n ? 'LIMIT '.(int)(($p - 1) * $n).', '.(int)($n) : ''));
		}
		


		


	}

	
	public function getStatsProduct($id_product){

		return Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'av_products_average WHERE ref_product = '.$id_product);

	}

	public function Export($id_shop = null, $header_colums) {

		$this->duree = Tools::getValue('duree');


		if(isset($id_shop) && ! empty($id_shop)){

			$fileName = Configuration::get('AVISVERIFIES_CSVFILENAME', null, null, $id_shop) ;
			$delay = (Configuration::get('AVISVERIFIES_DELAY', null, null, $id_shop)) ? Configuration::get('AVISVERIFIES_DELAY', null, null, $id_shop) : 0;

		}
		else{

			$fileName = Configuration::get('AVISVERIFIES_CSVFILENAME') ;
			$delay = (Configuration::get('AVISVERIFIES_DELAY', null, null, $id_shop)) ? Configuration::get('AVISVERIFIES_DELAY') : 0;
		}

		

		$avisProduit = Tools::getValue('productreviews');
				
		if(!empty($fileName)){

			$filePath = _PS_MODULE_DIR_."avisverifies/".$fileName; 			

			if(@file_exists($filePath)) {					
				@unlink($filePath);				
			}
		}

		$fileName = 'Export_AV_'.date('d-m-Y').'-'.substr(md5(rand(0,10000)),1,3).'.csv';
		$filePath = _PS_MODULE_DIR_.'avisverifies/'.$fileName;


		$dureeSql="";
		switch($this->duree) {
			case "1w":
				$dureeSql="INTERVAL 1 WEEK";

				break;
			case "2w":
				$dureeSql="INTERVAL 2 WEEK";
				break;
			case "1m":
				$dureeSql="INTERVAL 1 MONTH";
				break;
			case "2m":
				$dureeSql="INTERVAL 2 MONTH";
				break;
			case "3m":
				$dureeSql="INTERVAL 3 MONTH";
				break;
			case "4m":
				$dureeSql="INTERVAL 4 MONTH";
				break;
			case "5m":
				$dureeSql="INTERVAL 5 MONTH";
				break;
			case "6m":
				$dureeSql="INTERVAL 6 MONTH";
				break;
			case "7m":
				$dureeSql="INTERVAL 7 MONTH";
				break;
			case "8m":
				$dureeSql="INTERVAL 8 MONTH";
				break;
			case "9m":
				$dureeSql="INTERVAL 9 MONTH";
				break;
			case "10m":
				$dureeSql="INTERVAL 10 MONTH";
				break;
			case "11m":
				$dureeSql="INTERVAL 11 MONTH";
				break;
			case "12m":
				$dureeSql="INTERVAL 12 MONTH";
				break;			
			default:
				$dureeSql="INTERVAL 1 WEEK";
				break;

		}

		$GENERAL_CLIENT = array();
		$all_orders = array();
		
		// Récupération des commandes en fonction de l'interval choisi
		$where_id_shop = (isset($id_shop) && ! empty($id_shop)) ?  'AND o.id_shop = '.$id_shop  : '';
		$select_id_shop = (isset($id_shop) && ! empty($id_shop)) ?  ', o.id_shop' : '';

		$qrySql = "		SELECT o.id_order, o.id_customer, o.invoice_date, o.date_add, c.firstname, c.lastname, c.email ".$select_id_shop."				
						FROM "._DB_PREFIX_."orders o
						LEFT JOIN "._DB_PREFIX_."customer c ON o.id_customer = c.id_customer
						WHERE o.invoice_date IS NOT NULL and (TO_DAYS(DATE_ADD(o.invoice_date,".$dureeSql.")) - TO_DAYS(NOW())) >= 0
						".$where_id_shop;					

		$itemList = Db::getInstance()->ExecuteS($qrySql);	

		foreach ($itemList as $item) {

			$all_orders[ $item['id_order'] ] = array(
				"ID_ORDER"=>$item['id_order'],
				"DATE_ORDER"=>date('d/m/Y',strtotime($item['date_add'])),
				"ID_CUSTOMER"=>array(
								"ID_CUSTOMER"=>$item['id_customer'],
								"FIRST_NAME"=>$item['firstname'],
								"LAST_NAME"=>$item['lastname'],
								"EMAIL"=>$item['email']),
				"EMAIL_CLIENT"=>"",
				"NOM_CLIENT"=>"",
				"ORDER_STATE"=>"",
				"PRODUCTS"=>array()
			);
			
			$qrySql = "SELECT id_order, product_id, product_name FROM "._DB_PREFIX_."order_detail WHERE id_order = ".$item['id_order'];
			$productList = Db::getInstance()->ExecuteS($qrySql);	
			foreach ($productList as $product) {
				$all_orders[ $product['id_order'] ]["PRODUCTS"][]=array(
					"ID_PRODUIT"=>$product['product_id'],
					"NOM_PRODUIT"=>$product['product_name']
				);

			}						

		}


		if(count($all_orders) > 0) {

			$csv = fopen($filePath, 'w');	

			//$header_colums = (isset($id_shop) && ! empty($id_shop)) ? "refcommande;email;nom;prenom;datecommande;delaiavis;refproduit;categorie;description;urlficheproduit;id_order_state;id_shop\r\n" : "refcommande;email;nom;prenom;datecommande;delaiavis;refproduit;categorie;description;urlficheproduit;id_order_state\r\n";

			fwrite($csv, $header_colums);
			
			foreach ($all_orders as $order) {

				if($avisProduit == 1 && count($order["PRODUCTS"]) > 0) {

					for($i=0; $i<count($order["PRODUCTS"]); $i++) {

						$line='';//reset the line
						$line[] = $order['ID_ORDER'];
						$line[] = $order['ID_CUSTOMER']["EMAIL"];
						$line[] = utf8_decode($order['ID_CUSTOMER']["LAST_NAME"]);
						$line[] = utf8_decode($order['ID_CUSTOMER']["FIRST_NAME"]);
						$line[] = $order['DATE_ORDER'];
						$line[] = $delay;
						$line[] = $order["PRODUCTS"][$i]["ID_PRODUIT"];
						$line[] = ''; // Categorie du produit
						$line[] = utf8_decode($order["PRODUCTS"][$i]["NOM_PRODUIT"]);
						$line[] = ''; //Url fiche produit
						$line[] = $order['ORDER_STATE']; //Etat de la commande
						if(isset($id_shop) && ! empty($id_shop)) $line[] = $id_shop;
						fwrite($csv, self::generateCsvLine($line));

						

					}

				} else {
					$line='';//reset the line
					$line[] = $order['ID_ORDER'];
					$line[] = $order['ID_CUSTOMER']["EMAIL"];
					$line[] = utf8_decode($order['ID_CUSTOMER']["LAST_NAME"]);
					$line[] = utf8_decode($order['ID_CUSTOMER']["FIRST_NAME"]);					
					$line[] = $order['DATE_ORDER'];
					$line[] = $delay;
					$line[] = '';
					$line[] = ''; // Categorie du produit
					$line[] = '';
					$line[] = '';//Url fiche produit
					$line[] = $order['ORDER_STATE']; //Etat de la commande
					if(isset($id_shop) && ! empty($id_shop)) $line[] = $id_shop;
					fwrite($csv, self::generateCsvLine($line));
					
				}

				
			}
			fclose($csv);
			Configuration::updateValue('AVISVERIFIES_CSVFILENAME',$fileName);

			return array($fileName,count($all_orders),$filePath);
		}
		else {

			throw new Exception('Aucune commande à exporter');
		}
		/*

		if (@file_exists($filePath))
        {	

                
            header('Content-type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            // cache
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            Configuration::updateValue('AVISVERIFIES_CSVFILENAME',$fileName);
			echo file_get_contents($filePath);
			
			
			if(_PS_VERSION_ > 1.5){
				Tools::redirect('modules/avisverifies/'.$fileName);
			}                

        }

        else
        {            
            throw new Exception('Le fichier CSV n\'a pas pu être créé');
        }
        */
	}

	private static function generateCsvLine($list) {

		foreach($list as &$l){
			$l = '' . addslashes($l) . '';
		}
		return implode(";", $list)."\r\n";
	}

	static public function AC_encode_base64($sData){ 
    		$sBase64 = base64_encode($sData); 
    		return strtr($sBase64, '+/', '-_'); 
 	} 
   	

   	static public function AC_decode_base64($sData){ 
    		$sBase64 = strtr($sData, '-_', '+/'); 
    		return base64_decode($sBase64); 
   	}


}
