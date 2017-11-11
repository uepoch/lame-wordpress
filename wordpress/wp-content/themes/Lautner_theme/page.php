<?php

get_header();
?>

<div class="container-contact">
	<?php 
	if (have_posts()){
		while ( have_posts()){
			the_post(); 
			
			echo'<div class="contact-h2">';
				echo'<h2>'; the_title(); echo '</h2>';
			echo'</div>';
			
			the_content();
			
		}
	}
	?>
	<br>
	<br>
	<br>
</div>

<?php
get_footer();
