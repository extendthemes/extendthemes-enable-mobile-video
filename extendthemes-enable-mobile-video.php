<?php
/* 
 * Plugin Name: ExtendThemes - Enable Mobile Video
 * Author: Extendthemes
 * Description: ExtendThemes - Enable the hero video background on mobile 
 *
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * Version: 1.0
 */

add_action( 'wp_enqueue_scripts', function() {
	wp_register_script( 
		'extendthemes-enable-yt-video', 
		plugin_dir_url( __FILE__ ) . 'enable-yt-video.js',  
		array( 'wp-custom-header' ), 
		'1.0', 
		true 
	);

	wp_enqueue_script( 'extendthemes-enable-yt-video' );
} );

// MP4 Video Bg
$ext_video_script = '
jQuery( document ).on( "wp-custom-header-video-loaded", function() {
	var %1$s_VidHeader = document.querySelector( "video#wp-custom-header-video" );
	if ( %1$s_VidHeader ) {
		%1$s_VidHeader.setAttribute( "muted" , true );
		%1$s_VidHeader.setAttribute( "playsinline" , true );
		%1$s_VidHeader.oncanplaythrough = function() {
			%1$s_VidHeader.muted = true;
			%1$s_VidHeader.play();
		}
	}
});';

$ext_current_theme = wp_get_theme();

// Highlight & Empower same as Mesmerize
$ext_themes_list = [
	'mesmerize', 'materialis'
];

foreach ( $ext_themes_list as $ext_theme ) {
	$ext_action_name = $ext_theme . '_background';
	$ext_script = sprintf( $ext_video_script, $ext_theme );
	
	add_action( $ext_action_name, function() use ( $ext_theme, $ext_script ) {		
		   wp_add_inline_script( $ext_theme . '-video-bg', $ext_script ); 
	}, 11 );
}

// OPEX
add_action( 'wp_footer', function() use ( $ext_current_theme, $ext_video_script ) {
    if ( false !== strpos( $ext_current_theme->get( 'Name' ), 'One Page Express' ) ) {
        echo '<script type="text/javascript">' . sprintf( $ext_video_script, 'cp' ) . '</script>';
    }
}, 99 );