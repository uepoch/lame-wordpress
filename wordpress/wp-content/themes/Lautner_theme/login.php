<?php
require __DIR__ ."./functions.php";

if (check(array('nom','password'))) {

	$mysqli = bdd();
	$result = $mysqli -> query("SELECT * 
					  			FROM `user`
					  			WHERE `nom` = '".$_POST['nom']."' AND `password` = '".$_POST['password']."'");

	if ($mysqli->affected_rows == 1) {
		$_SESSION['user'] = $result -> fetch_assoc();
	header("Location: /page-valid.php");
	die();
	}
}

header("Location: /lautner_theme");