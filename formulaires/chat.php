<?php



function formulaires_chat_charger_dist($id_salon,$file){
	include_spip('inc/session');
	//On charge les définitions
	
	// Si l'id projet est connu, on charge les données


	$valeurs=array('text'=>'','name'=>'','login'=>'');
	$valeurs['_hidden'] .='<input type="hidden" name="login" value="'.session_get('login').'"/>';
	

return $valeurs;
}

function formulaires_chat_verifier_dist(){

    return $erreurs;
}

function formulaires_chat_traiter_dist($id_salon,$file){

    	 	$chemin='img_pack/smiles/';

    	 	
		     $nickname = _request('login');
		    	$message = htmlentities(strip_tags(_request('text')), ENT_QUOTES);
		     $patterns = array("/:\)/", "/:D/", "/:p/", "/:P/", "/:\(/");
		 	$replacements = array(
		 		'<img src="'.find_in_path($chemin.'smile.gif').'"/>', 
		 		'<img src="'.find_in_path($chemin.'bigsmile.png').'"/>', 
		 		'<img src="'.find_in_path($chemin.'tongue.png').'"/>', 
		 		'<img src="'.find_in_path($chemin.'tongue.png').'"/>', 
		 		'<img src="'.find_in_path($chemin.'sad.png').'"/>'
		 		);
			 $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
			 $blankexp = "/^\n/";
			 
			 
    		 if (!preg_match($blankexp, $message)) {
            	
    			 if (preg_match($reg_exUrl, $message, $url)) {
           			$message = preg_replace($reg_exUrl, '<a href="'.$url[0].'" target="_blank">'.$url[0].'</a>', $message);
    			 } 
    			 $message = preg_replace($patterns, $replacements, $message);
            	
            	 fwrite(fopen($file, 'a'), "<span>". $nickname . "</span>" . $message = str_replace("\n", " ", $message) . "\n"); 
    		 }


return;
    
}
	 
?>