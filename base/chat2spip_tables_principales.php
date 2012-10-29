<?php
if (!defined("_ECRIRE_INC_VERSION")) return;


function chat2spip_declarer_tables_principales($tables_principales){

	$spip_chat_salons = array(
		"id_salon" 	=> "int NOT NULL",
		"nom" 			=> "varchar(255) NOT NULL",
		"statut" 	=> "tinyint(4) NOT NULL",		
		"numofuser" 	=> " int(10) NOT NULL",
		"fichier" 		=> "varchar(100) NOT NULL",	
		  );

	$spip_chat_salons_key = array(
		"PRIMARY KEY" 	=> "id_salon",
		);
		
	$spip_chat_salons_join = array(
		"id_salon"	=> "id_salon",
		);

	$tables_principales['spip_chat_salons'] = array(
		'field' => &$spip_chat_salons,
		'key' => &$spip_chat_salons_key,
		'join' => &$spip_chat_salons_join
	);

	$spip_chat_utilisateurs = array(
		"id_utilisateur" => "int NOT NULL",
		"id_auteur" => "int NOT NULL",		
		"login" 		=> "varchar(100) NOT NULL",
		"statut" 	=> " tinyint(1) NOT NULL",
		"salons" 	=> " text NOT NULL",		
		"amies" 	=> " text NOT NULL",
		"bloques" 	=> " text NOT NULL",
		"vue" 	=> "varchar(10) NOT NULL",		
		"avis_chat" 	=> "varchar(255) NOT NULL",						
		"time_mod" 		=> "int(100) NOT NULL",	
		  );

	$spip_chat_utilisateurs_key = array(
		"PRIMARY KEY" 	=> "id_utilisateur",
		"KEY id_auteur"	=> "id_auteur",
		"KEY login"	=> "login",		
		);
		
	$spip_chat_utilisateurs_join = array(
		"id_auteur"	=> "id_auteur",
		);

	$tables_principales['spip_chat_utilisateurs'] = array(
		'field' => &$spip_chat_utilisateurs,
		'key' => &$spip_chat_utilisateurs_key,
		'join' => &$spip_chat_utilisateurs_join
	);

	$spip_chat_utilisateurs_salons = array(
		"id" => "int(100) NOT NULL",
		"login" 		=> "varchar(100) NOT NULL",
		"salon" 	=> "varchar(100) NOT NULL",
		"mod_time" 		=> "int(40) NOT NULL",	
		  );

	$spip_chat_utilisateurs_salons_key = array(
		"PRIMARY KEY" 	=> "id",	
		);
		
	$spip_chat_utilisateurs_salons_join = array(
		"id_auteur"	=> "id_auteur",
		);

	$tables_principales['spip_chat_utilisateurs_salons'] = array(
		'field' => &$spip_chat_utilisateurs_salons,
		'key' => &$spip_chat_utilisateurs_salons_key,
		'join' => &$spip_chat_utilisateurs_salons_join
	);

	$spip_chat_messages = array(
		"id_message" 	=> "int NOT NULL",
		"login" 			=> "varchar(100) NOT NULL",
		"fichier" 		=> "varchar(100) NOT NULL",	
		"date" 	=> "datetime NOT NULL",		
		"texte" 	=> "mediumtext NOT NULL",
		  );

	$spip_chat_messages_key = array(
		"PRIMARY KEY" 	=> "id_message",
		);
		
	$spip_chat_messages_join = array(
		"id_message"	=> "id_message",
		);

	$tables_principales['spip_chat_messages'] = array(
		'field' => &$spip_chat_messages,
		'key' => &$spip_chat_messages_key,
		'join' => &$spip_chat_messages_join
	);		
	return $tables_principales;
	
	}
?>
