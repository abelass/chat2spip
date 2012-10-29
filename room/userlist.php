<?php
/* 
Author: Kenrick Beckett
Author URL: http://kenrickbeckett.com
Name: Chat Engine 2.0

*/
require_once("../dbcon.php");

//Start Array
$data = array();
// Get data to work with
		$current = cleanInput($_GET['current']);
		$room = cleanInput($_GET['room']);
		$username = cleanInput($_GET['username']);
		$id_auteur = cleanInput($_GET['id_auteur']);		
		$now = time();
// INSERT your data (if is not already there)
       	$findUser = "SELECT * FROM `chat_utilisateurs_salons` WHERE `login` = '$username' AND `salon` ='$room' ";
		
		if(!$findUser)
				{
					$insertUser = "INSERT INTO `chat_utilisateurs_salons` (`id`, `login`, `salon`, `mod_time`) VALUES ( NULL , '$username', '$room', '$now')";
					mysql_query($insertUser) or die(mysql_error());
				}		
		 	$findUser2 = "SELECT * FROM `chat_utilisateurs` WHERE `login` = '$username'";
			if(!$findUser2)
				{
					$insertUser2 = "INSERT INTO `chat_utilisateurs` (`id` ,`id_auteur`,`username` , `status` ,`time_mod`)
					VALUES ('' ,'$id_auteur', '$username', '1', '$now')";
					mysql_query($insertUser2);
					$data['check'] = 'true';
				}			
		$finish = time() + 7;
		$getRoomUsers = mysql_query("SELECT * FROM `chat_utilisateurs_salons` WHERE `salon` = '$room'");
		$check = mysql_num_rows($getRoomUsers);
        	;
	    while(true)
		{
			usleep(10000);
			mysql_query("UPDATE `chat_utilisateurs` SET `time_mod` = '$now' WHERE `login` = '$username'");
			$olduser = time() - 5;
			$eraseuser = time() - 30;
			mysql_query("DELETE FROM `chat_utilisateurs_salons` WHERE `mod_time` <  '$olduser'");
			mysql_query("DELETE FROM `chat_utilisateurs_salons` WHERE `time_mod` <  '$eraseuser'");
			$check = mysql_num_rows(mysql_query("SELECT * FROM `chat_utilisateurs_salons` WHERE `salon` = '$room' "));
			$now = time();
			if($now <= $finish)
			{
				mysql_query("UPDATE `chat_utilisateurs_salons` SET `mod_time` = '$now' WHERE `login` = '$username' AND `salon` ='$room'  LIMIT 1") ;
				if($check != $current){
				 break;
				}
			}
			else
			{
				 break;	
		    }
        }		 		
// Get People in chat
		if(mysql_num_rows($getRoomUsers) != $current)
		{
			$data['numOfUsers'] = mysql_num_rows($getRoomUsers);
			// Get the user list (Finally!!!)
			$data['userlist'] = array();
			while($user = mysql_fetch_array($getRoomUsers))
			{
				$data['userlist'][] = $user['login'];
			}
			$data['userlist'] = array_reverse($data['userlist']);
		}
		else
		{
			$data['numOfUsers'] = $current;	
			while($user = mysql_fetch_array($getRoomUsers))
			{
				$data['userlist'][] = $user['login'];
			}
			$data['userlist'] = array_reverse($data['userlist']);
		}
		echo json_encode($data);

?>