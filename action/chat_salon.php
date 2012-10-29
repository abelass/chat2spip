<?php

// Sécurité
if (!defined('_ECRIRE_INC_VERSION')) return;


function action_chat_salon_dist($arg=null) {
	if (is_null($arg)){
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}
	
	// On récupère les infos de l'argument
	@list($action, $id_salon,$id_utilisateur,$option) = explode('|', $args);


	//include_spip('inc/cookie');
	include_spip('inc/session');
	
	//$nom_cookie = $GLOBALS['cookie_prefix'].'_chat2spip';

	
	switch($action){
		case 'fermer':
		
			//$utilisateur=donnes_session($nom_cookie);
			$utilisateur=donnes_session();
			$utilisateur['salons']['actif']='';
		
			//$session_chat=spip_setcookie ($nom_cookie, serialize($utilisateur));
			
			//$donnes_tables=donnes_session($nom_cookie,session_get('utilisateur_chat'));
			$donnes_tables=donnes_session('',session_get('utilisateur_chat'));
			
			if($donnes_tables['salons'])$salons=unserialize($donnes_tables['salons']);
			else $salons=array();
			
			$salons['actif']='';

			sql_updateq('spip_chat_utilisateurs',array('salons'=>serialize($salons)),'id_utilisateur='.session_get('utilisateur_chat'));
	
		break;
		
		case 'ouvrir':
	
			//$utilisateur=donnes_session($nom_cookie);
			$utilisateur=donnes_session();
			$utilisateur['salons']['actif']=$id_salon;
		
			//$session_chat=spip_setcookie ($nom_cookie, serialize($utilisateur));
		break;
		
		case 'ouvrir_salon':
			include_spip('chat2spip_fonctions');
			$login_session=session_get('login');
			
			
			if($option =='enlever_avis'){
				$avis_chat=sql_getfetsel('avis_chat','spip_chat_utilisateurs','login='.sql_quote($login_session));
			if($avis_chat){
				$avis_chat=unserialize($avis_chat);
				$avis=array();
				
				foreach($avis_chat AS $key=>$var) 
					if(!in_array($id_utilisateur,$avis_chat))$avis[$key]=$var;
				}
				
				$avis=serialize($avis);

					
				
				sql_updateq('spip_chat_utilisateurs',array('avis_chat'=>$avis),'login='.sql_quote($login_session));
			}
		
			//$nom_cookie = $GLOBALS['cookie_prefix'].'_chat2spip';
			
			//if($cookie=$_COOKIE[$nom_cookie])$utilisateur_session=unserialize($cookie);
			
			
			if($id_salon=='new') $id_salon=creer_salon($id_utilisateur,'',$login_session);

			$ouvrir_salon=array(
						$id_utilisateur=>$id_salon
						);
						
			if(is_array($utilisateur_session['salons']['salons_ouverts'])){
				$utilisateur_session['salons']['salons_ouverts']=array_merge($utilisateur_session['salons']['salons_ouverts'],$ouvrir_salon);
				}
			else $utilisateur_session= array('salons'=>array('salons_ouverts'=>$ouvrir_salon));
			

			$utilisateur_session['salons']['actif']=$id_salon;
			
			spip_log($utilisateur_session,'teste');
			
			//$utilisateur_session= spip_setcookie ($nom_cookie, serialize($utilisateur_session));
			
			
			$salons=sql_getfetsel('salons','spip_chat_utilisateurs','login='.sql_quote($login_session));

			
			if($salons)$salons=unserialize($salons);
			else $salons=array();
			
			$salons['actif']=$id_salon;
			
			sql_updateq('spip_chat_utilisateurs',array('salons'=>serialize($salons)),'login='.sql_quote($login_session));
			
			
			
		break;		
		
		case 'eliminer':
			include_spip('inc/session');
	
			//$utilisateur=donnes_session($nom_cookie);
			$utilisateur=donnes_session();

			if(is_array($utilisateur['salons']['salons_ouverts'])){
				foreach($utilisateur['salons']['salons_ouverts'] AS $key=>$var){
					if($var= $id_salon)unset($utilisateur['salons']['salons_ouverts'][$key]);
					}
				}
				

			if($utilisateur['salons'])$salons=serialize($utilisateur['salons']);
			else $salons=array();
			
			$salons['actif']='';
			include_spip('inc/session');
			sql_updateq('spip_chat_utilisateurs',array('salons'=>serialize($salons)),'login='.sql_quote(session_get('login')));
			//$utilisateur['salons']['salons_ouverts']=$u;
			
			//$session_chat=spip_setcookie ($nom_cookie, serialize($utilisateur));

		break;		
		
	}
	return;

	
function donnes_session($nom_cookie='',$id_utilisateur=''){
	if(!$id_utilisateur){
		include_spip('inc/session');
		//if(!$utilisateur=$_COOKIE[$nom_cookie]) {
			$utilisateur=sql_fetsel('salons,amies,bloques,statut','spip_chat_utilisateurs','login='.sql_quote(session_get('login')));
		//	}
		$utilisateur=unserialize($utilisateur);
		}
	else $utilisateur=sql_fetsel('salons,amies,bloques,statut','spip_chat_utilisateurs','id_utilisateur='.$id_utilisateur);
		
	return $utilisateur;
	}
	
	
?>
