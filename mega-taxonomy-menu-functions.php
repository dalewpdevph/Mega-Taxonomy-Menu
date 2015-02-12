<?php

function mtm_get_template_part( $slug, $name = '' ) {
	
	$template = '';
	
	if ( $name )
		$template = locate_template( array ( "{$slug}-{$name}.php" ) );

	
	if ( !$template && $name && file_exists( MTM()->get_dir() . "/templates/{$slug}-{$name}.php" ) )
		$template = MTM()->get_dir() . "/templates/{$slug}-{$name}.php";	
	
	if ( !$template && $slug && file_exists( MTM()->get_dir() . "/templates/{$slug}.php" ) )
		$template = MTM()->get_dir() . "/templates/{$slug}.php";

	
	if ( !$template )
		$template = locate_template( array ( "{$slug}.php" ) );


		
	if ( $template )
		load_template( $template, false );
}

function mtm_get_option_def($key) {
	$def = MTM()->option_defaults();
	$option = mtm_get_option($key);
	if(empty($option)) {
		return $def[$key];
	} else {
		return $option;
	}
}
?>
