<?php

// Class of Google AdSense functions
class adsns
{
	var $page_title;  // title for options page
	var $menu_title;  // name in menu
	var $options;
	 
	// Constructor
	function adsns()
	{
		$this->options = get_option( 'adsns_settings' );
		$this->options['code'] = stripslashes( $this->options['code'] );
	}
	
	// Number of views ads on a home page
	function adsns_home_postviews() {
		/*if( is_home() ) {
			$this->options['num_show'] += $this->options['max_homepostads'];  // Counting views
			update_option( 'adsns_settings', $this->options );
		}*/
	}
	
	// Number of views ads on a single page
	function adsns_single_postviews() {
		if( is_single() ) {			
			$this->options['num_show']++;
			update_option( 'adsns_settings', $this->options );
		}
	}
	
	// Number of views footer ads
	function adsns_footer_postviews() {
		if( !is_feed() ) {			
			$this->options['num_show']++;
			update_option( 'adsns_settings', $this->options );
		}
	}
	
	// Number of posts on home page
	function adsns_post_count( $content ) {
		global $adsns_count;
		$adsns_count++;
		return $content;
	}	
	
	// Show ads after post on a single page
	function adsns_end_post_ad( $content ) {
		$this->adsns_donate();  // Calling a donate function
		if ( ! is_feed() && is_single() ) {  // Checking if we are on a single page
			$content.= '<div id="end_post_ad" class="ads">' . $this->options['code'] . '</div>';  // Adding an ad code on page
		}
		return $content;
	}
	
	// Show ads after comment form
	function adsns_end_comment_ad() {
		$this->adsns_donate();
		if( ! is_feed() ) {
			echo '<div id="end_comment_ad" class="ads">' . $this->options['code'] . '</div>';
		}
	}
	
	// Show ads after post on home page
	function adsns_end_home_post_ad( $content ) {
		global $adsns_count;
		$current_count	= $adsns_count;		// backup current number of shows ads
		while ( $adsns_count <= $this->options[ 'max_ads' ] && $adsns_count <= $this->options['max_homepostads'] ) {
			$adsns_count		+= $this->options[ 'max_homepostads' ];	// number of shows ads
			//$adsns_count					= $this->options[ 'max_ads' ] + 1;			// set count value out of range
			$this->adsns_donate();		// Calling a donate function
			if( ! is_feed() && is_home() ) {
				$content .= '<div class="ads">' . $this->options['code'] . '</div>';
			}
		}
		$this->options['num_show'] ++;  // Counting views
		update_option( 'adsns_settings', $this->options );
		$adsns_count = $current_count;		// restore count value
		return $content;
	}
	
	// Show ads in footer
	function adsns_end_footer_ad() {
		$this->adsns_donate();
		if( ! is_feed() ) {
			echo '<div id="end_footer_ad" class="ads">' . $this->options['code'] . '</div>';
		}
	}
	
	// Add 'BWS Plugins' menu at the left side in administer panel
	function adsns_add_admin_menu() {
		add_menu_page( 'BWS Plugins', 'BWS Plugins', 'manage_options', 'bws_plugins', 'bws_add_menu_render', plugins_url( "images/px.png", __FILE__ ), 1001 ); 
		add_submenu_page( 'bws_plugins', __( 'AdSense Options', 'adsense'), __( 'AdSense', 'adsense' ), 'manage_options', "adsense-plugin.php", array( $this, 'adsns_settings_page' ) );
	}

	// Add a link for settings page
	function adsns_plugin_action_links( $links, $file ) {
		global $this_adsns_plugin;
		if ( $file == $this_adsns_plugin ){
			$settings_link = '<a href="admin.php?page=adsense-plugin.php">' . __( 'Settings', 'adsense' ) . '</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

	function adsns_register_plugin_links($links, $file) {
		global $this_adsns_plugin;
		if ($file == $this_adsns_plugin) {
			$links[] = '<a href="admin.php?page=adsense-plugin.php">' . __( 'Settings', 'adsense' ) . '</a>';
			$links[] = '<a href="http://wordpress.org/extend/plugins/adsense-plugin/faq/" target="_blank">' . __( 'FAQ', 'adsense' ) . '</a>';
			$links[] = '<a href="Mailto:plugin@bestwebsoft.com">' . __( 'Support', 'adsense' ) . '</a>';
		}
		return $links;
	}

	// Creating a default options for showing ads. Starts on plugin activation.
	function adsns_activate()	{
		$new_options = array(
			'num_show' => '0',
			'donate' => '0',
			'max_ads' => '3',
			'max_homepostads' => '1',
			'clientid' => '',
			'donate_id' => '1662250046693311',
			'adtypeselect' => 'text',			
			'donate_width' => '',
			'donate_height' => '',
			'default' => '468x60',
			'image_only' => '',
			'link_unit' => '',
			'adtype' => 'adunit',
			'corner_style' => 'none',
			'border' => '#FFFFFF',
			'title' => '#0000FF',
			'background' => '#FFFFFF',
			'text' => '#000000',
			'url' => '#008000',
			'palette' => 'Default Google pallete',
			'position' => 'homepostend',
			'code' => '	<script type="text/javascript">
						google_ad_client = "pub-1662250046693311";
						google_ad_width = 468;
						google_ad_height = 60;
						google_ad_format = "468x60_as";
						google_ad_type = "text";
						google_color_border = "#FFFFFF";
						google_color_bg = "#FFFFFF";
						google_color_link = "#0000FF";
						google_color_text = "#000000";
						google_color_url = "#008000";
						</script><script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>'
		);
		add_option( 'adsns_settings', $new_options );
	}
	
	// Donate settings
	function adsns_donate() {
		if ( $this->options['donate'] > 0 ) {
			$don = intval( 100/$this->options['donate'] );  // Calculating number of donate ads for showing
		}
		if ( $this->options['donate'] > 0 && $this->options['num_show'] % $don == 0) {  // Checking if now showing ad must be a donate ad
			$dimensions = explode( "x", $this->options['default'] );  // Calculating dimensions of ad block
			$this->options['donate_width'] = $dimensions[0];		// Width
			$this->options['donate_height'] = $dimensions[1];		// Height
			$don_code = '<script type="text/javascript">
						google_ad_client = "pub-' .$this->options['donate_id']. '";
						google_ad_width = ' .$this->options['donate_width']. ';
						google_ad_height = ' .$this->options['donate_height']. ';
						google_ad_format = "' .$this->options['default']. '_as";
						google_ad_type = "text";
						google_color_border = "' .$this->options['border']. '";
						google_color_bg = "' .$this->options['background']. '";
						google_color_link = "' .$this->options['title']. '";
						google_color_text = "' .$this->options['text']. '";
						google_color_url = "' .$this->options['url']. '";
						</script><script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';			
			$this->options['code'] = $don_code;
			update_option( 'adsns_settings', $this->options );
		}
		else {
			if( $this->options['adtype'] == 'ad_unit' ) {
				if($this->options['adtypeselect'] == 'default_image')
					$adtypeselect = 'default';
				else 
					$adtypeselect = $this->options['adtypeselect'];
				$dimensions = explode( "x", $this->options[ $adtypeselect ] );  // Calculating dimensions of ad block
				$format = $this->options[ $adtypeselect ];
				$format .= '_as';
				switch($this->options['adtypeselect']) {
						case 'image_only':
							$type = 'google_ad_type = "image";';
							break;
						case 'default_image':
							$type = 'google_ad_type = "text_image";';
							break;
						default:
							$type = 'google_ad_type = "text";';
							break;
				}
			}
			else {
				$dimensions = explode( "x", $this->options[ $this->options['adtype'] ] );  // Calculating dimensions of ad block
				$format = $this->options[ $this->options['adtype'] ];
				$format .= '_0ads_al';
				$type = '';
			}
			$this->options['donate_width'] = $dimensions[0];		// Width
			$this->options['donate_height'] = $dimensions[1];		// Height
			$don_code = '<script type="text/javascript">
					google_ad_client = "pub-' .$this->options['clientid'] . '";
					google_ad_width = ' . $this->options['donate_width'] . ';
					google_ad_height = ' . $this->options['donate_height'] . ';
					google_ad_format = "' . $format . '";
					' . $type . '
					google_color_border = "' . $this->options['border'] . '";
					google_color_bg = "' . $this->options['background'] . '";
					google_color_link = "' . $this->options['title'] . '";
					google_color_text = "' . $this->options['text'] . '";
					google_color_url = "' . $this->options['url'] . '";
					</script><script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';			
			$this->options['code'] = $don_code;
			update_option( 'adsns_settings', $this->options );
		}
	}
	
	// Function fo deactivation
	function adsns_deactivate()
	{
	}

	// Saving settings
	function adsns_settings_page()
	{
		echo '
		<div class="wrap">
		<div class="icon32 icon32-bws" id="icon-options-general"></div>
		<h2>' . $this->page_title . '</h2>
		';
		
		if ( isset( $_REQUEST['adsns_update'] ) ) { ### if click on Save Changes button
			if ( strlen( $_REQUEST['client_id'] ) > 0 ) {
				echo "<div class='updated'><p>".__( "Options saved.", 'adsense' )."</p></div>";
					
				if ( isset( $_REQUEST['client_id'] ) ) { ## client
					$this->options['clientid'] = $_REQUEST['client_id'];					
				}
				
				if ( isset( $_REQUEST['mycode'] ) ) { ## ad code
					$id = $_REQUEST['mycode'];
					if ( strlen($id)>0 ) {
						$this->options['code'] = $id;
					}
				}
	 
				if ( isset( $_REQUEST['homeAds'] ) ) { ## select
					$this->options['max_homepostads'] = $_REQUEST['homeAds'];				
				}
				
				if ( isset( $_REQUEST['adtypeselect'] ) ) { ## adtypeselect
					$this->options['adtypeselect'] = $_REQUEST['adtypeselect'];								
				}
				else {
					$this->options['adtypeselect'] = '';	
				}
				
				if ( isset( $_REQUEST['default'] ) ) { ## format
					$this->options['default'] = $_REQUEST['default'];		
				}
				else {
					$this->options['default'] = '';	
				}

				if ( isset( $_REQUEST['image_only'] ) ) { ## format
					$this->options['image_only'] = $_REQUEST['image_only'];	
				}
				else {
					$this->options['image_only'] = '';	
				}
				
				if ( isset( $_REQUEST['link_unit'] ) ) { ## format
					$this->options['link_unit'] = $_REQUEST['link_unit'];	
				}
				else {
					$this->options['link_unit'] = '';	
				}
				
				if ( isset( $_REQUEST['adtype'] ) ) { ## adtype
					$this->options['adtype'] = $_REQUEST['adtype'];		
				}
				
				if ( isset( $_REQUEST['corner_style'] ) ) { ## corner_style
					$this->options['corner_style'] = $_REQUEST['corner_style'];
				}
				
				if ( isset( $_REQUEST['pallete'] ) ) { ## pallete
					$this->options['pallete'] = $_REQUEST['pallete'];	
				}
				
				if ( isset( $_REQUEST['border'] ) ) { ## border
					$this->options['border'] = $_REQUEST['border'];		
				}
				if ( isset( $_REQUEST['title'] ) ) { ## title
					$this->options['title'] = $_REQUEST['title'];		
				}
				if ( isset( $_REQUEST['background'] ) ) { ## background
					$this->options['background'] = $_REQUEST['background'];
				}
				if ( isset( $_REQUEST['text'] ) ) { ## text
					$this->options['text'] = $_REQUEST['text'];		
				}
				if ( isset( $_REQUEST['url'] ) ) { ## url
					$this->options['url'] = $_REQUEST['url'];		
				}
				
				if ( isset( $_REQUEST['position'] ) ) { ## position
					$this->options['position'] = $_REQUEST['position'];	
				}
				
				if ( isset( $_REQUEST['donate'] ) ) { ## donate
					$this->options['donate'] = $_REQUEST['donate'];	
				}
				if( $this->options['adtype'] == 'ad_unit' ) {
					if($this->options['adtypeselect'] == 'default_image')
						$adtypeselect = 'default';
					else 
						$adtypeselect = $this->options['adtypeselect'];
					$dimensions = explode( "x", $this->options[ $adtypeselect ] );  // Calculating dimensions of ad block
					$format = $this->options[ $adtypeselect ];
					$format .= '_as';
					switch($this->options['adtypeselect']) {
							case 'image_only':
								$type = 'google_ad_type = "image";';
								break;
							case 'default_image':
								$type = 'google_ad_type = "text_image";';
								break;
							default:
								$type = 'google_ad_type = "text";';
								break;
					}
				}
				else {
					$dimensions = explode( "x", $this->options[ $this->options['adtype'] ] );  // Calculating dimensions of ad block
					$format = $this->options[ $this->options['adtype'] ];
					$format .= '_0ads_al';
					$type = '';
				}
				$this->options['donate_width'] = $dimensions[0];		// Width
				$this->options['donate_height'] = $dimensions[1];		// Height
				$don_code = '<script type="text/javascript">
						google_ad_client = "pub-' .$this->options['clientid'] . '";
						google_ad_width = ' . $this->options['donate_width'] . ';
						google_ad_height = ' . $this->options['donate_height'] . ';
						google_ad_format = "' . $format . '";
						' . $type . '
						google_color_border = "' . $this->options['border'] . '";
						google_color_bg = "' . $this->options['background'] . '";
						google_color_link = "' . $this->options['title'] . '";
						google_color_text = "' . $this->options['text'] . '";
						google_color_url = "' . $this->options['url'] . '";
						</script><script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';			
				$this->options['code'] = $don_code;
				update_option( 'adsns_settings', $this->options );				
			}
			else echo "<div class='error'><p>" . __( "Please enter your Publisher ID.", 'adsense' ) . "</p></div>";
		} // Click on Save Changes button end	 
		$this->adsns_view_options_page();	 
		echo '</div>';
	}

	// Admin interface of plugin
	function adsns_view_options_page()
	{
		$this->options = get_option( 'adsns_settings' );
		?>
  	
		<form id="option" name="option" action="" method="post">
			<div class="settings_head"> 
				<label for="color"> <?php _e( 'Network', 'adsense' ); ?> </label>
			</div>
			<div class="settings_body" id="network">
				<label for="client_id" class="left" ><?php _e( 'Publisher  ID:', 'adsense' ); ?></label>
				<div class="right">
					<input type="hidden" id="client_id_val" name="client_id_val" value="<?php echo $this->options['clientid'] ?>" />
					<input type="text" id="client_id" name="client_id" class ="positive-integer" size="20" maxlength="16" />
					<br />
					<div style="width: 250px; padding-left: 2px;">
						<span class="description"><?php _e( 'Publisher ID is the unique identifer of', 'adsense' ); ?> <a href="https://www.google.com/adsense"><?php _e( 'your account', 'adsense' ); ?></a> <?php _e( 'at Google AdSense.', 'adsense' ); ?></span>
					</div>
				</div>
			</div>
			
			<div class="settings_head"> 			
				<label for="adtype"><?php _e( 'Ad Type &amp; Format', 'adsense' ); ?></label>	
			</div>	
			<div class="settings_body">
				<label for="adtype" class="left"><?php _e( 'Type:', 'adsense' ); ?></label>
				<div class="right">
					<input type="hidden" id="adtype_val" value="<?php echo $this->options['adtype'] ?>">
					<input type="radio" name="adtype" id="ad_type1" value="ad_unit" <?php if( $this->options['adtype'] == 'ad_unit' || $this->options['adtype'] == 'adunit') echo 'checked="checked"'; ?>/>
					<label for="ad_type1"><?php _e( 'Ad unit', 'adsense' ); ?></label>
					<input type="hidden" id="adtypesel_val" value="<?php echo $this->options['adtypeselect'] ?>">					
					<select id="adtypeselect" name ="adtypeselect" style="width: 168px; margin-left: 10px;">
						<option value="default_image" <?php if( $this->options['adtypeselect'] == 'default_image' || $this->options['adtypeselect'] == 'text_image') echo 'selected="selected"'; ?>><?php _e( 'Text and image ads', 'adsense' ); ?></option>
						<option value="default" <?php if( $this->options['adtypeselect'] == 'default' || $this->options['adtypeselect'] == 'text') echo 'selected="selected"'; ?>><?php _e( 'Text ads only (default)', 'adsense' ); ?></option>
						<option value="image_only" <?php if( $this->options['adtypeselect'] == 'image_only' || $this->options['adtypeselect'] == 'image') echo 'selected="selected"'; ?>><?php _e( 'Image ads only', 'adsense' ); ?></option>
					</select>
					<br />
					<input type="radio" name="adtype" id="ad_type2" value="link_unit" <?php if( $this->options['adtype'] == 'link_unit' ) echo 'checked="checked"'; ?>/>
					<label for="ad_type2"><?php _e( 'Link unit', 'adsense' ); ?></label>
				</div>
				<br />
				
				<label for="default" class="left"><?php _e( 'Format:', 'adsense' ); ?></label>
				<div class="right">
					<div id="def" <?php if($this->options['adtype'] == 'ad_unit' && ( $this->options['adtypeselect'] == 'default' || $this->options['adtypeselect'] == 'default_image' ) || $this->options['adtypeselect'] == 'text' || $this->options['adtypeselect'] == 'text_image' ) echo 'style="visibility: visible;"'; else echo 'style="visibility: hidden;"'; ?>> 
						<input type="hidden" id="default_val" value="<?php echo $this->options['default'] ?>">
						<select id="default" name="default">
							<optgroup label="Horizontal">
								<option value="728x90" <?php if( $this->options['default'] == '728x90' ) echo 'selected="selected"'; ?>>728x90 Leaderboard</option>
								<option value="468x60" <?php if( $this->options['default'] == '468x60' ) echo 'selected="selected"'; ?>>468x60 Banner</option>
								<option value="234x60" <?php if( $this->options['default'] == '234x60' ) echo 'selected="selected"'; ?>>234x60 Half Banner</option>
							</optgroup>
							<optgroup label="Vertical">
								<option value="120x600" <?php if( $this->options['default'] == '120x600' ) echo 'selected="selected"'; ?>>120x600 Skyscraper</option>
								<option value="160x600" <?php if( $this->options['default'] == '160x600' ) echo 'selected="selected"'; ?>>160x600 Wide Skyscraper</option>
								<option value="120x240" <?php if( $this->options['default'] == '120x240' ) echo 'selected="selected"'; ?>>120x240 Vertical Banner</option>
							</optgroup>
							<optgroup label="Square">
								<option value="336x280" <?php if( $this->options['default'] == '336x280' ) echo 'selected="selected"'; ?>>336x280 Large Rectangle</option>
								<option value="300x250" <?php if( $this->options['default'] == '300x250' ) echo 'selected="selected"'; ?>>300x250 Medium Rectangle</option>
								<option value="250x250" <?php if( $this->options['default'] == '250x250' ) echo 'selected="selected"'; ?>>250x250 Square</option>
								<option value="200x200" <?php if( $this->options['default'] == '200x200' ) echo 'selected="selected"'; ?>>200x200 Small Square</option>
								<option value="180x150" <?php if( $this->options['default'] == '180x150' ) echo 'selected="selected"'; ?>>180x150 Small Rectangle</option>
								<option value="125x125" <?php if( $this->options['default'] == '125x125' ) echo 'selected="selected"'; ?>>125x125 Button</option></optgroup>
						</select>
					</div>
				
					<div id="img_only" <?php if($this->options['adtype'] == 'ad_unit' && ( $this->options['adtypeselect'] == 'image_only' || $this->options['adtypeselect'] == 'image' ) ) echo 'style="visibility: visible;"'; else echo 'style="visibility: hidden;"'; ?> class="right_img">
						<input type="hidden" id="image_only_val" value="<?php echo $this->options['image_only'] ?>">
						<select id="image_only" name="image_only">
							<optgroup label="Horizontal">
								<option value="728x90" <?php if( $this->options['image_only'] == '728x90' ) echo 'selected="selected"'; ?>>728x90 Leaderboard</option>
								<option value="468x60" <?php if( $this->options['image_only'] == '468x60' ) echo 'selected="selected"'; ?>>468x60 Banner</option>
							</optgroup>
							<optgroup label="Vertical">
								<option value="120x600" <?php if( $this->options['image_only'] == '120x600' ) echo 'selected="selected"'; ?>>120x600 Skyscraper</option>
								<option value="160x600" <?php if( $this->options['image_only'] == '160x600' ) echo 'selected="selected"'; ?>>160x600 Wide Skyscraper</option>
							</optgroup>
							<optgroup label="Square">
								<option value="336x280" <?php if( $this->options['image_only'] == '336x280' ) echo 'selected="selected"'; ?>>336x280 Large Rectangle</option>
								<option value="300x250" <?php if( $this->options['image_only'] == '300x250' ) echo 'selected="selected"'; ?>>300x250 Medium Rectangle</option>
								<option value="250x250" <?php if( $this->options['image_only'] == '250x250' ) echo 'selected="selected"'; ?>>250x250 Square</option>
								<option value="200x200" <?php if( $this->options['image_only'] == '200x200' ) echo 'selected="selected"'; ?>>200x200 Small Square</option>
							</optgroup>
						</select>
					</div>

					<div id="lnk_unit" <?php if($this->options['adtype'] == 'link_unit' ) echo 'style="visibility: visible;margin-top: -28px"'; else echo 'style="visibility: hidden;margin-top: -28px"'; ?> class="right">
						<input type="hidden" id="link_unit_val" value="<?php echo $this->options['link_unit'] ?>">
						<select id="link_unit" name="link_unit">
							<optgroup label="Horizontal">
								<option value="728x15" <?php if( $this->options['link_unit'] == '728x15' ) echo 'selected="selected"'; ?>>728x15</option>
								<option value="468x15" <?php if( $this->options['link_unit'] == '468x15' ) echo 'selected="selected"'; ?>>468x15</option>
							</optgroup>
							<optgroup label="Square">
								<option value="200x90" <?php if( $this->options['link_unit'] == '200x90' ) echo 'selected="selected"'; ?>>200x90</option>
								<option value="180x90" <?php if( $this->options['link_unit'] == '180x90' ) echo 'selected="selected"'; ?>>180x90</option>
								<option value="160x90" <?php if( $this->options['link_unit'] == '160x90' ) echo 'selected="selected"'; ?>>160x90</option>
								<option value="120x90" <?php if( $this->options['link_unit'] == '120x90' ) echo 'selected="selected"'; ?>>120x90</option>
							</optgroup>
						</select>
					</div>
				</div>
			</div>
							
			<div class="settings_head">
				<label for="position"><?php _e( 'Position &amp; Numbers of Ads', 'adsense' ); ?></label>
			</div>
			<div class="settings_body" id="pos_num">
				<label for="position" class="left"><?php _e( 'Position:', 'adsense' ); ?></label>
				<div class="right">
					<input type="hidden" id="position_val" value="<?php echo $this->options['position'] ?>">
					<select name="position" id="position">
						<option value="postend"><?php _e( 'After post text(Single page)', 'adsense' ); ?></option>
						<option value="homepostend"><?php _e( 'After post text(Home page)', 'adsense' ); ?></option>
						<option value="commentform"><?php _e( 'After comment form', 'adsense' ); ?></option>
						<option value="footer"><?php _e( 'Before footer', 'adsense' ); ?></option>
					</select>
				</div>
				<br />
				
				<label for="homeAds" class="left"><?php _e( 'Number of Ads:', 'adsense' ); ?></label>
				<div class="right">
					<input type="hidden" id="homeads_val" name="homeads_val" value="<?php echo $this->options['max_homepostads'] ?>" />		
					<select name="homeAds" id="homeAds" style="width: 40px;" />
						<option value="1" selected="selected">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
					</select> 
				</div>
				<div style="width: 265px; padding-left: 2px;">
					<span class="description"><?php _e( 'Number of ads after post text on your home page.', 'adsense' ); ?></span>
				</div>
			</div>
				
			<div class="settings_head"> 
				<label for="color"> <?php _e( 'Visualisation', 'adsense' ); ?> </label>
			</div>
			<div class="settings_body" id="visual">
				<label for="Border" class="left"><?php _e( 'Color:', 'adsense' ); ?></label>
				<input type="hidden" id="border_val" value="<?php echo $this->options['border'] ?>">
				<input type="hidden" id="title_val" value="<?php echo $this->options['title'] ?>">
				<input type="hidden" id="background_val" value="<?php echo $this->options['background'] ?>">
				<input type="hidden" id="text_val" value="<?php echo $this->options['text'] ?>">
				<input type="hidden" id="url_val" value="<?php echo $this->options['url'] ?>">

				<table cellpadding="0" cellspacing="0" border="0" class="right">
					<tr class="paddings">
						<td align="right">
							<label for="Border"><?php _e( 'Border', 'adsense' ); ?>&nbsp;&nbsp;&nbsp;</label>
						</td>
						<td>
							<input type="text" id="Border" size="7" maxlength="7" name="border" />
							<div id="colorpicker1" class="col_pal" ></div>					
							<div id="colorpicker2" class="col_pal" ></div>					
							<div id="colorpicker3" class="col_pal" ></div>				
							<div id="colorpicker4" class="col_pal" ></div>				
							<div id="colorpicker5" class="col_pal" ></div>					
						</td>
						<td>
						</td>
					</tr>
					<tr class="paddings">
						<td align="right">
							<label for="Title"><?php _e( 'Title', 'adsense' ); ?>&nbsp;&nbsp;&nbsp;</label>
						</td>
						<td>
							<input type="text" id="Title" size="7" maxlength="7" name="title" />
						</td>
						<td>
						</td>
					</tr>
					<tr class="paddings">
						<td align="right">
							<label for="Background"><?php _e( 'Background', 'adsense' ); ?>&nbsp;&nbsp;&nbsp;</label>
						</td>
						<td>
							<input type="text" id="Background" size="7" maxlength="7" name="background" />
						</td>
					</tr>
					<tr class="paddings">
						<td align="right">
							<label for="Text"><?php _e( 'Text', 'adsense' ); ?>&nbsp;&nbsp;&nbsp;</label>
						</td>
						<td>
							<input type="text" id="Text" size="7" maxlength="7" name="text" />
						</td>
					</tr>
					<tr class="paddings">
						<td align="right">
							<label for="URL"><?php _e( 'URL', 'adsense' ); ?>&nbsp;&nbsp;&nbsp;</label>
						</td>
						<td>
							<input type="text" id="URL" size="7" maxlength="7" name="url" />
						</td>
					</tr>
				</table>
				<br />
				
				<label for="pallete" class="left"><?php _e( 'Palette:', 'adsense' ); ?></label>
				<div class="right">
					<input type="hidden" id="pallete_val" value="<?php echo $this->options['pallete'] ?>">
					<select id="pallete" name="pallete">
						<optgroup label="Default Pallete">
							<option value="Default Google pallete" selected="selected"><?php _e( 'Default Google pallete', 'adsense' ); ?></option>
						</optgroup>
						<optgroup label="AdSense Pallete">
							<option value="Open Air"><?php _e( 'Open Air', 'adsense' ); ?></option>
							<option value="Seaside"><?php _e( 'Seaside', 'adsense' ); ?></option>
							<option value="Shadow"><?php _e( 'Shadow', 'adsense' ); ?></option>
							<option value="Blue Mix"><?php _e( 'Blue Mix', 'adsense' ); ?></option>
							<option value="Ink"><?php _e( 'Ink', 'adsense' ); ?></option>
							<option value="Graphite"><?php _e( 'Graphite', 'adsense' ); ?></option>
						</optgroup>
					</select>
				</div>
				<div style="width: 250px; padding-left: 2px;">
					<span class="description"><?php _e( 'This is a standard Google color palette.', 'adsense' ); ?></span>
				</div>
				<br />
				
				<label for="corner_style" class="left"><?php _e( 'Corner Style:', 'adsense' ); ?></label>
				<div class="right">
					<input type="hidden" id="corner_style_val" value="<?php echo $this->options['corner_style'] ?>">
					<select name="corner_style" id="corner_style">
						<option value="none" selected="selected"> <?php _e( 'Select corner style', 'adsense' ); ?> </option>
						<option value="0"> <?php _e( 'Square corners', 'adsense' ); ?> </option>
						<option value="6"> <?php _e( 'Slightly rounded corners', 'adsense' ); ?> </option>
						<option value="10"> <?php _e( 'Very rounded corners', 'adsense' ); ?> </option>
					</select>
				</div>
				<div style="width: 250px; padding-left: 2px;">
					<span class="description"><?php _e( 'Corner style property will help you to make your Ad rounded corners.', 'adsense' ); ?></span>
				</div>
			</div>
			
			<div class="settings_head">
				<label for="position"><?php _e( 'Donate', 'adsense' ); ?></label>
			</div>
			<div class="settings_body" id="donate_menu">
				<label for="donate" class="left"><?php _e( 'Donate us:', 'adsense' ); ?></label>
				<div class="right">
					<input type="hidden" id="donate_val" value="<?php echo $this->options['donate'] ?>">
					<input type="text" id="donate" size="2" maxlength="2" name="donate" style="padding-left: 10px; padding-right: 10px; text-align: center;" />%
					<br />
					<span class="description"><?php _e( 'Support us by Donating Ad Space.', 'adsense' ); ?></span>
					<br />
					<span class="description"><?php _e( 'Input percentage of ad slots to share [Default: 0%].', 'adsense' ); ?></span>
				</div>
			</div>

			<div style="position: absolute; margin-left: 700px; margin-top: -200px; visibility: hidden;"> 
				<textarea id="mycode" name="mycode" rows="15" cols="60"></textarea>
				<input type="button" id="generate" value="Generate!" />
			</div>
			<div style="margin-top: 25px;" >
				<input type="submit" class="button-primary" name="adsns_update" value="<?php _e('Save Changes') ?>" />
			</div>
		</form>		
	<?php		
	}

	// Including scripts and stylesheets for admin interface of plugin
	public function adsns_write_admin_head(){
		if( (is_admin() ) && (isset($_GET['page'])) && ($_GET['page'] == "adsense-plugin.php") ) {
			wp_register_script( 'adsns_admin_script', plugins_url( 'js/admin.js' , __FILE__ ) );
			wp_register_script( 'adsns_numeric_script', plugins_url( 'js/numeric.js' , __FILE__ ) );
			wp_register_script( 'adsns_farbtastic_script', plugins_url( 'farbtastic/farbtastic.js' , __FILE__ ) );
			
			wp_enqueue_script( 'adsns_admin_script' );
			wp_enqueue_script( 'adsns_numeric_script' );
			wp_enqueue_script( 'adsns_farbtastic_script' );
			
			wp_register_style( 'adsnsFarbtasticStylesheet', plugins_url( 'farbtastic/farbtastic.css' , __FILE__ ) ) ;
			wp_enqueue_style( 'adsnsFarbtasticStylesheet' );
		}
		wp_register_style( 'adsnsStylesheet', plugins_url( 'css/style.css' , __FILE__ ) );
		wp_enqueue_style( 'adsnsStylesheet' );
	}
	
	// Stylesheets for ads
	function adsns_head() {
		echo <<<EOF
		<style type="text/css">
		.ads {
			position: relative;
			text-align: center;		
		}
		</style>
EOF;
	}

} // class
?>