 <?php

if (!empty($_POST)) {
	$userdata = array(
	    'user_login'  =>  $_POST['identifiant'],
	    'user_pass'   =>  $_POST['password'],
	    'first_name'    =>  $_POST['prenom'],
	    'last_name'   =>  $_POST['nom'],
	    'telParent'   =>  $_POST['tel'],
	    'user_email'    =>  $_POST['email'],
	    'classe'    =>  $_POST['niv'],
	    'role'   =>  'wait',
	);
$user_id = wp_insert_user( $userdata ) ;

//On success
if ( ! is_wp_error( $user_id ) ) {
    echo "User created : ". $user_id;
}

}

get_header();
?>

<div class="container-contact">

<!-- <?php echo $messerreur; ?> -->

<div><p>Ceci est une pré-inscription pour votre enfant.</br>Lorsque celle-ci sera validée, vous pourrez accèder à l'ENT.</br>Attention, tous les champs sont obligatoires.</p></div>	
<form action="" method="post">

	 <div class="containform">
		<div class="centerrightform">Identifiant de connexion :</div> <input class="champtext" type="text" name="identifiant" placeholder="NomPrenom"><br>
	</div>

	<div class="containform">
		<div class="centerrightform">Mot de passe :</div> <input class="champtext" type="password" name="password" placeholder="..."><br>
	</div>
	
	<div class="containform">
		<div class="centerrightform">Nom :</div> <input class="champtext" type="text" name="nom" placeholder="Mickey"><br>
	</div>

	<div class="containform">
		<div class="centerrightform">Prénom : </div><input class="champtext" type="text" name="prenom" placeholder="Mouse"><br>
	</div>

	<div class="containform">
		<div class="centerrightform">Téléphone :</div> <input class="champtext" type="int" name="tel" placeholder="0102030405"><br>
	</div>

	<div class="containform">
		<div class="centerrightform">Email :</div> <input class="champtext" type="text" name="email" placeholder="...@gmail.com"><br>
	</div>
	

	<div class="containform">
		<div class="centerrightform">Niveau de classe :</div>
		<ul class="radioul">
			<li><input class="radiocheck" type="radio" name="niv" value="CP" checked> CP<br></li>
		  	<li><input class="radiocheck" type="radio" name="niv" value="CE1"> CE1<br></li>
			<li><input class="radiocheck" type="radio" name="niv" value="CE2"> CE2<br></li>
			<li><input class="radiocheck" type="radio" name="niv" value="CM1"> CM1<br></li>
			<li><input class="radiocheck" type="radio" name="niv" value="CM2"> CM2<br></li>
		</ul>

	</div>


	<input class="validation" type="submit" value="VALIDER">


</form> 
	
</div>

<?php
get_footer();
