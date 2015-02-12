<?php
/**
 * @package   Options_Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2010-2014 WP Theming
 */

class Options_Framework {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 1.7.0
	 * @type string
	 */
	const VERSION = '1.8.2';

	/**
	 * Initialize the plugin.
	 *
	 * @since 1.7.0
	 */
	public function init() {

		// Needs to run every time in case theme has been changed
		add_action( 'admin_init', array( $this, 'set_theme_option' ) );

	}

	/**
	 * Sets option defaults
	 *
	 * @since 1.7.0
	 */
	function set_theme_option() {

		// Load settings
        $mtm_optionsframework_settings = get_option( 'mtm_optionsframework' );

        // Updates the unique option id in the database if it has changed
        if ( function_exists( 'mtm_optionsframework_option_name' ) ) {
			mtm_optionsframework_option_name();
        }
        elseif ( has_action( 'mtm_optionsframework_option_name' ) ) {
			do_action( 'mtm_optionsframework_option_name' );
        }
        // If the developer hasn't explicitly set an option id, we'll use a default
        else {
            $default_themename = get_option( 'stylesheet' );
            $default_themename = preg_replace( "/\W/", "_", strtolower($default_themename ) );
            $default_themename = 'mtm_optionsframework_' . $default_themename;
            if ( isset( $mtm_optionsframework_settings['id'] ) ) {
				if ( $mtm_optionsframework_settings['id'] == $default_themename ) {
					// All good, using default theme id
				} else {
					$mtm_optionsframework_settings['id'] = $default_themename;
					update_option( 'mtm_optionsframework', $mtm_optionsframework_settings );
				}
            }
            else {
				$mtm_optionsframework_settings['id'] = $default_themename;
				update_option( 'mtm_optionsframework', $mtm_optionsframework_settings );
            }
        }

	}

	/**
	 * Wrapper for mtm_optionsframework_options()
	 *
	 * Allows for manipulating or setting options via 'mtm_options' filter
	 * For example:
	 *
	 * <code>
	 * add_filter( 'mtm_options', function( $options ) {
	 *     $options[] = array(
	 *         'name' => 'Input Text Mini',
	 *         'desc' => 'A mini text input field.',
	 *         'id' => 'example_text_mini',
	 *         'std' => 'Default',
	 *         'class' => 'mini',
	 *         'type' => 'text'
	 *     );
	 *
	 *     return $options;
	 * });
	 * </code>
	 *
	 * Also allows for setting options via a return statement in the
	 * options.php file.  For example (in options.php):
	 *
	 * <code>
	 * return array(...);
	 * </code>
	 *
	 * @return array (by reference)
	 */
	static function &_mtm_optionsframework_options() {
		static $options = null;

		if ( !$options ) {
	        // Load options from options.php file (if it exists)
	        $location = apply_filters( 'options_framework_location', array('options.php') );
	        if ( $optionsfile = locate_template( $location ) ) {
	            $maybe_options = require_once $optionsfile;
	            if ( is_array( $maybe_options ) ) {
					$options = $maybe_options;
	            } else if ( function_exists( 'mtm_optionsframework_options' ) ) {
					$options = mtm_optionsframework_options();
				}
	        }

	        // Allow setting/manipulating options via filters
	        $options = apply_filters( 'mtm_options', $options );
		}

		return $options;
	}

}