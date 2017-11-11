<!DOCTYPE html>
    <html <?php language_attributes(); ?> >
        <head>
            <meta charset ="<?php bloginfo('charset'); ?>" />
            <meta name="viewport" content="width=device-width, intial-scale=1.0"/>

            <title>Ecole George Lautner</title>
        
            <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>" />
            <link rel="stylesheet" type="text/css" href= "<?php bloginfo('stylesheet_url');?>" />
            <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">

        
                        <?php wp_head(); ?>
        </head>
       
        <body class="bodyEnt">
        <header>
            <div class="sidenav">

                <div class="sidenavYellow">
                <a href="<?php echo wp_logout_url( home_url('/accueil/') ); ?>">Deconnexion</a>


                </div>
                

                <div class="sidenavBlue">
                    <div>                       
                    <?php
                        $args = array(
                            'menu' => 'menuEntEleve',
                            'container' => 'nav',
                            'container_class' => 'navent');
                              
                         /*if (is_single()) echo 'current_page_item';
                                 */
                        wp_nav_menu( $args );
                            
                    ?>
                    </div>     

                        
                    <div class="logoEnt">
                        <img src="<?php echo get_bloginfo('template_url'); ?>/img/logo.png"/>
                    </div>
                </div>

            </div>
        </header>
