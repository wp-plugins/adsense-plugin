<?php
/*
Plugin Name: Google AdSense Plugin
Plugin URI:  http://bestwebsoft.com/plugin/
Description: This plugin allows implementing Google AdSense to your website.
Author: BestWebSoft
Version: 1.21
Author URI: http://bestwebsoft.com/
License: GPLv2 or later
*/

/*  © Copyright 2011  BestWebSoft  ( http://support.bestwebsoft.com )

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
 
$adsns_plugin->page_title = __( 'AdSense Settings', 'adsense'); // title for options page
 
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

if ( ! function_exists( 'bws_add_menu_render' ) ) {
	function bws_add_menu_render() {
		global $wpdb, $wp_version, $title;
		$active_plugins = get_option('active_plugins');
		$all_plugins = get_plugins();
		$error = '';
		$message = '';
		$bwsmn_form_email = '';

		$array_activate = array();
		$array_install	= array();
		$array_recomend = array();
		$count_activate = $count_install = $count_recomend = 0;
		$array_plugins	= array(
			array( 'captcha\/captcha.php', 'Captcha', 'http://bestwebsoft.com/plugin/captcha-plugin/', 'http://bestwebsoft.com/plugin/captcha-plugin/#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Captcha+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=captcha.php' ), 
			array( 'contact-form-plugin\/contact_form.php', 'Contact Form', 'http://bestwebsoft.com/plugin/contact-form/', 'http://bestwebsoft.com/plugin/contact-form/#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Contact+Form+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=contact_form.php' ), 
			array( 'facebook-button-plugin\/facebook-button-plugin.php', 'Facebook Like Button Plugin', 'http://bestwebsoft.com/plugin/facebook-like-button-plugin/', 'http://bestwebsoft.com/plugin/facebook-like-button-plugin/#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Facebook+Like+Button+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=facebook-button-plugin.php' ), 
			array( 'twitter-plugin\/twitter.php', 'Twitter Plugin', 'http://bestwebsoft.com/plugin/twitter-plugin/', 'http://bestwebsoft.com/plugin/twitter-plugin/#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Twitter+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=twitter.php' ), 
			array( 'portfolio\/portfolio.php', 'Portfolio', 'http://bestwebsoft.com/plugin/portfolio-plugin/', 'http://bestwebsoft.com/plugin/portfolio-plugin/#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Portfolio+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=portfolio.php' ),
			array( 'gallery-plugin\/gallery-plugin.php', 'Gallery', 'http://bestwebsoft.com/plugin/gallery-plugin/', 'http://bestwebsoft.com/plugin/gallery-plugin/#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Gallery+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=gallery-plugin.php' ),
			array( 'adsense-plugin\/adsense-plugin.php', 'Google AdSense Plugin', 'http://bestwebsoft.com/plugin/google-adsense-plugin/', 'http://bestwebsoft.com/plugin/google-adsense-plugin/#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Adsense+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=adsense-plugin.php' ),
			array( 'custom-search-plugin\/custom-search-plugin.php', 'Custom Search Plugin', 'http://bestwebsoft.com/plugin/custom-search-plugin/', 'http://bestwebsoft.com/plugin/custom-search-plugin/#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Custom+Search+plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=custom_search.php' ),
			array( 'quotes-and-tips\/quotes-and-tips.php', 'Quotes and Tips', 'http://bestwebsoft.com/plugin/quotes-and-tips/', 'http://bestwebsoft.com/plugin/quotes-and-tips/#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Quotes+and+Tips+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=quotes-and-tips.php' ),
			array( 'google-sitemap-plugin\/google-sitemap-plugin.php', 'Google sitemap plugin', 'http://bestwebsoft.com/plugin/google-sitemap-plugin/', 'http://bestwebsoft.com/plugin/google-sitemap-plugin/#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Google+sitemap+plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=google-sitemap-plugin.php' ),
			array( 'updater\/updater.php', 'Updater', 'http://bestwebsoft.com/plugin/updater-plugin/', 'http://bestwebsoft.com/plugin/updater-plugin/#download', '/wp-admin/plugin-install.php?tab=search&s=updater+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=updater-options' )
		);
		foreach ( $array_plugins as $plugins ) {
			if( 0 < count( preg_grep( "/".$plugins[0]."/", $active_plugins ) ) ) {
				$array_activate[$count_activate]["title"] = $plugins[1];
				$array_activate[$count_activate]["link"] = $plugins[2];
				$array_activate[$count_activate]["href"] = $plugins[3];
				$array_activate[$count_activate]["url"]	= $plugins[5];
				$count_activate++;
			} else if ( array_key_exists(str_replace( "\\", "", $plugins[0]), $all_plugins ) ) {
				$array_install[$count_install]["title"] = $plugins[1];
				$array_install[$count_install]["link"]	= $plugins[2];
				$array_install[$count_install]["href"]	= $plugins[3];
				$count_install++;
			} else {
				$array_recomend[$count_recomend]["title"] = $plugins[1];
				$array_recomend[$count_recomend]["link"] = $plugins[2];
				$array_recomend[$count_recomend]["href"] = $plugins[3];
				$array_recomend[$count_recomend]["slug"] = $plugins[4];
				$count_recomend++;
			}
		}
		$array_activate_pro = array();
		$array_install_pro	= array();
		$array_recomend_pro = array();
		$count_activate_pro = $count_install_pro = $count_recomend_pro = 0;
		$array_plugins_pro	= array(
			array( 'gallery-plugin-pro\/gallery-plugin-pro.php', 'Gallery Pro', 'http://bestwebsoft.com/plugin/gallery-pro/?k=382e5ce7c96a6391f5ffa5e116b37fe0', 'http://bestwebsoft.com/plugin/gallery-pro/?k=382e5ce7c96a6391f5ffa5e116b37fe0#purchase', 'admin.php?page=gallery-plugin-pro.php' ),
			array( 'contact-form-pro\/contact_form_pro.php', 'Contact Form Pro', 'http://bestwebsoft.com/plugin/contact-form-pro/?k=773dc97bb3551975db0e32edca1a6d71', 'http://bestwebsoft.com/plugin/contact-form-pro/?k=773dc97bb3551975db0e32edca1a6d71#purchase', 'admin.php?page=contact_form_pro.php' )
		);
		foreach ( $array_plugins_pro as $plugins ) {
			if( 0 < count( preg_grep( "/".$plugins[0]."/", $active_plugins ) ) ) {
				$array_activate_pro[$count_activate_pro]["title"] = $plugins[1];
				$array_activate_pro[$count_activate_pro]["link"] = $plugins[2];
				$array_activate_pro[$count_activate_pro]["href"] = $plugins[3];
				$array_activate_pro[$count_activate_pro]["url"]	= $plugins[4];
				$count_activate_pro++;
			} else if( array_key_exists(str_replace( "\\", "", $plugins[0]), $all_plugins ) ) {
				$array_install_pro[$count_install_pro]["title"] = $plugins[1];
				$array_install_pro[$count_install_pro]["link"]	= $plugins[2];
				$array_install_pro[$count_install_pro]["href"]	= $plugins[3];
				$count_install_pro++;
			} else {
				$array_recomend_pro[$count_recomend_pro]["title"] = $plugins[1];
				$array_recomend_pro[$count_recomend_pro]["link"] = $plugins[2];
				$array_recomend_pro[$count_recomend_pro]["href"] = $plugins[3];
				$count_recomend_pro++;
			}
		}
		
		$sql_version = $wpdb->get_var( "SELECT VERSION() AS version" );
	    $mysql_info = $wpdb->get_results( "SHOW VARIABLES LIKE 'sql_mode'" );
	    if ( is_array( $mysql_info) )
	    	$sql_mode = $mysql_info[0]->Value;
	    if ( empty( $sql_mode ) )
	    	$sql_mode = __( 'Not set', 'adsense' );
	    if ( ini_get( 'safe_mode' ) )
	    	$safe_mode = __( 'On', 'adsense' );
	    else
	    	$safe_mode = __( 'Off', 'adsense' );
	    if ( ini_get( 'allow_url_fopen' ) )
	    	$allow_url_fopen = __( 'On', 'adsense' );
	    else
	    	$allow_url_fopen = __( 'Off', 'adsense' );
	    if ( ini_get( 'upload_max_filesize' ) )
	    	$upload_max_filesize = ini_get( 'upload_max_filesize' );
	    else
	    	$upload_max_filesize = __( 'N/A', 'adsense' );
	    if ( ini_get('post_max_size') )
	    	$post_max_size = ini_get('post_max_size');
	    else
	    	$post_max_size = __( 'N/A', 'adsense' );
	    if ( ini_get( 'max_execution_time' ) )
	    	$max_execution_time = ini_get( 'max_execution_time' );
	    else
	    	$max_execution_time = __( 'N/A', 'adsense' );
	    if ( ini_get( 'memory_limit' ) )
	    	$memory_limit = ini_get( 'memory_limit' );
	    else
	    	$memory_limit = __( 'N/A', 'adsense' );
	    if ( function_exists( 'memory_get_usage' ) )
	    	$memory_usage = round( memory_get_usage() / 1024 / 1024, 2 ) . __(' Mb', 'adsense' );
	    else
	    	$memory_usage = __( 'N/A', 'adsense' );
	    if ( is_callable( 'exif_read_data' ) )
	    	$exif_read_data = __( 'Yes', 'adsense' ) . " ( V" . substr( phpversion( 'exif' ), 0,4 ) . ")" ;
	    else
	    	$exif_read_data = __( 'No', 'adsense' );
	    if ( is_callable( 'iptcparse' ) )
	    	$iptcparse = __( 'Yes', 'adsense' );
	    else
	    	$iptcparse = __( 'No', 'adsense' );
	    if ( is_callable( 'xml_parser_create' ) )
	    	$xml_parser_create = __( 'Yes', 'adsense' );
	    else
	    	$xml_parser_create = __( 'No', 'adsense' );

		if ( function_exists( 'wp_get_theme' ) )
			$theme = wp_get_theme();
		else
			$theme = get_theme( get_current_theme() );

		if ( function_exists( 'is_multisite' ) ) {
			if ( is_multisite() ) {
				$multisite = __( 'Yes', 'adsense' );
			} else {
				$multisite = __( 'No', 'adsense' );
			}
		} else
			$multisite = __( 'N/A', 'adsense' );

		$site_url = get_option( 'siteurl' );
		$home_url = get_option( 'home' );
		$db_version = get_option( 'db_version' );
		$system_info = array(
			'system_info' => '',
			'active_plugins' => '',
			'inactive_plugins' => ''
		);
		$system_info['system_info'] = array(
	        __( 'Operating System', 'adsense' )				=> PHP_OS,
	        __( 'Server', 'adsense' )						=> $_SERVER["SERVER_SOFTWARE"],
	        __( 'Memory usage', 'adsense' )					=> $memory_usage,
	        __( 'MYSQL Version', 'adsense' )				=> $sql_version,
	        __( 'SQL Mode', 'adsense' )						=> $sql_mode,
	        __( 'PHP Version', 'adsense' )					=> PHP_VERSION,
	        __( 'PHP Safe Mode', 'adsense' )				=> $safe_mode,
	        __( 'PHP Allow URL fopen', 'adsense' )			=> $allow_url_fopen,
	        __( 'PHP Memory Limit', 'adsense' )				=> $memory_limit,
	        __( 'PHP Max Upload Size', 'adsense' )			=> $upload_max_filesize,
	        __( 'PHP Max Post Size', 'adsense' )			=> $post_max_size,
	        __( 'PHP Max Script Execute Time', 'adsense' )	=> $max_execution_time,
	        __( 'PHP Exif support', 'adsense' )				=> $exif_read_data,
	        __( 'PHP IPTC support', 'adsense' )				=> $iptcparse,
	        __( 'PHP XML support', 'adsense' )				=> $xml_parser_create,
			__( 'Site URL', 'adsense' )						=> $site_url,
			__( 'Home URL', 'adsense' )						=> $home_url,
			__( 'WordPress Version', 'adsense' )			=> $wp_version,
			__( 'WordPress DB Version', 'adsense' )			=> $db_version,
			__( 'Multisite', 'adsense' )					=> $multisite,
			__( 'Active Theme', 'adsense' )					=> $theme['Name'].' '.$theme['Version']
		);
		foreach ( $all_plugins as $path => $plugin ) {
			if ( is_plugin_active( $path ) ) {
				$system_info['active_plugins'][ $plugin['Name'] ] = $plugin['Version'];
			} else {
				$system_info['inactive_plugins'][ $plugin['Name'] ] = $plugin['Version'];
			}
		} 

		if ( ( isset( $_REQUEST['bwsmn_form_submit'] ) && check_admin_referer( plugin_basename(__FILE__), 'bwsmn_nonce_submit' ) ) ||
			 ( isset( $_REQUEST['bwsmn_form_submit_custom_email'] ) && check_admin_referer( plugin_basename(__FILE__), 'bwsmn_nonce_submit_custom_email' ) ) ) {
			if ( isset( $_REQUEST['bwsmn_form_email'] ) ) {
				$bwsmn_form_email = trim( $_REQUEST['bwsmn_form_email'] );
				if( $bwsmn_form_email == "" || !preg_match( "/^((?:[a-z0-9']+(?:[a-z0-9\-_\.']+)?@[a-z0-9]+(?:[a-z0-9\-\.]+)?\.[a-z]{2,5})[, ]*)+$/i", $bwsmn_form_email ) ) {
					$error = __( "Please enter a valid email address.", 'adsense' );
				} else {
					$email = $bwsmn_form_email;
					$bwsmn_form_email = '';
					$message = __( 'Email with system info is sent to ', 'adsense' ) . $email;			
				}
			} else {
				$email = 'plugin_system_status@bestwebsoft.com';
				$message = __( 'Thank you for contacting us.', 'adsense' );
			}

			if ( $error == '' ) {
				$headers  = 'MIME-Version: 1.0' . "\n";
				$headers .= 'Content-type: text/html; charset=utf-8' . "\n";
				$headers .= 'From: ' . get_option( 'admin_email' );
				$message_text = '<html><head><title>System Info From ' . $home_url . '</title></head><body>
				<h4>Environment</h4>
				<table>';
				foreach ( $system_info['system_info'] as $key => $value ) {
					$message_text .= '<tr><td>'. $key .'</td><td>'. $value .'</td></tr>';	
				}
				$message_text .= '</table>
				<h4>Active Plugins</h4>
				<table>';
				foreach ( $system_info['active_plugins'] as $key => $value ) {	
					$message_text .= '<tr><td scope="row">'. $key .'</td><td scope="row">'. $value .'</td></tr>';	
				}
				$message_text .= '</table>
				<h4>Inactive Plugins</h4>
				<table>';
				foreach ( $system_info['inactive_plugins'] as $key => $value ) {
					$message_text .= '<tr><td scope="row">'. $key .'</td><td scope="row">'. $value .'</td></tr>';
				}
				$message_text .= '</table></body></html>';
				$result = wp_mail( $email, 'System Info From ' . $home_url, $message_text, $headers );
				if ( $result != true )
					$error = __( "Sorry, email message could not be delivered.", 'adsense' );
			}
		}
		?><div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2><?php echo $title;?></h2>
			<div class="updated fade" <?php if( !( isset( $_REQUEST['bwsmn_form_submit'] ) || isset( $_REQUEST['bwsmn_form_submit_custom_email'] ) ) || $error != "" ) echo "style=\"display:none\""; ?>><p><strong><?php echo $message; ?></strong></p></div>
			<div class="error" <?php if ( "" == $error ) echo "style=\"display:none\""; ?>><p><strong><?php echo $error; ?></strong></p></div>
			<h3 style="color: blue;"><?php _e( 'Pro plugins', 'adsense' ); ?></h3>
			<?php if( 0 < $count_activate_pro ) { ?>
			<div style="padding-left:15px;">
				<h4><?php _e( 'Activated plugins', 'adsense' ); ?></h4>
				<?php foreach ( $array_activate_pro as $activate_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $activate_plugin["title"]; ?></div> <p><a href="<?php echo $activate_plugin["link"]; ?>" target="_blank"><?php echo __( "Read more", 'adsense' ); ?></a> <a href="<?php echo $activate_plugin["url"]; ?>"><?php echo __( "Settings", 'adsense' ); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( 0 < $count_install_pro ) { ?>
			<div style="padding-left:15px;">
				<h4><?php _e( 'Installed plugins', 'adsense' ); ?></h4>
				<?php foreach ( $array_install_pro as $install_plugin) { ?>
				<div style="float:left; width:200px;"><?php echo $install_plugin["title"]; ?></div> <p><a href="<?php echo $install_plugin["link"]; ?>" target="_blank"><?php echo __( "Read more", 'adsense' ); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( 0 < $count_recomend_pro ) { ?>
			<div style="padding-left:15px;">
				<h4><?php _e( 'Recommended plugins', 'adsense' ); ?></h4>
				<?php foreach ( $array_recomend_pro as $recomend_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $recomend_plugin["title"]; ?></div> <p><a href="<?php echo $recomend_plugin["link"]; ?>" target="_blank"><?php echo __( "Read more", 'adsense' ); ?></a> <a href="<?php echo $recomend_plugin["href"]; ?>" target="_blank"><?php echo __( "Purchase", 'adsense' ); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<br />
			<h3 style="color: green"><?php _e( 'Free plugins', 'adsense' ); ?></h3>
			<?php if( 0 < $count_activate ) { ?>
			<div style="padding-left:15px;">
				<h4><?php _e( 'Activated plugins', 'adsense' ); ?></h4>
				<?php foreach( $array_activate as $activate_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $activate_plugin["title"]; ?></div> <p><a href="<?php echo $activate_plugin["link"]; ?>" target="_blank"><?php echo __( "Read more", 'adsense' ); ?></a> <a href="<?php echo $activate_plugin["url"]; ?>"><?php echo __( "Settings", 'adsense' ); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( 0 < $count_install ) { ?>
			<div style="padding-left:15px;">
				<h4><?php _e( 'Installed plugins', 'adsense' ); ?></h4>
				<?php foreach ( $array_install as $install_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $install_plugin["title"]; ?></div> <p><a href="<?php echo $install_plugin["link"]; ?>" target="_blank"><?php echo __( "Read more", 'adsense' ); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( 0 < $count_recomend ) { ?>
			<div style="padding-left:15px;">
				<h4><?php _e( 'Recommended plugins', 'adsense' ); ?></h4>
				<?php foreach ( $array_recomend as $recomend_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $recomend_plugin["title"]; ?></div> <p><a href="<?php echo $recomend_plugin["link"]; ?>" target="_blank"><?php echo __( "Read more", 'adsense' ); ?></a> <a href="<?php echo $recomend_plugin["href"]; ?>" target="_blank"><?php echo __( "Download", 'adsense' ); ?></a> <a class="install-now" href="<?php echo get_bloginfo( "url" ) . $recomend_plugin["slug"]; ?>" title="<?php esc_attr( sprintf( __( 'Install %s' ), $recomend_plugin["title"] ) ) ?>" target="_blank"><?php echo __( 'Install now from wordpress.org', 'adsense' ) ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>	
			<br />		
			<span style="color: rgb(136, 136, 136); font-size: 10px;"><?php _e( 'If you have any questions, please contact us via', 'adsense' ); ?> <a href="http://support.bestwebsoft.com">http://support.bestwebsoft.com</a></span>
			<div id="poststuff" class="bws_system_info_mata_box">
				<div class="postbox">
					<div class="handlediv" title="Click to toggle">
						<br>
					</div>
					<h3 class="hndle">
						<span><?php _e( 'System status', 'adsense' ); ?></span>
					</h3>
					<div class="inside">
						<table class="bws_system_info">
							<thead><tr><th><?php _e( 'Environment', 'adsense' ); ?></th><td></td></tr></thead>
							<tbody>
							<?php foreach ( $system_info['system_info'] as $key => $value ) { ?>	
								<tr>
									<td scope="row"><?php echo $key; ?></td>
									<td scope="row"><?php echo $value; ?></td>
								</tr>	
							<?php } ?>
							</tbody>
						</table>
						<table class="bws_system_info">
							<thead><tr><th><?php _e( 'Active Plugins', 'adsense' ); ?></th><th></th></tr></thead>
							<tbody>
							<?php foreach ( $system_info['active_plugins'] as $key => $value ) { ?>	
								<tr>
									<td scope="row"><?php echo $key; ?></td>
									<td scope="row"><?php echo $value; ?></td>
								</tr>	
							<?php } ?>
							</tbody>
						</table>
						<table class="bws_system_info">
							<thead><tr><th><?php _e( 'Inactive Plugins', 'adsense' ); ?></th><th></th></tr></thead>
							<tbody>
							<?php foreach ( $system_info['inactive_plugins'] as $key => $value ) { ?>	
								<tr>
									<td scope="row"><?php echo $key; ?></td>
									<td scope="row"><?php echo $value; ?></td>
								</tr>	
							<?php } ?>
							</tbody>
						</table>
						<div class="clear"></div>						
						<form method="post" action="admin.php?page=bws_plugins">
							<p>			
								<input type="hidden" name="bwsmn_form_submit" value="submit" />
								<input type="submit" class="button-primary" value="<?php _e( 'Send to support', 'adsense' ) ?>" />
								<?php wp_nonce_field( plugin_basename(__FILE__), 'bwsmn_nonce_submit' ); ?>		
							</p>		
						</form>				
						<form method="post" action="admin.php?page=bws_plugins">	
							<p>			
								<input type="hidden" name="bwsmn_form_submit_custom_email" value="submit" />						
								<input type="submit" class="button" value="<?php _e( 'Send to custom email &#187;', 'adsense' ) ?>" />
								<input type="text" value="<?php echo $bwsmn_form_email; ?>" name="bwsmn_form_email" />
								<?php wp_nonce_field( plugin_basename(__FILE__), 'bwsmn_nonce_submit_custom_email' ); ?>
							</p>				
						</form>						
					</div>
				</div>
			</div>
		</div>
	<?php }
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