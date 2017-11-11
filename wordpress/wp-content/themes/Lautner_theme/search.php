<?php

get_header();
?>



<div class="container">
<?php 
	$actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        			if (preg_match('/en/', $actual_link)){
						echo'<h3>Search results:';echo $_GET['s']; echo'</h3>';
					}
					else{
						echo'<h3>Résultat de votre recherche:';echo $_GET['s']; echo'</h3>';
					}
?>
	<section class="block-home-article">
	<?php

		if (have_posts()){
				while ( have_posts()){
				
					echo'<div class="mea-single-article">';
						the_post();	

						if ( has_post_thumbnail() ) {
   							echo '<a href="' . get_permalink($post->ID) . '" >';
  									the_post_thumbnail();
  							echo '</a>';
						} 

						else {
       						echo '<a href="' . get_permalink($post->ID) . '" ><img src="'. get_stylesheet_directory_uri() . '/></a>';
						}

						echo'<a href="'.get_permalink($post->ID).'">';
						echo '<div class="mea-texte-single-article">';
							echo '<h2>'.get_the_title($post->ID).'</h2>';
							
							echo '<p>'.get_the_date().'</p>';
							echo '<p>'.the_excerpt().'</p>';

						echo '</div>';
						echo '</a>';

					echo '</div>';		
				}				
			}

	else{
		$actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        			echo 'Aucun resultat';
					
	}

	?>
</section>
</div>

<?php
get_footer();
