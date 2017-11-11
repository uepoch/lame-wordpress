<?php


function init_menu(){
	register_nav_menus(
		array('menuPrincipal' => 'Menu principal du site')
		);
}
add_action('init','init_menu');


add_theme_support('post-thumbnails',array('post'));


add_image_size('identifiantPerso', 500, 500, false);



add_action('init','remove_page_from_search');

function remove_page_from_search(){
	global $wp_post_types;
	$wp_post_types['page'] ->exclude_from_search = true;
}




add_action('admin_init','modify_capabilities_role');

function modify_capabilities_role(){
	$auteur = get_role('author');
	$auteur->add_cap('edit_others_posts');
	/*$auteur->remove_cap('edit_others_posts');*/
}




// Creer un role
add_action('admin_init', 'create_new_role');
function create_new_role() {
	add_role('professeur', 'Professeur',
		array('read' => true,)
			);
	add_role('eleve', 'Eleve', array(
		'read' => true,)
			);
	add_role('wait', 'En attente', array(
		'read' => true,)
			);
}



function wpdocs_custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );




// Création nouveau boutons or mce
//1.création d'un select
add_filter('mce_buttons_2', 'add_select_perso');
function add_select_perso($buttons) {
	array_unshift($buttons, 'styleselect');
	return $buttons;
}

//2.Choix des options possibles dans le select
add_filter('tiny_mce_before_init', 'option_in_select');
function option_in_select($styles) {
	$style_formats = array (
		array (
			'title' => 'Lobster',
			'inline' => 'span', // ou : 'block' => 'div'
			'classes' => 'lobster'
			));

	$styles['style_formats'] = json_encode($style_formats);
	return $styles;
}

//3. Declaration d'un css pour le bouton qu'on vient de creer
add_action('init','add_css_for_back');
function add_css_for_back () {
	add_editor_style('editor-style.css');
}


// Rajouter des boutons d'option dans l'edit d'un espace texte dans le backo (existant dans mce)
add_filter('mce_buttons', 'modify_mce_buttons');
function modify_mce_buttons($buttons) {
	$buttons[] = 'fontsizeselect'; // Permet d'avoir un menu déroulant pour choisir la taille de la typo

	return $buttons;
}


// Ajouter des champs personnalisés dans le profil utilisateur de WordPress
function modify_user_contact_methods( $user_contact ) {

	// Add user contact methods
	$user_contact['classe']   = __( 'Classe'   );
	$user_contact['telParent']   = __( 'Telephone des parents'   );

	// Remove user contact methods
	unset( $user_contact['aim']    );
	unset( $user_contact['yim']    );

	return $user_contact;
}
add_filter( 'user_contactmethods', 'modify_user_contact_methods' );
