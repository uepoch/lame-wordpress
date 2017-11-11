<?php
    if(!current_user_can('professeur') ) {
        wp_redirect(get_bloginfo('url').'/accueil/');
    }

    if (!empty($_FILES["file"])) {
		$name = basename($_FILES["file"]["name"]);
		$uploadDir = "/tmp/controle";
		$path = "$uploadDir/$name";
	    move_uploaded_file($_FILES["file"]['tmp_name'], $path);

	    echo "File uploaded: $path\n";

	    $wpdb->show_errors();

		$wpdb->query( 
			$wpdb->prepare("
				INSERT INTO wp_controle (
					user_id,
					numcontrole,
					matiere,
					classe,
					path
				) VALUES (
					%d, %s, %s, %s, %s
				)
			",	get_current_user_id(), $_POST['numcontrole'], $_POST['matiere'], $_POST['niv'], $path
		));

		$wpdb->print_error();
		die;
	}





get_header('entProfesseur');
?>

	<div class="container">
	
		<form action="" method="post" enctype="multipart/form-data">

			<div>Numéro de la correction du contrôle :
				<input class="champtext" type="text" name="numcontrole" placeholder="Numero uniquement, exemple : 001">
			</div>

			 <div>
				<div>Matière :
					<select name="matiere" >
					  <option value="math">Mathématiques</option>
					  <option value="fran">Français</option>
					  <option value="histgeo">Histoire/Géographie</option>
					  <option value="angl">Anglais</option>
					</select>
				</div>
			</div>


			 <div>
				<div>Classe :
					<select name="niv" >
					  <option value="CP">CP</option>
					  <option value="CE1">CE1</option>
					  <option value="CE2">CE2</option>
					  <option value="CM1">CM1</option>
					  <option value="CM2">CM2</option>
					</select>
				</div>
			</div>


			<div>
				<div>Fichier :</div> <input class="" type="file" name="file" placeholder=""><br>
			</div>

			<input class="validation" type="submit" value="VALIDER">

		</form>

	</div>
