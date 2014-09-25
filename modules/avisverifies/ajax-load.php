<?php

require(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/models/AvisVerifiesModel.php'); 

/*
# Fichier de traitement Ajax destiné à la pagination
# Ce fichier reprend la structure du code du hook productabcontent en utilisant un template destiné uniquement à recevoir les données en AJAX
#
*/



global $cookie, $smarty;

$shop_name = Configuration::get('PS_SHOP_NAME');

$id_product = abs((int)(Tools::getValue('id_product')));


$o_av = new AvisVerifiesModel();

$nbComments = (int)(Tools::getValue('count_reviews'));


// ### Gestion de la pagination - Calqué sur la méthode pagination de Frontcontroller  ###


$p = abs((int)(Tools::getValue('p', 1))); //Page à afficher

$range = 2; //Nombre de pages à afficher autour de la page en cours dans la navigation de la pagination

if ($p > (($nbComments / $o_av->reviews_by_page) + 1))
    Tools::redirect(preg_replace('/[&?]p=\d+/', '', $_SERVER['REQUEST_URI']));
$pages_nb = ceil($nbComments / (int)($o_av->reviews_by_page));
$start = (int)($p - $range);
if ($start < 1)
    $start = 1;
$stop = (int)($p + $range);
if ($stop > $pages_nb)
    $stop = (int)($pages_nb);

// ### FIN Gestion de la pagination ###

//$first_review = ($p - 1) * $reviews_by_page; 

$reviews = $o_av->getProductReviews($id_product, false, $p);

$reviews_list = array();

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

$smarty->assign(array(              
    'current_url' =>  $_SERVER['REQUEST_URI'],
    'reviews' => $reviews_list,
    'p' => (int)$p,
    'n' => $o_av->reviews_by_page,
    'pages_nb' => $pages_nb,
    'start' => $start,
    'stop' => $stop,
));

$rendered_content = $smarty->fetch(_PS_ROOT_DIR_.'/modules/avisverifies/views/templates/ajax-load-tab-content.tpl');
echo $rendered_content;

?>