<?php



class Product extends ProductCore {

	private static $is_active_module;

	public static function getProductsProperties($id_lang, $query_result)
	{		

		if(empty(Product::$is_active_module)){

			if(_PS_VERSION_  < 1.5 ){
				Product::$is_active_module = (Db::getInstance()->getValue("SELECT id_module FROM "._DB_PREFIX_."module WHERE name = 'avisverifies' AND active = 1")) ? 2 : 1;				
			}
			else{
				Product::$is_active_module = (Db::getInstance()->getValue("SELECT m.id_module FROM "._DB_PREFIX_."module m LEFT JOIN "._DB_PREFIX_."module_shop ms ON m.id_module = ms.id_module WHERE m.name = 'avisverifies' AND m.active = 1 AND ms.id_shop = ".(int)Context::getContext()->shop->id)) ? 2 : 1;
			}

		}
		
	
		$resultsArray = array();

		foreach ($query_result AS $row) {


			if ($row2 = Product::getProductProperties($id_lang, $row)){

				if(Module::isInstalled('avisverifies') && Product::$is_active_module == 2){

					$reviews = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'av_products_average WHERE ref_product = '.$row2['id_product']);	
						
					$row2['av_rate'] = round($reviews["rate"],0);				
					$row2['av_nb_reviews'] = $reviews["nb_reviews"];				
									

				}

				
				$resultsArray[] = $row2;
				
			}


		}
		return $resultsArray;

	}



}

?>