<?php

get_header();
?>

<div class="container">

	<?php 
	if (have_posts()){
		while ( have_posts()){
			the_post(); 
			
			echo'<div class="h1-img-article">';
				if (has_post_thumbnail()){
					echo '<div class="h1-texte-article">';
						echo'<h1>'; the_title(); echo '</h1>';
						echo'<p>'.get_the_date().'</p>';
					

						$post_tags = get_the_tags();
 						

							if ( $post_tags ) {
    							foreach( $post_tags as $tag ) {
    								echo '<a href="' . get_tag_link($tag->term_id) . '"><div class="gettag-article"><span>' . $tag->name . '</span></div></a>';
    							}
							}
					echo'</div>';	
					
					the_post_thumbnail('large');

				}
			echo'</div>';
			
			echo'<div class="container-content">';
				the_content();
			echo'</div>';
		}
	}
	?>

</div>



<?php
get_footer();
?>
