<?php

// Sécurité
if (!defined('_ECRIRE_INC_VERSION')) return;


function action_utilisateurs_dist($arg=null) {
	if (is_null($arg)){
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}
	
	// On récupère les infos de l'argument
	@list($action,$id_utilisateur,$option) = explode('|', $arg);


	include_spip('inc/cookie');
	include_spip('inc/session');
	
	$nom_cookie = $GLOBALS['cookie_prefix'].'_chat2spip';
	
	$login_session=session_get('login');
	

	$session=donnes_session('',$nom_cookie);
	

	spip_log('n'.serialize($session),'teste');

	if($session['amies'])$session['amies']=unserialize($session['amies']);
	if($session['bloques'])$session['bloques']=unserialize($session['bloques']);	
	

	
	switch($action){
		case 'devenir_amie':
		
			$amies_nouveau=array(0=>$id_utilisateur);

			if($session['amies']){
			$session['amies']=array_merge($amies_nouveau,$session['amies']);
			}
			else {
			$session['amies'] =$amies_nouveau;

			}
			
			if($session['bloques']){
				foreach($session['bloques'] AS $key=>$bloque){
				if($id_utilisateur == $bloque)unset($session['bloques'] [$key]);
				}
			}
			
		break;
		
		case 'enlever_amie':
			if($session['amies']){
				foreach($session['amies'] AS $key=>$amie){
					if($id_utilisateur == $amie)unset($session['amies'][$key]);
				}
			}
			
		break;
		
		case 'bloquer_utilisateur':
		
			$bloque_nouveau=array(0=>$id_utilisateur);
			
			if(is_array($session['bloques']))$session['bloques']=array_merge($bloque_nouveau,$session['bloques']);
			else $session['bloques'] =$bloque_nouveau;
			
			if(is_array($session['amies'])){
				foreach($session['amies'] AS $key=>$amie){
					if($id_utilisateur == $amie)unset($session['amies'][$key]);
				}
			}
		
			if($session['amies'])$session['amies']=serialize($session['amies']);

	
		break;		
		
		case 'debloquer_utilisateur':
		
			if(is_array($session['bloques'])){
				foreach($session['bloques'] AS $key=>$bloque){
				if($id_utilisateur == $bloque)unset($session['bloques'][$key]);
				}
			}
	
		break;

	}

				
	//$test=spip_setcookie($nom_cookie, serialize($session));	
	
	
	
	if(is_array($session['amies']))$amies=$session['amies'];
	if(is_array($session['bloques']))$bloques=$session['bloques'];
	
	sql_updateq('spip_chat_utilisateurs',array('amies'=>serialize($amies),'bloques'=>serialize($bloques)),'login='.sql_quote($login_session));	
	return;
}
	

function donnes_session($id_utilisateur='',$nom_cookie){


	if(!$id_utilisateur){
		include_spip('inc/session');

			$session=sql_fetsel('salons,amies,bloques,statut','spip_chat_utilisateurs','login='.sql_quote(session_get('login')));
			
		//$utilisateur=unserialize($utilisateur);
		}
	else {
		$session=sql_fetsel('salons,amies,bloques,statut','spip_chat_utilisateurs','id_utilisateur='.$id_utilisateur);
		}
			spip_log('f'.serialize($session),'teste');
	return $session;
	
	}