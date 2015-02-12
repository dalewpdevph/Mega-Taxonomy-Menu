<?php
if(!function_exists("mtm_optionsframework_option_name")) {
	function mtm_optionsframework_option_name() {

		// This gets the theme name from the stylesheet
		$optname = "mega_taxonomy_menu_option";
		$optname = preg_replace("/\W/", "_", strtolower($optname) );

		$mtm_optionsframework_settings = get_option( 'mtm_optionsframework' );
		$mtm_optionsframework_settings['id'] = $optname;
		update_option( 'mtm_optionsframework', $mtm_optionsframework_settings );
	}
}
if ( ! function_exists( 'mtm_optionsframework_init' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'lib/options-framework/options-framework.php';
}



function mtm_optionsframework_menu($menu) {
	
	$menu['menu_title'] = __( 'Mega Taxonomy Menu Options', 'mtm_optionsframework');
	$menu['page_title'] = __( 'Mega Taxonomy Menu Options', 'mtm_optionsframework');
	$menu['menu_slug'] = 'mtm-options-framework';
	$menu['parent_slug'] = 'edit.php';
	$menu['capability'] = 'edit_plugins';
	$menu['position'] = '73.3';
	$menu['mode'] = 'menu';

		
	return $menu;
}
add_filter("mtm_optionsframework_menu","mtm_optionsframework_menu");



add_filter('mtm_options', 'mtm_options'); 

function mtm_options($options) {



	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/images/';
	
	$typography_mixed_fonts = array_merge( mtm_options_typography_get_os_fonts() , mtm_options_typography_get_google_fonts() );
	asort($typography_mixed_fonts);

	$options = array();
	
	$options[] = array(
		'name' => __('Mega Taxonomy Menu Settings', 'mtm_options_framework'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('Logo', 'mtm_options_framework'),
		'desc' => __('Adds logo the mega taxonomy menu', 'mtm_options_framework'),
		'id' => 'mtm_logo',
		'type' => 'upload');
	$menu_terms = get_terms( 'nav_menu', array( 'hide_empty' => true ));
	$menus = array();
	if(!empty($menu_terms)) {
		foreach($menu_terms as $menu_term) {
			$menus[$menu_term->term_id] = $menu_term->name;
		}
	}
 	if ( !empty($menus) ) {
	
	$options[] = array(
		'name' => __('Select a menu', 'mtm_options_framework'),
		'desc' => __('Choose a menu to convert it to a Mega Taxonomy Menu', 'mtm_options_framework'),
		'id' => 'mtm_menu',
		'type' => 'select',
		'options' => $menus);
	} else {
		$options[] = array(
		'name' => __('Menu', 'mtm_options_framework'),
		'desc' => __('Please create a menu in Appearance -> Menus.', 'mtm_options_framework'),
		'type' => 'info');
	}
	
	$options[] = array(
		'name' => __('Menu Position', 'mtm_options_framework'),
		'desc' => __('Choose a menu position. Fixed position floats menu when scrolling down.', 'mtm_options_framework'),
		'id' => 'mtm_menu_position',
		'type' => 'select',
		'std' => 'fixed',
		'options' => array("fixed" => "Fixed", "relative" => "Relative"));
	
	$options[] = array(
		'name' => __('Primary Color', 'mtm_options_framework'),
		'desc' => __('Choose a primary color.', 'mtm_options_framework'),
		'id' => 'mtm_primary_color',
		'std' => '#00aeef',
		'type' => 'color' );	
	
	$options[] = array(
		'name' => __('Secondary Color', 'mtm_options_framework'),
		'desc' => __('Choose a secondary color.', 'mtm_options_framework'),
		'id' => 'mtm_secondary_color',
		'std' => '#DDF0F9',
		'type' => 'color' );
			
	$options[] = array(
		'name' => __('Tertiary Color', 'mtm_options_framework'),
		'desc' => __('Choose a tertiary color.', 'mtm_options_framework'),
		'id' => 'mtm_tertiary_color',
		'std' => '#C7E6F5',
		'type' => 'color' );
		
	$options[] = array(
		'name' => __('Menu Width', 'mtm_options_framework'),
		'desc' => __('The mega taxonomy menu maximum width in px.', 'mtm_options_framework'),
		'id' => 'mtm_width',
		'std' => '1300',
		'class' => 'mini',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Menu Height', 'mtm_options_framework'),
		'desc' => __('The mega taxonomy menu height in px.', 'mtm_options_framework'),
		'id' => 'mtm_height',
		'std' => '70',
		'class' => 'mini',
		'type' => 'text');
		
	$options[] = array( 'name' => __('Typography', 'mtm_options_framework'),
		'desc' => __('Select font size, font family and font color for the menu.', 'mtm_options_framework'),
		'id' => "mtm_typography",
		'std' => array( 'size' => '14px', 'face' => 'Arial, serif', 'color' => '#cccccc'),
		'type' => 'typography', 
		'options' => array(
						"faces" => $typography_mixed_fonts,
						"styles" => false
					)
		);
		
	$options[] = array(
		'name' => __('Article color', 'mtm_options_framework'),
		'desc' => __('select color of article text.', 'mtm_options_framework'),
		'id' => 'mtm_article_color',
		'std' => '#000000',
		'type' => 'color' );	
	
	$options[] = array(
		'name' => __('Article Width', 'mtm_options_framework'),
		'desc' => __('Choose the article width layout.', 'mtm_options_framework'),
		'id' => 'mtm_article_width',
		'std' => 'fixed',
		'type' => 'select', 
		'class' => 'mini', 
		'options' => array("fixed" => "Fixed", "fluid" => "Fluid")
		);
	$image_sizes = array();
	$registered_images = get_intermediate_image_sizes();
	
	if(!empty($registered_images)) {
		foreach($registered_images as $img_key => $size_name) {
			$image_sizes[$size_name] = $size_name;
		}
	}
	$options[] = array(
		'name' => __('Article Image Size', 'mtm_options_framework'),
		'desc' => __('Choose the article image size.', 'mtm_options_framework'),
		'id' => 'mtm_article_image_size',
		'std' => 'thumbnail',
		'type' => 'select', 
		'class' => 'mini', 
		'options' => $image_sizes
		);
		
	$options[] = array(
		'name' => __('Article Image Height crop', 'mtm_options_framework'),
		'desc' => __('Use CSS to crop your article images height in px.', 'mtm_options_framework'),
		'id' => 'mtm_article_image_crop_height',
		'type' => 'text',
		'class' => 'mini'
		);	
		
	$options[] = array(
		'name' => __('Remove See All Link', 'mtm_options_framework'),
		'desc' => __('Check to remove "See all" link at the bottom of the articles. ', 'mtm_options_framework'),
		'id' => 'mtm_article_see_all',
		'std' => false,
		'type' => 'checkbox'
		);
	
	$columns = array(1 => 1, 2 => 2,3 => 3,4 => 4,5 => 5);
	
	$options[] = array(
		'name' => __('Columns', 'mtm_options_framework'),
		'desc' => __('Number of articles to display', 'mtm_options_framework'),
		'id' => 'mtm_column',
		'std' => 5,
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $columns);

	$options[] = array(
		'name' => __('Custom CSS', 'mtm_options_framework'),
		'desc' => __('Add your custom Css here.', 'mtm_options_framework'),
		'id' => 'mtm_custom_css',
		'std' => '',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('HTML5 Local Storage Minutes', 'options_check'),
		'desc' => __('Input how many minutes html5 local storage clears. Note that for every post update 
		html5 local storage clears to have an updated articles in the menu. Default to 5 hours. 300min = 5h * 60min', 'options_check'),
		'id' => 'mtm_localstorage_minutes',
		'std' => '300',
		'class' => 'mini',
		'type' => 'text');	
		
	$options[] = array(
			'name' => __('Clear Cache', 'options_check'),
			'desc' => __("<a id='mtm_clear_link' style='font-size: 34px' href='" . admin_url() . "?page=mtm-options-framework&clear_jstorage=true&clear_mam=1'>Clear</a> Click to clear cache.", 'options_check'),
			'type' => 'info');

	$options[] = array(
		'name' => __('Social Media Links', 'options_check'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('Facebook URL', 'options_check'),
		'desc' => __('Facebook url link.', 'options_check'),
		'id' => 'mtm_facebook_url',
		'std' => '',
		'class' => 'medium',
		'type' => 'text');	
	
	$options[] = array(
		'name' => __('Twitter URL', 'options_check'),
		'desc' => __('Twitter url link.', 'options_check'),
		'id' => 'mtm_twitter_url',
		'std' => '',
		'class' => 'medium',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Pinterest URL', 'options_check'),
		'desc' => __('Pinterest url link.', 'options_check'),
		'id' => 'mtm_pinterest_url',
		'std' => '',
		'class' => 'medium',
		'type' => 'text');	
	
	$options[] = array(
		'name' => __('Instagram URL', 'options_check'),
		'desc' => __('Instagram url link.', 'options_check'),
		'id' => 'mtm_instagram_url',
		'std' => '',
		'class' => 'medium',
		'type' => 'text');	
	
	$options[] = array(
		'name' => __('Google Plus URL', 'options_check'),
		'desc' => __('Google Plus url link.', 'options_check'),
		'id' => 'mtm_googleplus_url',
		'std' => '',
		'class' => 'medium',
		'type' => 'text');

	return $options;


};



/**
 * Returns an array of system fonts
 * Feel free to edit this, update the font fallbacks, etc.
 */
function mtm_options_typography_get_os_fonts() {
	// OS Font Defaults
	$os_faces = array(
		'Arial, sans-serif' => 'Arial',
		'"Avant Garde", sans-serif' => 'Avant Garde',
		'Cambria, Georgia, serif' => 'Cambria',
		'Copse, sans-serif' => 'Copse',
		'Garamond, "Hoefler Text", Times New Roman, Times, serif' => 'Garamond',
		'Georgia, serif' => 'Georgia',
		'"Helvetica Neue", Helvetica, sans-serif' => 'Helvetica Neue',
		'Tahoma, Geneva, sans-serif' => 'Tahoma'
	);
	return $os_faces;
}

/**
 * Returns a select list of Google fonts
 * Feel free to edit this, update the fallbacks, etc.
 */
function mtm_options_typography_get_google_fonts() {
	// Google Font Defaults
	$google_faces = array(
		'Arvo, serif' => 'Arvo',
		'Copse, sans-serif' => 'Copse',
		'Droid Sans, sans-serif' => 'Droid Sans',
		'Droid Serif, serif' => 'Droid Serif',
		'Lobster, cursive' => 'Lobster',
		'Nobile, sans-serif' => 'Nobile',
		'Open Sans, sans-serif' => 'Open Sans',
		'Oswald, sans-serif' => 'Oswald',
		'Pacifico, cursive' => 'Pacifico',
		'Rokkitt, serif' => 'Rokkit',
		'PT Sans, sans-serif' => 'PT Sans',
		'Quattrocento, serif' => 'Quattrocento',
		'Raleway, cursive' => 'Raleway',
		'Ubuntu, sans-serif' => 'Ubuntu',
		'Yanone Kaffeesatz, sans-serif' => 'Yanone Kaffeesatz'
	);
	return $google_faces;
}

/**
 * Checks font options to see if a Google font is selected.
 * If so, options_typography_enqueue_google_font is called to enqueue the font.
 * Ensures that each Google font is only enqueued once.
 */
if ( !function_exists( 'mtm_options_typography_google_fonts' ) ) {
	function mtm_options_typography_google_fonts() {
		$all_google_fonts = array_keys( mtm_options_typography_get_google_fonts() );
		// Define all the options that possibly have a unique Google font
		$google_mixed = mtm_get_option('mtm_typography', false);

		// Get the font face for each option and put it in an array
		$selected_fonts = array($google_mixed['face']);
		// Remove any duplicates in the list
		$selected_fonts = array_unique($selected_fonts);
		// Check each of the unique fonts against the defined Google fonts
		// If it is a Google font, go ahead and call the function to enqueue it
		foreach ( $selected_fonts as $font ) {
			if ( in_array( $font, $all_google_fonts ) ) {
				mtm_options_typography_enqueue_google_font($font);
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'mtm_options_typography_google_fonts' );
/**
 * Enqueues the Google $font that is passed
 */
function mtm_options_typography_enqueue_google_font($font) {
	$font = explode(',', $font);
	$font = $font[0];
	// Certain Google fonts need slight tweaks in order to load properly
	// Like our friend "Raleway"
	if ( $font == 'Raleway' )
		$font = 'Raleway:100';
	$font = str_replace(" ", "+", $font);
	wp_enqueue_style( "options_typography_$font", "http://fonts.googleapis.com/css?family=$font", false, null, 'all' );
}