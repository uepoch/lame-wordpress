<?php
     if(!current_user_can('wait') ) {
               wp_redirect(get_bloginfo('url').'/accueil/');
          }



get_header();
?>

<div class="container">
<p>Votre demande de pré-inscription a bien été prise en compte.</br>Lorsque celle-ci sera validée, vous pourrez accèder à l'ENT.</br></p>
</div>



<?php
get_footer();