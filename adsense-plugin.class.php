<?php

// Class of Google AdSense functions
class adsns
{
	var $page_title;  // title for options page
	var $menu_title;  // name in menu
	 
	// Constructor
	function adsns()
	{
		$this->adsns_get_options();
	}
	
	function adsns_get_options()
	{
	}
	
	// Number of views ads on a home page
	function adsns_home_postviews() {
		$options = get_option( 'adsns_sets' );  // Get an array of ad settings
		if( is_home() ) {
			$options['num_show'] += $options['max_homepostads'];  // Counting views
			update_option( 'adsns_sets', $options );
		}
	}
	
	// Number of views ads on a single page
	function adsns_single_postviews() {
		$options = get_option( 'adsns_sets' );
		if( is_single() ) {			
			$options['num_show']++;
			update_option( 'adsns_sets', $options );
		}
	}
	
	// Number of views footer ads
	function adsns_footer_postviews() {
		$options = get_option( 'adsns_sets' );
		if( !is_feed() ) {			
			$options['num_show']++;
			update_option( 'adsns_sets', $options );
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
		$codd = get_option( 'adsns_sets' );  // Get an array of ad settings
		$codd['code'] = stripslashes( $codd['code'] );
		$this->adsns_donate();  // Calling a donate function
		if ( ! is_feed() && is_single() ) {  // Checking if we are on a single page
			$content.= '<div id="end_post_ad" class="ads">' . $codd['code'] . '</div>';  // Adding an ad code on page
		}
		return $content;
	}
	
	// Show ads after comment form
	function adsns_end_comment_ad() {
		$codd = get_option( 'adsns_sets' );
		$codd['code'] = stripslashes( $codd['code'] );
		$this->adsns_donate();
		if( ! is_feed() ) {
			echo '<div id="end_comment_ad" class="ads">' . $codd['code'] . '</div>';
		}
	}
	
	// Show ads after post on home page
	function adsns_end_home_post_ad( $content ) {
		global $adsns_count;
		$codd = get_option( 'adsns_sets' );		// Get an array of ad settings
		$codd['code'] = stripslashes( $codd['code'] );
		while ($count <= $max_ads) {
			$count+=$options['max_homepostads'];	// number of shows ads
			$current_count=$count;		// backup current number of shows ads
			$count=$max_ads+1;			// set count value out of range
			$this->adsns_donate();		// Calling a donate function
			if( ! is_feed() && is_home() && $adsns_count <= $codd['max_homepostads'] ) {
				$content.= '<div class="ads">' . $codd['code'] . '</div>';
			}
		}
		$count=$current_count;		// restore count value
		return $content;
	}
	
	// Show ads in footer
	function adsns_end_footer_ad() {
		$codd = get_option( 'adsns_sets' );
		$codd['code'] = stripslashes( $codd['code'] );
		$this->adsns_donate();
		if( ! is_feed() ) {
			echo '<div id="end_footer_ad" class="ads">' . $codd['code'] . '</div>';
		}
	}
	
	// Add 'BWS Plugins' menu at the left side in administer panel
	function adsns_add_admin_menu() {
		add_menu_page( __( 'BWS Plugins', 'adsense' ), __( 'BWS Plugins', 'adsense' ), 'manage_options', 'bws_plugins', 'bws_add_menu_render', plugins_url( "images/px.png", __FILE__ ), 1001 ); 
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
			'code2' => '',
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
		add_option( 'adsns_sets', $new_options );
	}
	
	// Donate settings
	function adsns_donate() {
		$options = get_option( 'adsns_sets' );
		if ( $options['donate'] > 0 ) {
			$don = intval( 100/$options['donate'] );  // Calculating number of donate ads for showing
			if ( $options['num_show'] % $don == 0) {  // Checking if now showing ad must be a donate ad
				$dimensions = explode( "x", $options['default'] );  // Calculating dimensions of ad block
				$options['donate_width'] = $dimensions[0];		// Width
				$options['donate_height'] = $dimensions[1];		// Height
				$don_code = '<script type="text/javascript">
							google_ad_client = "pub-' .$options['donate_id']. '";
							google_ad_width = ' .$options['donate_width']. ';
							google_ad_height = ' .$options['donate_height']. ';
							google_ad_format = "' .$options['default']. '_as";
							google_ad_type = "text";
							google_color_border = "' .$options['border']. '";
							google_color_bg = "' .$options['background']. '";
							google_color_link = "' .$options['title']. '";
							google_color_text = "' .$options['text']. '";
							google_color_url = "' .$options['url']. '";
							</script><script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';			
				$options['code'] = $don_code;
				update_option( 'adsns_sets', $options );
			}
			else $options['code'] = $options['code2'];
			update_option( 'adsns_sets', $options );
		}
	}
	
	// Function fo deactivation
	function adsns_deactivate()
	{
	}

	// Saving settings
	function adsns_settings_page()
	{
		$options = get_option( 'adsns_sets' );
		echo '
		<div class="wrap">
		<div class="icon32 icon32-bws" id="icon-options-general"></div>
		<h2>' . $this->page_title . '</h2>
		';
		
		if ( isset( $_POST['adsns_update'] ) ) { ### if click on Save Changes button
			if ( strlen( $_POST['client_id'] ) > 0 ) {
				echo "<div class='updated'><p>".__("Options saved.", 'adsense')."</p></div>";
					
				if ( isset( $_POST['client_id'] ) ) { ## client
					$options['clientid'] = $_POST['client_id'];					
				}
				
				if ( isset( $_POST['mycode'] ) ) { ## ad code
					$id = $_POST['mycode'];
					if ( strlen($id)>0 ) {
						$options['code'] = $id;
						$options['code2'] = $id;
					}
				}
	 
				if ( isset( $_POST['homeAds'] ) ) { ## select
					$options['max_homepostads'] = $_POST['homeAds'];				
				}
				
				if ( isset( $_POST['adtypeselect'] ) ) { ## adtypeselect
					$options['adtypeselect'] = $_POST['adtypeselect'];								
				}
				
				if ( isset( $_POST['default'] ) ) { ## format
					$options['default'] = $_POST['default'];		
				}

				if ( isset( $_POST['image_only'] ) ) { ## format
					$options['image_only'] = $_POST['image_only'];	
				}
				
				if ( isset( $_POST['link_unit'] ) ) { ## format
					$options['link_unit'] = $_POST['link_unit'];	
				}
				
				if ( isset( $_POST['adtype'] ) ) { ## adtype
					$options['adtype'] = $_POST['adtype'];		
				}
				
				if ( isset( $_POST['corner_style'] ) ) { ## corner_style
					$options['corner_style'] = $_POST['corner_style'];
				}
				
				if ( isset( $_POST['pallete'] ) ) { ## pallete
					$options['pallete'] = $_POST['pallete'];	
				}
				
				if ( isset( $_POST['border'] ) ) { ## border
					$options['border'] = $_POST['border'];		
				}
				if ( isset( $_POST['title'] ) ) { ## title
					$options['title'] = $_POST['title'];		
				}
				if ( isset( $_POST['background'] ) ) { ## background
					$options['background'] = $_POST['background'];
				}
				if ( isset( $_POST['text'] ) ) { ## text
					$options['text'] = $_POST['text'];		
				}
				if ( isset( $_POST['url'] ) ) { ## url
					$options['url'] = $_POST['url'];		
				}
				
				if ( isset( $_POST['position'] ) ) { ## position
					$options['position'] = $_POST['position'];	
				}
				
				if ( isset( $_POST['donate'] ) ) { ## donate
					$options['donate'] = $_POST['donate'];	
				}
				update_option( 'adsns_sets', $options );				
			}
			else echo "<div class='error'><p>".__("Please enter your Publisher ID.", 'adsense')."</p></div>";
		} // Click on Save Changes button end	 
		$this->adsns_view_options_page();	 
		echo '</div>';
	}

	// Admin interface of plugin
	function adsns_view_options_page()
	{
		$options = get_option( 'adsns_sets' );
		?>
  	
		<form id="option" name="option" action="" method="post">
			<div class="settings_head"> 
				<label for="color"> <?php _e( 'Network', 'adsense' ); ?> </label>
			</div>
			<div class="settings_body" id="network">
				<label for="client_id" class="left" ><?php _e( 'Publisher  ID:', 'adsense' ); ?></label>
				<div class="right">
					<input type="hidden" id="client_id_val" name="client_id_val" value="<?php echo $options['clientid'] ?>" />
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
					<input type="hidden" id="adtype_val" value="<?php echo $options['adtype'] ?>">
					<input type="radio" name="adtype" checked="checked" id="ad_type1" value="adunit" />
					<label for="ad_type1"><?php _e( 'Ad unit', 'adsense' ); ?></label>
					<input type="hidden" id="adtypesel_val" value="<?php echo $options['adtypeselect'] ?>">
					<select id="adtypeselect" name ="adtypeselect" style="width: 168px; margin-left: 10px;">
						<option value="text_image"><?php _e( 'Text and image ads', 'adsense' ); ?></option>
						<option selected="selected" value="text"><?php _e( 'Text ads only (default)', 'adsense' ); ?></option>
						<option value="image"><?php _e( 'Image ads only', 'adsense' ); ?></option>
					</select>
					<br />
					<input type="radio" name="adtype" id="ad_type2" value="linkunit" />
					<label for="ad_type2"><?php _e( 'Link unit', 'adsense' ); ?></label>
				</div>
				<br />
				
				<label for="default" class="left"><?php _e( 'Format:', 'adsense' ); ?></label>
				<div class="right">
					<div id="def" style="visibility: visible;"> 
						<input type="hidden" id="default_val" value="<?php echo $options['default'] ?>">
						<select id="default" name="default">
							<optgroup label="Horizontal">
								<option value="728x90">728x90 Leaderboard</option>
								<option value="468x60">468x60 Banner</option>
								<option value="234x60">234x60 Half Banner</option>
							</optgroup>
							<optgroup label="Vertical">
								<option value="120x600">120x600 Skyscraper</option>
								<option value="160x600">160x600 Wide Skyscraper</option>
								<option value="120x240">120x240 Vertical Banner</option>
							</optgroup>
							<optgroup label="Square">
								<option value="336x280">336x280 Large Rectangle</option>
								<option value="300x250">300x250 Medium Rectangle</option>
								<option value="250x250">250x250 Square</option>
								<option value="200x200">200x200 Small Square</option>
								<option value="180x150">180x150 Small Rectangle</option>
								<option value="125x125">125x125 Button</option></optgroup>
						</select>
					</div>
				
					<div id="img_only" style="visibility: hidden;" class="right_img">
						<input type="hidden" id="image_only_val" value="<?php echo $options['image_only'] ?>">
						<select id="image_only" name="image_only">
							<optgroup label="Horizontal">
								<option value="728x90">728x90 Leaderboard</option>
								<option value="468x60">468x60 Banner</option>
							</optgroup>
							<optgroup label="Vertical">
								<option value="120x600">120x600 Skyscraper</option>
								<option value="160x600">160x600 Wide Skyscraper</option>
							</optgroup>
							<optgroup label="Square">
								<option value="336x280">336x280 Large Rectangle</option>
								<option value="300x250">300x250 Medium Rectangle</option>
								<option value="250x250">250x250 Square</option>
								<option value="200x200">200x200 Small Square</option>
							</optgroup>
						</select>
					</div>

					<div id="lnk_unit" style="visibility: hidden; margin-top: -28px" class="right">
						<input type="hidden" id="link_unit_val" value="<?php echo $options['link_unit'] ?>">
						<select id="link_unit" name="link_unit">
							<optgroup label="Horizontal">
								<option value="728x15">728x15</option>
								<option value="468x15">468x15</option>
							</optgroup>
							<optgroup label="Square">
								<option value="200x90">200x90</option>
								<option value="180x90">180x90</option>
								<option value="160x90">160x90</option>
								<option value="120x90">120x90</option>
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
					<input type="hidden" id="position_val" value="<?php echo $options['position'] ?>">
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
					<input type="hidden" id="homeads_val" name="homeads_val" value="<?php echo $options['max_homepostads'] ?>" />		
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
				<input type="hidden" id="border_val" value="<?php echo $options['border'] ?>">
				<input type="hidden" id="title_val" value="<?php echo $options['title'] ?>">
				<input type="hidden" id="background_val" value="<?php echo $options['background'] ?>">
				<input type="hidden" id="text_val" value="<?php echo $options['text'] ?>">
				<input type="hidden" id="url_val" value="<?php echo $options['url'] ?>">

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
					<input type="hidden" id="pallete_val" value="<?php echo $options['pallete'] ?>">
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
					<input type="hidden" id="corner_style_val" value="<?php echo $options['corner_style'] ?>">
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
					<input type="hidden" id="donate_val" value="<?php echo $options['donate'] ?>">
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