<?php

 if( isset($_POST['user_login']) && isset($_POST['user_password']) ){
      $creds = array(
        'user_login' =>  $_POST['user_login'],
        'user_password' => $_POST['user_password']);
     
      $user = wp_signon( $creds, false );


      if ( is_wp_error($user) ) {
        $messerreur="Erreur, votre identifiant ou votre mot de passe est erroné. Veuillez réessayer.";
        }


      else {
            if (in_array( 'professeur', (array) $user->roles )) {
                wp_redirect(get_bloginfo('url').'/accueil-ent-professeur/');
            }
            else if (in_array( 'eleve', (array) $user->roles )) {
                wp_redirect(get_bloginfo('url').'/accueil-ent-eleve/');
            }
            else if(in_array( 'wait', (array) $user->roles )) {
                wp_redirect(get_bloginfo('url').'/valid/');
            }
      }
    
}


get_header();
?>


        <main class="connectFormErreur">
        <?php 
        if (defined('messerreur')) {
            echo $messerreur;
        }
        ?>
            <form method="POST" action="" class="connectForm">
                <div>
                    <label for="nom">Identifiant</label>
                    <input class="champTextConnect" name="user_login" type="text" value="" />
                </div>
                <div>
                    <label for="password">Mot de passe</label>
                    <input class="champTextConnect" name="user_password" type="password" />
                </div>
                <input type="submit" value="Se connecter" name="accesEnt" class="seConnecter">
            </form>
        </main>



<?php
get_footer();