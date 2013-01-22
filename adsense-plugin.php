<?php
/*
Plugin Name: Google AdSense Plugin
Plugin URI:  http://bestwebsoft.com/plugin/
Description: This plugin allows implementing Google AdSense to your website.
Author: BestWebSoft
Version: 1.12
Author URI: http://bestwebsoft.com/
License: GPLv2 or later
*/

/*  © Copyright 2011  BestWebSoft  ( admin@bestwebsoft.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once( 'adsense-plugin.class.php' );  // including a class which contains a plugin functions

$this_adsns_plugin = plugin_basename(__FILE__);  // path to this file(from plugins dir)

$adsns_plugin = new adsns();  // creating a variable with type of our class
 
$adsns_plugin->page_title = __( 'AdSense Options', 'adsense'); // title for options page
 
$adsns_plugin->menu_title = __( 'AdSense', 'adsense'); 	// name in menu

// This function showing ads at the choosen position
function adsns_show_ads() {
	global $adsns_options, $max_ads, $count, $current_count, $adsns_count, $adsns_plugin;
	$adsns_plugin->adsns_activate();

	// checking in what position we should show an ads
	if ( $adsns_options['position'] == 'postend' ) {  									// if we choose ad position after post(single page)
		add_filter( 'the_content', array( $adsns_plugin, 'adsns_end_post_ad' ) );  	// adding ad after post
	}
	
	else if ( $adsns_options['position'] == 'homepostend' ) {								// if we choose ad position after post(home page)
		add_filter( 'the_content', array( $adsns_plugin, 'adsns_end_home_post_ad' ) );		// adding ad after post
	}

	else if ( $adsns_options['position'] == 'homeandpostend' ) {										// if we choose ad position after post(home page)
		add_filter( 'the_content', array( $adsns_plugin, 'adsns_end_home_post_ad' ) );		// adding ad after post
		add_filter( 'the_content', array( $adsns_plugin, 'adsns_end_post_ad' ) );  	// adding ad after post
	}

	else if ( $adsns_options['position'] == 'commentform' ) {											// if we choose ad position after comment form
		add_filter( 'comment_id_fields', array( $adsns_plugin, 'adsns_end_comment_ad' ) );		// adding ad after comment form
	}

	else if ( $adsns_options['position'] == 'footer' ) {
	// if we choose ad position in a footer
		add_filter( 'get_footer', array( $adsns_plugin, 'adsns_end_footer_ad' ) );		// adding footer ad
	}
	// end checking
}

if( ! function_exists( 'bws_add_menu_render' ) ) {
	function bws_add_menu_render() {
		global $title;
		$active_plugins = get_option('active_plugins');
		$all_plugins		= get_plugins();

		$array_activate = array();
		$array_install	= array();
		$array_recomend = array();
		$count_activate = $count_install = $count_recomend = 0;
		$array_plugins	= array(
			array( 'captcha\/captcha.php', 'Captcha', 'http://wordpress.org/extend/plugins/captcha/', 'http://bestwebsoft.com/plugin/captcha-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Captcha+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=captcha.php' ), 
			array( 'contact-form-plugin\/contact_form.php', 'Contact Form', 'http://wordpress.org/extend/plugins/contact-form-plugin/', 'http://bestwebsoft.com/plugin/contact-form/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Contact+Form+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=contact_form.php' ), 
			array( 'facebook-button-plugin\/facebook-button-plugin.php', 'Facebook Like Button Plugin', 'http://wordpress.org/extend/plugins/facebook-button-plugin/', 'http://bestwebsoft.com/plugin/facebook-like-button-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Facebook+Like+Button+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=facebook-button-plugin.php' ), 
			array( 'twitter-plugin\/twitter.php', 'Twitter Plugin', 'http://wordpress.org/extend/plugins/twitter-plugin/', 'http://bestwebsoft.com/plugin/twitter-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Twitter+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=twitter.php' ), 
			array( 'portfolio\/portfolio.php', 'Portfolio', 'http://wordpress.org/extend/plugins/portfolio/', 'http://bestwebsoft.com/plugin/portfolio-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Portfolio+bestwebsoft&plugin-search-input=Search+Plugins', '' ),
			array( 'gallery-plugin\/gallery-plugin.php', 'Gallery', 'http://wordpress.org/extend/plugins/gallery-plugin/', 'http://bestwebsoft.com/plugin/gallery-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Gallery+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', '' ),
			array( 'adsense-plugin\/adsense-plugin.php', 'Google AdSense Plugin', 'http://wordpress.org/extend/plugins/adsense-plugin/', 'http://bestwebsoft.com/plugin/google-adsense-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Adsense+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=adsense-plugin.php' ),
			array( 'custom-search-plugin\/custom-search-plugin.php', 'Custom Search Plugin', 'http://wordpress.org/extend/plugins/custom-search-plugin/', 'http://bestwebsoft.com/plugin/custom-search-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Custom+Search+plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=custom_search.php' ),
			array( 'quotes_and_tips\/quotes-and-tips.php', 'Quotes and Tips', 'http://wordpress.org/extend/plugins/quotes-and-tips/', 'http://bestwebsoft.com/plugin/quotes-and-tips/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Quotes+and+Tips+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=quotes-and-tips.php' ),
			array( 'updater\/updater.php', 'Updater', 'http://wordpress.org/extend/plugins/updater/', 'http://bestwebsoft.com/plugin/updater/', '/wp-admin/plugin-install.php?tab=search&s=updater+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=updater-options' )
		);
		foreach($array_plugins as $plugins) {
			if( 0 < count( preg_grep( "/".$plugins[0]."/", $active_plugins ) ) ) {
				$array_activate[$count_activate]['title'] = $plugins[1];
				$array_activate[$count_activate]['link']	= $plugins[2];
				$array_activate[$count_activate]['href']	= $plugins[3];
				$array_activate[$count_activate]['url']	= $plugins[5];
				$count_activate++;
			}
			else if( array_key_exists(str_replace("\\", "", $plugins[0]), $all_plugins) ) {
				$array_install[$count_install]['title'] = $plugins[1];
				$array_install[$count_install]['link']	= $plugins[2];
				$array_install[$count_install]['href']	= $plugins[3];
				$count_install++;
			}
			else {
				$array_recomend[$count_recomend]['title'] = $plugins[1];
				$array_recomend[$count_recomend]['link']	= $plugins[2];
				$array_recomend[$count_recomend]['href']	= $plugins[3];
				$array_recomend[$count_recomend]['slug']	= $plugins[4];
				$count_recomend++;
			}
		}
		?>
		<div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2><?php echo $title;?></h2>
			<?php if( 0 < $count_activate ) { ?>
			<div>
				<h3><?php _e( 'Activated plugins', 'adsense' ); ?></h3>
				<?php foreach( $array_activate as $activate_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $activate_plugin['title']; ?></div> <p><a href="<?php echo $activate_plugin['link']; ?>" target="_blank"><?php echo __( "Read more", 'adsense'); ?></a> <a href="<?php echo $activate_plugin['url']; ?>"><?php echo __( "Settings", 'adsense'); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( 0 < $count_install ) { ?>
			<div>
				<h3><?php _e( 'Installed plugins', 'adsense' ); ?></h3>
				<?php foreach($array_install as $install_plugin) { ?>
				<div style="float:left; width:200px;"><?php echo $install_plugin['title']; ?></div> <p><a href="<?php echo $install_plugin['link']; ?>" target="_blank"><?php echo __( "Read more", 'adsense'); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( 0 < $count_recomend ) { ?>
			<div>
				<h3><?php _e( 'Recommended plugins', 'adsense' ); ?></h3>
				<?php foreach( $array_recomend as $recomend_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $recomend_plugin['title']; ?></div> <p><a href="<?php echo $recomend_plugin['link']; ?>" target="_blank"><?php echo __( "Read more", 'adsense'); ?></a> <a href="<?php echo $recomend_plugin['href']; ?>" target="_blank"><?php echo __( "Download", 'adsense'); ?></a> <a class="install-now" href="<?php echo get_bloginfo( "url" ) . $recomend_plugin['slug']; ?>" title="<?php esc_attr( sprintf( __( 'Install %s' ), $recomend_plugin['title'] ) ) ?>" target="_blank"><?php echo __( 'Install now from wordpress.org', 'adsense' ) ?></a></p>
				<?php } ?>
				<span style="color: rgb(136, 136, 136); font-size: 10px;"><?php _e( 'If you have any questions, please contact us via plugin@bestwebsoft.com or fill in our contact form on our site', 'adsense' ); ?> <a href="http://bestwebsoft.com/contact/">http://bestwebsoft.com/contact/</a></span>
			</div>
			<?php } ?>
		</div>
		<?php
	}
}

if ( ! function_exists ( 'adsns_plugin_init' ) ) {
	function adsns_plugin_init() {
		// Internationalization
		load_plugin_textdomain( 'adsense', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

// add "Settings" link to the plugin action page
add_filter( 'plugin_action_links', array( $adsns_plugin, 'adsns_plugin_action_links'), 10, 2 );

// Additional links on the plugin page
add_filter( 'plugin_row_meta', array( $adsns_plugin, 'adsns_register_plugin_links'), 10, 2 );

add_action( 'init', 'adsns_plugin_init' );
add_action( 'init', array( $adsns_plugin, 'adsns_activate' ) );
add_action( 'admin_init', array( $adsns_plugin, 'adsns_write_admin_head' ) );

// Action for adsns_show_ads
add_action( 'after_setup_theme', 'adsns_show_ads' );

// Display the plugin widget
add_action( 'widgets_init', array( $adsns_plugin, 'adsns_register_widget' ) );

// Adding ads stylesheets
add_action( 'wp_head', array( $adsns_plugin, 'adsns_head' ) );

// Adding 'BWS Plugins' admin menu
add_action( 'admin_menu', array( $adsns_plugin, 'adsns_add_admin_menu' ) );

// Deactivation hook
register_deactivation_hook( __FILE__, array( $adsns_plugin, 'adsns_deactivate' ) );

// Activation hook
register_activation_hook( __FILE__, array( $adsns_plugin, 'adsns_activate' ) );


?>
