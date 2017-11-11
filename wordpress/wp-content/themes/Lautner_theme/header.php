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
       
        <body>
                <header>
                <section class="skewedBoxBlue">
                    <div class="logo">
                    <a href="<?php echo get_bloginfo('url').'/accueil/'; ?> ">
                    <img id="logo" src="<?php echo get_bloginfo('template_url'); ?>/img/logo.png"/>
                    </a>
                    </div>

                       <div id="navi">
                            
                                <?php
                                $args = array(
                                    'menu' => 'menuPrincipal',
                                    'container' => 'nav',
                                    'container_class' => 'nav');
                              
                                            if (is_single()) echo 'current_page_item';
                                 
                                            wp_nav_menu( $args );
                            
                                ?>
                                      
                        </div>
                     
                    
                     <div class="under-nav">
                        <?php get_search_form(); ?>
                    </div>
                </section>
                </header>



<!-- fil d'ariane -->
<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">
    <?php if(function_exists('bcn_display'))
    {
        bcn_display();
    }?>
</div>

