<?php
     if(!current_user_can('eleve') ) {
               wp_redirect(get_bloginfo('url').'/accueil/');
          }



get_header('entEleve');
?>