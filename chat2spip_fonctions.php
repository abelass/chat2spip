<?php

function definitions($type=''){
	$defs=array(
		'chemin'=>'IMG/chatrooms/'
		);		
	if($type)$defs=$defs[$type];
	
	return $defs;
}
     
function get_file($id='',$type='nom'){

	if($id){
     
		$chemin=definitions('chemin');
		
		$where='nom='.sql_quote($id);
		if($type=='id_salon')$where='id_salon='.$id;
		
		$fichier = sql_getfetsel('fichier','spip_chat_salons',$where);
		
		if (!$fichier) {
		//header('Location:'.generer_url_public('chat','view=chatsalons'),true);
		}
		else $fichier=$chemin.$fichier;
    	 }
     
     return $fichier;
     }
     
function get_salons($id_auteur){
     $salons=array(1,2);
     
     
     return $salons;
     }
     
function session_chat($type=''){
	//include_spip('inc/cookie');
	include_spip('inc/session');
	/*$nom_cookie = $GLOBALS['cookie_prefix'].'_chat2spip';
	$utilisateur=$_COOKIE[$nom_cookie];*/
	sql_updateq('spip_chat_utilisateurs',array('statut'=>1),'login='.sql_quote(session_get('login')).' AND statut=0');
	
	/*if(!$utilisateur=$_COOKIE[$nom_cookie]) {
	$utilisateur=sql_fetsel('amies,bloques,statut','spip_chat_utilisateurs','login='.sql_quote(session_get('login')));
	
	if(!$utilisateur['salons'])$utilisateur['salons']=array(
						'salons_ouverts'=>array(
							1=>array('id_utilisateur'=>2,
									'id_salon'=>1
								),
							2=>array('id_utilisateur'=>1,
									'id_salon'=>2
								),
							
							),

						);	
						
										
	$utilisateur=spip_setcookie ($nom_cookie, serialize($utilisateur));
	
	}*/
	$utilisateur=unserialize($utilisateur);
	
	
	
	if($type=='salons'){
		if(is_array($utilisateur['salons']['salons_ouverts'])) $utilisateur=$utilisateur['salons']['salons_ouverts'];
		}
	if($type=='salon_actif')if(is_array($utilisateur['salons']['actif']))$utilisateur=$utilisateur['salons']['actif'][0];
	else	$utilisateur=$utilisateur['salons']['actif'];

	return $utilisateur;


}
     
function user_salon($utilisateur_salons='',$session_salons="",$id_utilisateur,$id_session){

//echo $id_session;
	$salons= sql_getfetsel('salons','spip_chat_utilisateurs','id_utilisateur='.$id_utilisateur);
	


	if($salons)$session_salons=unserialize($salons);	

	if(is_array($session_salons)){
		foreach($session_salons AS $key=>$valeur){
			if($valeur['id_utilisateur']==$id_session)$id_salon=$valeur['id_salon'];
			}
		if(!$id_salon){
			$salons= sql_getfetsel('salons','spip_chat_utilisateurs','id_utilisateur='.$id_utilisateur);

			if($salons)$session_salons=unserialize($salons);	
			if(is_array($utilisateur_salons)){
				foreach($utilisateur_salons AS $key=>$valeur){
					if($valeur['id_utilisateur']==$id_utilisateur)$id_salon=$valeur['id_salon'];
					}
				}
			}
		}

		

		if(!$id_salon)$id_salon='new';
		
		
		return $id_salon;

} 
    
function creer_salon($id_utilisateur,$id_session='',$login_session){

	$donnees_utilisateur=sql_fetsel('login,salons','spip_chat_utilisateurs','id_utilisateur='.$id_utilisateur);
	$donnees_session=sql_fetsel('login,id_utilisateur,salons','spip_chat_utilisateurs','login='.sql_quote($login_session));
	
	$array_noms=array($donnees_utilisateur['login'],$donnees_session['login']);
	


	sort($array_noms);
	
	//echo serialize($noms_tries);
	
	$nom_salon=$array_noms[0].'_'.$array_noms[1];
	$fichier=$nom_salon.'.txt';
	$chemin=definitions('chemin');
	$file=$chemin.$fichier;
	
	$FileHandle = fopen($file, 'w') or die("le fichier du chat ne peut pas être crée");
	fclose($FileHandle);
	
	$id_salon=sql_getfetsel('id_salon','spip_chat_salons','nom='.sql_quote($nom_salon));
	
	if(!$id_salon)$id_salon=sql_insertq('spip_chat_salons',array('nom'=>$nom_salon,'fichier'=>$fichier,'statut'=>1));
	
	$salons_utilisateur=array($id_salon=>array(
							'id_utilisateur'=>$donnees_session['id_utilisateur'],
							'id_salon'=>$id_salon,							
							)
						);

	$salons_session=array($id_salon=>array(
								'id_utilisateur'=>$id_utilisateur,
								'id_salon'=>$id_salon,										
								)
							);

	
	if($donnees_utilisateur['salons']){
		$donnees_utilisateur['salons'] = unserialize($donnees_utilisateur['salons']);
		$salons_utilisateur= array_merge($donnees_utilisateur['salons'],$salons_utilisateur);
		}
	if($donnees_session['salons']){
		$donnees_session['salons'] = unserialize($donnees_session['salons']);	
		$salons_session= array_merge($donnees_session['salons'],$salons_session);	
		}
	
	sql_updateq('spip_chat_utilisateurs',array('salons'=>serialize($salons_utilisateur)),'id_utilisateur='.$id_utilisateur);
	
	sql_updateq('spip_chat_utilisateurs',array('salons'=>serialize($salons_session)),'id_utilisateur='.$donnees_session['id_utilisateur']);	

	
	return $id_salon;
}

function jumpin($login){

    include_spip('inc/session');

    	$data = array();
    	$nom=_request('nom');
	 $id_auteur=session_get('id_auteur');
	 
    	
    	if ($login) {

    			 $now = time();

 
    			 $utilisateur=sql_getfetsel('id_utilisateur','spip_chat_utilisateurs','login='.sql_quote($login));
    			 
			 if(!$utilisateur){
					$utilisateur= sql_insertq('spip_chat_utilisateurs',array('id_auteur'=>$id_auteur,'statut'=>1,'time_mod'=>$now,'login'=>$login));

					}
			else sql_updateq('spip_chat_utilisateurs',array('statut'=>1),'statut=0 AND login='.sql_quote($login));
					
	
			session_set('utilisateur_chat',$utilisateur);
			$salons = sql_select('nom','spip_chat_salons');
			
			$nbr_salons=sql_count($salons);
			


			if($nbr_salons==1){
				$fichier = sql_getfetsel('fichier','spip_chat_salons');
					if (!$fichier) {
						//$url=generer_url_public('chat');
						}
					else{
						$nom=sql_fetch($salons);
						//$url=generer_url_public('chat','view=salon&nom='.$nom['nom'],true);
						}
				}
			else {$url=generer_url_public('chat','view=chatsalons',true);

				  }
				// header('Location:'.$url);
    		}
    		

		
    	}
    	
function liens_utilisateur($id_session,$id_user){	 
	
	
   		 include_spip('inc/session');
   		 include_spip('inc/filtres_images'); 
   		   		 
		$login_session =session_get('login');
		
		//if($user['salons'])$user_salons=unserialize($user['salons']);	
		
		//$id_session=session_get();
		
		
				
		if($id_user)$user=sql_fetsel('id_auteur,login,amies,bloques','spip_chat_utilisateurs','id_utilisateur='.$id_user.' AND login!=""');
		
		$findUser = sql_fetsel('amies,bloques,avis_chat','spip_chat_utilisateurs','login='.sql_quote($login_session));
		
		
		if($findUser['amies'])$session_amies=unserialize($findUser['amies']);
		
		if($findUser['bloques'])$session_bloques=unserialize($findUser['bloques']);
		
		if(is_array($session_amies)){
			if(in_array($id_user,$session_amies)) {
				$type='amie';
				}
			}				


		if(is_array($session_bloques)){	
		if(in_array($id_user,$session_bloques)) {
			$type='bloque';
			}
			}

		if($user['id_auteur'])$id_aut=$user['id_auteur'];
		else{
			$id_aut=sql_getfetsel('id_auteur','spip_auteurs','login='.sql_quote($user['login']));
			sql_updateq('spip_chat_utilisateurs',array('id_auteur'=>$id_aut),'id_utilisateur='.$id_user);
			}
		
		

		// echo '<div>id-user:'.$id_user.'</div>';
		
		$id_salon=user_salon('','',$id_user,$id_session);
		
		if($id_salon !='new')$file=get_file($id_salon,'id_salon');
		else $user_bloques=array();	
		
	
		//$salons=session_chat('salons');
	
		
		if(is_array($salons)){
			$salon_nouveau=array(0=>$id_salon);
			$salons=array_merge($salon_nouveau,$salons);
			$salons=implode('|',$salons);
		 }
		else $salons=$id_salon;
		
		$id=$file.'-'.$id_salon.'-'.$salons;
		
		if ($findUser['avis_chat']){
			$avis_chat=unserialize($findUser['avis_chat']);
			if(in_array($id_user,$avis_chat)){
				include_spip('inc/cookie');
				$Id_suppl.='-enlever_avis';		
				//$avis='<a  href="'.generer_action_auteur('chat_salon','ouvrir_salon|'.$id_salon.'|'.$id_user.'|enlever_avis',$url_retour).'" title="nouveau message"><img src="'.find_in_path('img_pack/bubble_16.png').'" alt="avis"/></a>';
				$avis= '<span class="ouvrir_salon" id="'.$id.'-'.$id_user.$Id_suppl.'-nouveau"><img src="'.find_in_path('img_pack/bubble_16.png').'" alt="avis" class="avis"/></span>';	
				spip_setcookie('alerter','ok');
				}
			}

		//$ouvrir_salon='<a href="'.generer_action_auteur('chat_salon','ouvrir_salon|'.$id_salon.'|'.$user['id_utilisateur'],$url_retour).'">'.$user['login'].'</a>';
		

		
		if($type){
			if($type!='amie') {
				//$amie='<a class="devenir_amie" href="'.generer_action_auteur('utilisateurs','devenir_amie|'.$id_user,$url_retour).'" title="ajouter comme amie"><img src="'.find_in_path('img_pack/heart_16.png').'" alt="amie"/></a>';
				$amie ='<span class="devenir_amie" id="'.$login_session.'-'.$id_user.'"><img src="'.find_in_path('img_pack/heart_16.png').'" alt="amie"/></span>';
				//$debloquer='<a class="debloquer_utilisateur" href="'.generer_action_auteur('utilisateurs','debloquer_utilisateur|'.$id_user,$url_retour).'" title="debloquer cet utilisateur"><img src="'.find_in_path('img_pack/user_16.png').'" alt="amie"/></a>';
				$debloquer ='<span class="debloquer_utilisateur" id="'.$login_session.'-'.$id_user.'"><img src="'.find_in_path('img_pack/user_16.png').'" alt="debloquer_utilisateur"/></span>';	
				$icone=	'<img class="icone"  src="'.find_in_path('img_pack/block_16.png').'" alt="bloqué"/>';		
				}
			elseif ($type='bloque'){
				/*$enlever_amie='<a class="enlever_amie" href="'.generer_action_auteur('utilisateurs','enlever_amie|'.$id_user,$url_retour).'" title="enlever comme amie"><img src="'.find_in_path('img_pack/user_16.png').'" alt="enlevetr amie"/></a>';*/
				$enlever_amie='<span class="enlever_amie" id="'.$login_session.'-'.$id_user.'"><img src="'.find_in_path('img_pack/user_16.png').'" alt="enlever amie"/></span>';				
				//$bloquer='<a class="bloquer_utilisateur" href="'.generer_action_auteur('utilisateurs','bloquer_utilisateur|'.$id_user,$url_retour).'" title="bloquer cet utilisateur"><img src="'.find_in_path('img_pack/block_16.png').'" alt="amie"/></a>';
				$bloquer='<span class="bloquer_utilisateur" id="'.$login_session.'-'.$id_user.'"><img src="'.find_in_path('img_pack/block_16.png').'" alt="bloquer cet utilisateur"/></span>';	
				$icone=	'<img class="icone"  src="'.find_in_path('img_pack/heart_16.png').'" alt="amie"/>';	
				}
			}				
		else {
			//$amie='<a class="devenir_amie" href="'.generer_action_auteur('utilisateurs','devenir_amie|'.$id_user,$url_retour).'" title="ajouter comme amie"><img src="'.find_in_path('img_pack/heart_16.png').'" alt="amie"/></a>';
			$amie ='<span class="devenir_amie" id="'.$login_session.'-'.$id_user.'"><img src="'.find_in_path('img_pack/heart_16.png').'" alt="amie"/></span>';
			//$bloquer='<a class="bloquer_utilisateur" href="'.generer_action_auteur('utilisateurs','bloquer_utilisateur|'.$id_user,$url_retour).'" title="bloquer cet utilisateur"><img src="'.find_in_path('img_pack/block_16.png').'" alt="amie"/></a>';	
			$bloquer='<span class="bloquer_utilisateur" id="'.$login_session.'-'.$id_user.'"><img src="'.find_in_path('img_pack/block_16.png').'" alt="bloquer cet utilisateur"/></span>';	
			$icone=	'<img class="icone" src="'.find_in_path('img_pack/user_16.png').'" alt="user"/>';		
		}
			


		
		$ouvrir_salon= '<span class="ouvrir_salon" id="'.$id.'-'.$id_user.$Id_suppl.'">'.$user['login'].'</span>';	
				
		$link = $enlever_amie.$amie.$bloquer.$debloquer;	
			
		$details	='<span class="logo">'.image_reduire(find_in_path(logo_participant($id_aut)),40,0).$ouvrir_salon.$avis.'</span><div class="detail_utilisateur">'.$icone.$link.'</div>';
	unset($id_user);
	unset($type);			
	return $details;
}	    	
	    	
	    	
function nom_utilisateur($login){

	$nom=sql_getfetsel('nom','spip_auteurs','login='.sql_quote(trim($login)));

	return $nom;
}	    	
	    	
?>
