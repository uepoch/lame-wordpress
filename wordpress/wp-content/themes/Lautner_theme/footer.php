
<footer>
	<div class="container" id="navi-footer">	
		<p>Ecole primaire George Lautner</p>
        <p>@ Copyright 2017 Lajance</p>

        
        <?php
        $args = array(
            'menu' => 'menuFooter',
            'container' => 'nav',
            'container_class' => 'nav');
            if (is_single()) ;
            wp_nav_menu( $args );
                        
        ?>
        
    </div>
</footer>


<?php wp_footer(); ?>

</body>
</html>