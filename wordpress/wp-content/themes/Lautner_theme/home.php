<?php

get_header();
?>

	<div class="container">


Slider

		<section class="block-home-article">
		
		<?php 
		
			query_posts( 'posts_per_page=3' );
		
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

						echo '</div>';
						echo '</a>';

					echo '</div>';		
				}				
			}
		?>
		
		

	</div>

<?php
get_footer();