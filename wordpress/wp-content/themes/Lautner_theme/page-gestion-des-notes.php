<?php

    if(!current_user_can('professeur') ) {
        wp_redirect(get_bloginfo('url').'/accueil/');
    }


 /*   $result = mysql_query("SELECT user_nicename FROM wp_users");
/*
	if (!$result) {
	    echo 'Could not run query: ' . mysql_error();
	    exit;
	}
	$row = mysql_fetch_row($result);

	echo $row[0]; // 42
	echo $row[1]; // the email value


 /*   $nomEleve = "SELECT user_nicename FROM wp_users WHERE classe = 'niv'";
 	$bdd = mysql_connect('localhost', 'root', 'root');	
    mysql_select_db('wordpress');
    $result = mysql_query($nomEleve);
   

 /*   foreach($result as $user){
    	echo $user['user_nicename'];
    }
*/




get_header('entProfesseur');
?>

	<div class="container">
	
		<form action="" method="post" enctype="multipart/form-data">

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
				<div>Eleve :
					<select name="eleve" >
					  <option value="CP">CP</option>
					  <option value="CE1">CE1</option>
					  <option value="CE2">CE2</option>
					  <option value="CM1">CM1</option>
					  <option value="CM2">CM2</option>
					</select>
				</div>
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


			<div>Numéro du contrôle associé :
				<input class="champtext" type="text" name="numcontrole" placeholder="Numero uniquement, exemple : 001">
			</div>


			<div>Note :
				<input class="champtext" type="text" name="note" placeholder="...">
			</div>


			<input class="validation" type="submit" value="VALIDER">

		</form>

	</div>



<table border="1">
<tr>
 <th>ID</th>
 <th>Nom</th>
 <th>Classe</th>
</tr>

  <?php
	
	$a = get_users( 'role=eleve' );
	print_r($a);
	// foreach ( $result as $print )   {
	//   echo '<tr>';
	//   echo '<td>' . $print->firstname .'</td>';
	// 		  echo '<td>' . $print->lastname  .'</td>';
	// 		  echo '<td>' . $print->points    .'</td>';
	//   echo '</tr>';
	// 	}
  ?>

</table>