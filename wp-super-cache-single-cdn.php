<?php
/*
Plugin Name: Disable upload URL rewrite for subsites
Plugin URI: http://interconnectit.com/
Description: Disables the multisite upload URL rewriting so that a single origin-pull cdn can be set up. Supports WP Super Cache.
Author: Robert O'Rourke
Version: 0.1
Author URI: http://interconnectit.com/
*/

if ( is_multisite() && ! is_main_site() && get_site_option( 'ms_files_rewriting' ) ) {

	// upload_dir URLs
	add_filter( 'upload_dir', function( $upload_dir ) {
		global $current_site;
		$blog_id = get_current_blog_id();
		$main_url = str_replace( get_home_url(), get_home_url( $current_site->blog_id ), WP_CONTENT_URL );
		$baseurl = "{$main_url}/blogs.dir/{$blog_id}/files";
		$upload_dir[ 'url' ] = str_replace( $upload_dir[ 'baseurl' ], $baseurl, $upload_dir[ 'url' ] );
		$upload_dir[ 'baseurl' ] = $baseurl;
		return $upload_dir;
	}, 20, 1 );

	// super cache multisite cdn support
	if ( defined( 'WPCACHEHOME' ) ) {

		/**
		* Output filter which runs the actual plugin logic.
		*/
		add_filter( 'wp_cache_ob_callback_filter', 'ms_multisite_upload_scossdl_off_filter', 20 );
		function ms_multisite_upload_scossdl_off_filter( $content ) {
			global $ossdl_off_blog_url, $current_site;
			$ossdl_off_blog_url = get_blog_option( $current_site->blog_id, 'siteurl' );
			return scossdl_off_filter( $content );
		}

		foreach( array( 'off_cdn_url', 'cname', 'include_dirs', 'exclude', 'https' ) as $option )
			add_filter( "option_ossdl_{$option}", function( $value ) use ( $option ) {
				global $current_site;

				// don't run on master site
				if ( $current_site->blog_id === get_current_blog_id() )
					return $value;

				// filter subsite options with master value
				if ( $main_site_value = get_blog_option( $current_site->blog_id, "ossdl_{$option}" ) )
					$value = $main_site_value;

				return $value;
			} );

	}

}
