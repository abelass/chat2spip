<?php

function chat2spip_declarer_tables_interfaces($tables_interfaces){

	$tables_interfaces['table_des_tables']['chat_salons'] = 'chat_salons';
	$tables_interfaces['table_des_tables']['chat_utilisateurs'] = 'chat_utilisateurs';
	$tables_interfaces['table_des_tables']['chat_utilisateurs_salon'] = 'chat_utilisateurs_salon';	
	$tables_interfaces['table_des_tables']['chat_messages'] = 'chat_messages';	
	return $tables_interfaces;
}


?>
