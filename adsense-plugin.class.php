<?php

if ( ! class_exists( 'adsns' ) ) {
	/* Class of Google AdSense functions */
	class adsns {
		var $adsns_plugin_info, $adsns_options, $adsns_adsense_api;

		function adsns_show_ads() {
			if ( ! $this->adsns_options ) {
				$this->adsns_activate();
			}
			/* Use Google AdSense API? */
			if ( $this->adsns_adsense_api == true ) {
				add_filter( 'the_content', array( $this, 'adsns_content' ) );
				add_filter( 'comment_id_fields', array( $this, 'adsns_comments' ) );
			} else {

				$this->adsns_options['code'] =	stripslashes( $this->adsns_options['code'] );
				$this->adsns_options['num_show'] = 0;
				update_option( 'adsns_settings', $this->adsns_options );

				/* Checking in what position we should show an ads */
				if ( 'postend' == $this->adsns_options['position'] ) { /* If we choose ad position after post(single page) */
					add_filter( 'the_content', array( $this, 'adsns_end_post_ad' ) ); /* Adding ad after post */
				} else if ( 'homepostend' == $this->adsns_options['position'] ) { /* If we choose ad position after post(home page) */
					add_filter( 'the_content', array( $this, 'adsns_end_home_post_ad' ) ); /* Adding ad after post */
				} else if ( 'homeandpostend' == $this->adsns_options['position'] ) { /* If we choose ad position after post(home page) */
					add_filter( 'the_content', array( $this, 'adsns_end_home_post_ad' ) ); /* Adding ad after post */
					add_filter( 'the_content', array( $this, 'adsns_end_post_ad' ) ); /* Adding ad after post */
				} else if ( 'commentform' == $this->adsns_options['position'] ) { /* If we choose ad position after comment form */
					add_filter( 'comment_id_fields', array( $this, 'adsns_end_comment_ad' ) ); /* Adding ad after comment form */
				} else if ( 'footer' == $this->adsns_options['position'] ) { /* If we choose ad position in a footer */
					add_filter( 'get_footer', array( $this, 'adsns_end_footer_ad' ) ); /* Adding footer ad */
				}
			}
			/* End checking */
		}

		/* Show ads after comment form */
		function adsns_end_comment_ad() {
			global $adsns_count;
			if ( ! is_feed() && $adsns_count < $this->adsns_options['max_ads'] && $adsns_count < $this->adsns_options['max_homepostads'] ) {
				echo '<div id="end_comment_ad" class="ads">' . $this->adsns_options['code'] . '</div>';
				$this->adsns_options['num_show'] ++;  /* Counting views */
				update_option( 'adsns_settings', $this->adsns_options );
				$adsns_count = $this->adsns_options['num_show'];
			}
		}

		/* Show ads after post on a single page */
		function adsns_end_post_ad( $content ) {
			global $adsns_count;
			if ( ! is_feed() && is_single() && $adsns_count < $this->adsns_options['max_ads'] && $adsns_count < $this->adsns_options['max_homepostads'] ) {  /* Checking if we are on a single page */
				$content.= '<div id="end_post_ad" class="ads">' . $this->adsns_options['code'] . '</div>';  /* Adding an ad code on page */
				$this->adsns_options['num_show'] ++;  /* Counting views */
				update_option( 'adsns_settings', $this->adsns_options );
				$adsns_count = $this->adsns_options['num_show'];
			}
			return $content;
		}

		/* Show ads after post on home page */
		function adsns_end_home_post_ad( $content ) {
			global $adsns_count;
			if ( $adsns_count < $this->adsns_options['max_ads'] && $adsns_count < $this->adsns_options['max_homepostads'] ) {
				if ( ! is_feed() && ( is_home() || is_front_page() ) ) {
					$content .= '<div class="ads">' . $this->adsns_options['code'] . '</div>';
					$this->adsns_options['num_show'] ++;  /* Counting views */
					update_option( 'adsns_settings', $this->adsns_options );
					$adsns_count = $this->adsns_options['num_show']; /* Restore count value */
				}
			}
			return $content;
		}

		/* Show ads in footer */
		function adsns_end_footer_ad() {
			global $adsns_count;
			if ( ! is_feed() && $adsns_count < $this->adsns_options['max_ads'] && $adsns_count < $this->adsns_options['max_homepostads'] ) {
				echo '<div id="end_footer_ad" class="ads">' . $this->adsns_options['code'] . '</div>';
				$this->adsns_options['num_show'] ++;  /* Counting views */
				update_option( 'adsns_settings', $this->adsns_options );
				$adsns_count = $this->adsns_options['num_show']; /* Restore count value */
			}
		}

		/* Add 'BWS Plugins' menu at the left side in administer panel */
		function adsns_add_admin_menu() {
			bws_add_general_menu( 'adsense-plugin/adsense-plugin.php' );
			add_submenu_page( 'bws_plugins', __( 'Google AdSense Settings', 'adsense' ), 'Google AdSense', 'manage_options', "adsense-plugin.php", array( $this, 'adsns_settings_page' ) );
		}

		/* Add a link for settings page */
		function adsns_plugin_action_links( $links, $file ) {
			if ( ! is_network_admin() ) {
				if ( $file == 'adsense-plugin/adsense-plugin.php' ) {
					$settings_link = '<a href="admin.php?page=adsense-plugin.php">' . __( 'Settings', 'adsense' ) . '</a>';
					array_unshift( $links, $settings_link );
				}
			}
			return $links;
		}

		function adsns_register_plugin_links( $links, $file ) {
			if ( $file == 'adsense-plugin/adsense-plugin.php' ) {
				if ( ! is_network_admin() )
					$links[]	=	'<a href="admin.php?page=adsense-plugin.php">' . __( 'Settings', 'adsense' ) . '</a>';
				$links[]	=	'<a href="http://wordpress.org/plugins/adsense-plugin/faq/" target="_blank">' . __( 'FAQ', 'adsense' ) . '</a>';
				$links[]	=	'<a href="http://support.bestwebsoft.com">' . __( 'Support', 'adsense' ) . '</a>';
			}
			return $links;
		}

		function adsns_plugin_init() {
			/* Internationalization */
			load_plugin_textdomain( 'adsense', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

			require_once( dirname( __FILE__ ) . '/bws_menu/bws_functions.php' );

			if ( empty( $this->adsns_plugin_info ) ) {
				if ( ! function_exists( 'get_plugin_data' ) )
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				$this->adsns_plugin_info = get_plugin_data( dirname( __FILE__ ) . '/adsense-plugin.php' );
			}

			/* Function check if plugin is compatible with current WP version */
			bws_wp_version_check( 'adsense-plugin/adsense-plugin.php', $this->adsns_plugin_info, '3.3' );

			/* Call register settings function */
			if ( ! is_admin() || ( isset( $_GET['page'] ) && "adsense-plugin.php" == $_GET['page'] ) ) {
				$this->adsns_activate();
			}


		}

		function adsns_plugin_admin_init() {
			global $bws_plugin_info;

			if ( isset( $_GET['page'] ) && "adsense-plugin.php" == $_GET['page'] ) {
				if ( ! session_id() ) {
					session_start();
				}
			}

			if ( ! isset( $bws_plugin_info ) || empty( $bws_plugin_info ) )
				$bws_plugin_info = array( 'id' => '80', 'version' => $this->adsns_plugin_info["Version"] );
		}

		/* Creating a default options for showing ads. Starts on plugin activation. */
		function adsns_activate() {
			global $adsns_count;

			if ( ! function_exists( 'get_plugin_data' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$this->adsns_plugin_info = get_plugin_data( dirname( __FILE__ ) . '/adsense-plugin.php' );

			$adsns_options_defaults = array(
				'plugin_option_version'	=>	$this->adsns_plugin_info["Version"],
				'widget_title'			=>	'',
				'use_new_api'			=> false
			);

			if ( ! get_option( 'adsns_settings' ) ) {
				$adsns_options_defaults['use_new_api'] = true;
				add_option( 'adsns_settings', $adsns_options_defaults );
			}

			$this->adsns_options = get_option( 'adsns_settings' );

			$adsns_count = 0; 	/* Number of posts on home page */

			/* Array merge incase this version has added new options */
			if ( ! isset( $this->adsns_options['plugin_option_version'] ) || $this->adsns_options['plugin_option_version'] != $this->adsns_plugin_info["Version"] ) {
				$this->adsns_options = array_merge( $adsns_options_defaults, $this->adsns_options );
				$this->adsns_options['plugin_option_version'] = $this->adsns_plugin_info["Version"];
				update_option( 'adsns_settings', $this->adsns_options );
			}

			$this->adsns_adsense_api = ( $this->adsns_options['use_new_api'] == true ) ? true : false;
		}

		/* Google Asense API */
		function adsns_client() {
			require_once( dirname( __FILE__ ) . '/google_api/autoload.php' );
			$client = new Google_Client();
			$client->setClientId( '903234641369-4mm0lqt76r0rracrdn2on3qrk6c554aa.apps.googleusercontent.com' );
			$client->setClientSecret( 'Twlx072svotXexK5rvqC5bb-' );
			$client->setScopes( array( 'https://www.googleapis.com/auth/adsense' ) );
			$client->setRedirectUri( 'urn:ietf:wg:oauth:2.0:oob' );
			$client->setAccessType( 'offline' );
			$client->setDeveloperKey( 'AIzaSyBa4vT_9do8e7Yxv88EXle6546nFVGLHI8' );
			$client->setApplicationName( $this->adsns_plugin_info['Name'] );
			return $client;
		}

		/* Show ads on the home page / single page / post / custom post / categories page / tags page via Google AdSense API */
		function adsns_content( $content ) {
			global $adsns_count;

			if ( ! is_feed() && ( is_home() || is_front_page() || is_category() || is_tag() ) ) {
				$adsns_count = empty( $adsns_count ) ? 0 : $adsns_count;
				if ( $adsns_count > 2 ) {
					return $content;
				}
				if ( is_home() || is_front_page() ) {
					$adsns_area = 'home';
				}
				if ( is_category() || is_tag() ) {
					$adsns_area = 'categories+tags';
				}
				if ( isset( $this->adsns_options['adunits'][ $adsns_area ] ) ) {
					$adsns_ad_units = $this->adsns_options['adunits'][ $adsns_area ];
					for ( $i = 0; $i < count( $adsns_ad_units ); $i++ ) {
						if ( isset( $adsns_ad_units[ $adsns_count ] ) ) {
							$adsns_ad_unit = $adsns_ad_units[ $adsns_count ];
							$adsns_ad_unit_id = $adsns_ad_unit['id'];
							$adsns_ad_unit_position = $adsns_ad_unit['position'];
							$adsns_ad_unit_code = htmlspecialchars_decode( $adsns_ad_unit['code'] );
							$adsns_count++;
							switch ( $adsns_ad_unit_position ) {
								case 'after':
									$adsns_ads = sprintf( '<div id="%s" class="ads ads_after">%s</div>', $adsns_ad_unit_id, $adsns_ad_unit_code );
									return $content . $adsns_ads;
									break;
								case 'before':
									$adsns_ads = sprintf( '<div id="%s" class="ads ads_before">%s</div>', $adsns_ad_unit_id, $adsns_ad_unit_code );
									return $adsns_ads . $content;
									break;
								default:
									return $content;
									break;
							}
						}
					}
				}
			}
			if ( ! is_feed() && ( is_single() || is_page() ) ) {
				if ( is_single() ) {
					$adsns_area = 'posts+custom_posts';
				}
				if ( is_page() ) {
					$adsns_area = 'pages';
				}
				if ( isset( $this->adsns_options['adunits'][ $adsns_area ] ) ) {
					$adsns_ad_units = $this->adsns_options['adunits'][ $adsns_area ];
					for ( $i = 0; $i < count( $adsns_ad_units ); $i++ ) {
						if ( isset( $adsns_ad_units[ $i ] ) ) {
							$adsns_ad_unit = $adsns_ad_units[ $i ];
							$adsns_ad_unit_id = $adsns_ad_unit['id'];
							$adsns_ad_unit_position = $adsns_ad_unit['position'];
							$adsns_ad_unit_code = htmlspecialchars_decode( $adsns_ad_unit['code'] );
							$adsns_count++;
							switch ( $adsns_ad_unit_position ) {
								case 'after':
									$adsns_ads = sprintf( '<div id="%s" class="ads ads_after">%s</div>', $adsns_ad_unit_id, $adsns_ad_unit_code );
									$content = $content . $adsns_ads;
									break;
								case 'before':
									$adsns_ads = sprintf( '<div id="%s" class="ads ads_before">%s</div>', $adsns_ad_unit_id, $adsns_ad_unit_code );
									$content = $adsns_ads . $content;
									break;
								default:
									break;
							}
						}
					}
				}
			}
			return $content;
		}

		/* Show ads after comment form via Google AdSense API */
		function adsns_comments( $content ) {
			$adsns_area = '';
			if ( is_single() ) {
				$adsns_area = 'posts+custom_posts';
			}

			if ( is_page() ) {
				$adsns_area = 'pages';
			}
			if ( isset( $this->adsns_options['adunits'][ $adsns_area ] ) ) {
				$adsns_ad_units = $this->adsns_options['adunits'][ $adsns_area ];
				for ( $i = 0; $i < count( $adsns_ad_units ); $i++ ) {
					if ( isset( $adsns_ad_units[ $i ] ) ) {
						$adsns_ad_unit = $adsns_ad_units[ $i ];
						$adsns_ad_unit_id = $adsns_ad_unit['id'];
						$adsns_ad_unit_position = $adsns_ad_unit['position'];
						$adsns_ad_unit_code = htmlspecialchars_decode( $adsns_ad_unit['code'] );
						if ( $adsns_ad_unit_position == 'commentform' ) {
							$content .= sprintf( '<div id="%s" class="ads ads_comments">%s</div>', $adsns_ad_unit_id, $adsns_ad_unit_code );
						}
					}
				}
			}
			return $content;
		}

		/* Main settings page */
		function adsns_settings_page() {

			if ( isset( $_POST['adsns_upgrade'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'adsns_nonce_name' ) ) {
				$adsns_new_options['plugin_option_version'] = $this->adsns_options['plugin_option_version'];
				$adsns_new_options['widget_title'] = $this->adsns_options['widget_title'];
				$adsns_new_options['use_new_api'] = true;
				$this->adsns_adsense_api = true;
				$this->adsns_options = $adsns_new_options;
				update_option( 'adsns_settings', $this->adsns_options );
			}

			$adsns_current_tab = ( isset( $_GET['tab'] ) ) ? urlencode( $_GET['tab'] ) : 'home';

			$adsns_form_action = $adsns_tab_url = '';

			if ( isset( $_GET ) ) {
				unset( $_GET['page'] );
				foreach ( $_GET as $action => $value ) {
					$adsns_form_action .= sprintf( '&%s=%s', $action, urlencode( $value ) );
				}
				$adsns_tab_url = preg_replace( '/&tab=[\w\d+]+/', '', $adsns_form_action );
			}

			$adsns_tabs = array(
				'home' => array(
					'tab' => array(
						'title' => __( 'Home page', 'adsense' ),
						'url'   => sprintf( 'admin.php?page=adsense-plugin.php%s', $adsns_tab_url )
					),
					'adunit_positions' => array(
						'after'       => __( 'After the content', 'adsense' ),
						'before'      => __( 'Before the content', 'adsense' )
					),
					'max_ads' => 3
				),
				'pages' => array(
					'tab' => array(
						'title' => __( 'Pages', 'adsense' ),
						'url'   => sprintf( 'admin.php?page=adsense-plugin.php&tab=pages%s', $adsns_tab_url )
					),
					'adunit_positions' => array(
						'after'       => __( 'After the content', 'adsense' ),
						'before'      => __( 'Before the content', 'adsense' ),
						'commentform' => __( 'Below the comment form', 'adsense' )
					),
					'max_ads' => 3
				),
				'posts+custom_posts' => array(
					'tab' => array(
						'title' => __( 'Posts / Custom posts', 'adsense' ),
						'url'   => sprintf( 'admin.php?page=adsense-plugin.php&tab=posts+custom_posts%s', $adsns_tab_url )
					),
					'adunit_positions' => array(
						'after'       => __( 'After the content', 'adsense' ),
						'before'      => __( 'Before the content', 'adsense' ),
						'commentform' => __( 'Below the comment form', 'adsense' )
					),
					'max_ads' => 3
				),
				'categories+tags' => array(
					'tab' => array(
						'title' => __( 'Categories / Tags', 'adsense' ),
						'url'   => sprintf( 'admin.php?page=adsense-plugin.php&tab=categories+tags%s', $adsns_tab_url )
					),
					'adunit_positions' => array(
						'after'       => __( 'After the content', 'adsense' ),
						'before'      => __( 'Before the content', 'adsense' )
					),
					'max_ads' => 3
				),
				'widget' => array(
					'tab' => array(
						'title' => __( 'Widget', 'adsense' ),
						'url'   => sprintf( 'admin.php?page=adsense-plugin.php&tab=widget%s', $adsns_tab_url )
					),
					'adunit_positions' => array(),
					'max_ads' => 1
				)
			);

			$adsns_tbl_data = array();

			$adsns_adunit_types = array(
				'TEXT'       => __( 'Text', 'adsense' ),
				'IMAGE'      => __( 'Image', 'adsense' ),
				'TEXT_IMAGE' => __( 'Text/Image', 'adsense' )
			);

			$adsns_adunit_statuses = array(
				'NEW'      => __( 'New', 'adsense' ),
				'ACTIVE'   => __( 'Active', 'adsense' ),
				'INACTIVE' => __( 'Inactive', 'adsense' )
			);

			$adsns_adunit_sizes = array(
				'RESPONSIVE' => __( 'Responsive', 'adsense' )
			);

			$adsns_client = $this->adsns_client();
			$adsns_blog_prefix = '_' . get_current_blog_id();

			if ( isset( $_POST['adsns_logout'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'adsns_nonce_name' ) ) {
				unset( $_SESSION[ 'adsns_authorization_code' . $adsns_blog_prefix ] );
				unset( $this->adsns_options['authorization_code'] );
				update_option( 'adsns_settings', $this->adsns_options );
			}

			if ( isset( $_POST['adsns_authorization_code'] ) && ! empty( $_POST['adsns_authorization_code'] ) && check_admin_referer( plugin_basename(__FILE__), 'adsns_nonce_name' ) ) {
				try {
					$adsns_client->authenticate( $_POST['adsns_authorization_code'] );
					$this->adsns_options['authorization_code'] = $_SESSION[ 'adsns_authorization_code' . $adsns_blog_prefix ] = $adsns_client->getAccessToken();
					update_option( 'adsns_settings', $this->adsns_options );
				} catch ( Exception $e ) {}
			}

			if ( ! isset( $_SESSION[ 'adsns_authorization_code' . $adsns_blog_prefix ] ) && isset( $this->adsns_options['authorization_code'] ) ) {
				$_SESSION[ 'adsns_authorization_code' . $adsns_blog_prefix ] = $this->adsns_options['authorization_code'];
			}

			if ( isset( $_SESSION[ 'adsns_authorization_code' . $adsns_blog_prefix ] ) ) {
				$adsns_client->setAccessToken( $_SESSION[ 'adsns_authorization_code' . $adsns_blog_prefix ] );
			}

			if ( $adsns_client->getAccessToken() ) {
				$adsns_adsense = new Google_Service_AdSense( $adsns_client );
				$adsns_adsense_accounts = $adsns_adsense->accounts;
				$adsns_adsense_adclients = $adsns_adsense->adclients;
				$adsns_adsense_adunits = $adsns_adsense->adunits;
				try {
					$adsns_list_accounts = $adsns_adsense_accounts->listAccounts()->getItems();
					$adsns_publisher_id = $adsns_list_accounts[0]['id'];
					try {
						$adsns_list_adclients = $adsns_adsense_adclients->listAdclients()->getItems();
						$adsns_ad_client = $adsns_list_adclients[0]['id'];
						try {
							$adsns_adunits = $adsns_adsense_adunits->listAdunits( $adsns_ad_client )->getItems();
							foreach ( $adsns_adunits as $adsns_adunit ) {
								$adsns_adunit_type = $adsns_adunit_types[ $adsns_adunit->getContentAdsSettings()->getType() ];
								$adsns_adunit_size = preg_replace( '/SIZE_([\d]+)_([\d]+)/', '$1x$2', $adsns_adunit->getContentAdsSettings()->getSize() );
								if ( array_key_exists( $adsns_adunit_size, $adsns_adunit_sizes ) ) {
									$adsns_adunit_size = $adsns_adunit_sizes[ $adsns_adunit_size ];
								}
								$adsns_adunit_status = $adsns_adunit->getStatus();
								if ( array_key_exists( $adsns_adunit_status, $adsns_adunit_statuses ) ) {
									$adsns_adunit_status = $adsns_adunit_statuses[ $adsns_adunit_status ];
								}							
								$adsns_tbl_data[ $adsns_adunit->getName() ] = array(
									'id'      => $adsns_adunit->getId(),
									'name'    => $adsns_adunit->getName(),
									'code'    => $adsns_adunit->getCode(),
									'summary' => sprintf( '%s, %s', $adsns_adunit_type, $adsns_adunit_size ),
									'status'  => $adsns_adunit_status
								);
							}
						} catch ( Google_Service_Exception $e ) {
							$adsns_err = $e->getErrors();
							$adsns_api_notice = array(
								'class'    => 'error adsns_api_notice',
								'message'  => sprintf( '<strong>%s</strong> %s %s',
												__( 'AdUnits Error:', 'adsense' ),
												$adsns_err[0]['message'],
												sprintf( __( 'Create account in %s', 'adsense' ), '<a href="https://www.google.com/adsense" target="_blank">Google AdSense.</a>' )
											)
							);
						}
					} catch ( Google_Service_Exception $e ) {
						$adsns_err = $e->getErrors();
						$adsns_api_notice = array(
							'class'    => 'error adsns_api_notice',
							'message'  => sprintf( '<strong>%s</strong> %s %s',
											__( 'AdClient Error:', 'adsense' ),
											$adsns_err[0]['message'],
											sprintf( __( 'Create account in %s', 'adsense' ), '<a href="https://www.google.com/adsense" target="_blank">Google AdSense.</a>' )
										)
						);
					}
				} catch ( Google_Service_Exception $e ) {
					$adsns_err = $e->getErrors();
					$adsns_api_notice = array(
						'class'    => 'error adsns_api_notice',
						'message'  => sprintf( '<strong>%s</strong> %s %s',
										__( 'Account Error:', 'adsense' ),
										$adsns_err[0]['message'],
										sprintf( __( 'Create account in %s', 'adsense' ), '<a href="https://www.google.com/adsense" target="_blank">Google AdSense.</a>' )
									)
					);
				} catch ( Exception $e ) {
					$adsns_api_notice = array(
						'class'   => 'error adsns_api_notice',
						'message' => $e->getMessage()
					);
				}
			}

			if ( isset( $_POST['adsns_authorization_code'] ) && isset( $_POST['adsns_authorize'] ) && ! $adsns_client->getAccessToken() && check_admin_referer( plugin_basename( __FILE__ ), 'adsns_nonce_name' ) ) {
				$adsns_api_notice = array(
					'class'   => 'error adsns_api_notice',
					'message' => __( 'Invalid authorization code. Please, try again.', 'adsense' )
				);
			}

			if ( isset( $_POST['adsns_save_settings'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'adsns_nonce_name' ) ) {
				$adsns_old_options = $this->adsns_options;
				$adsns_area = isset( $_POST['adsns_area'] ) ? $_POST['adsns_area'] : '';

				if ( array_key_exists( $adsns_area, $adsns_tabs ) ) {

					$adsns_save_settings = true;

					if ( isset( $this->adsns_options['adunits'][ $adsns_area ] ) ) {
						$this->adsns_options['adunits'][ $adsns_area ] = array();
					}

					if ( isset( $_POST['adsns_adunit_ids'] ) ) {
						$adsns_adunit_ids = array_slice( $_POST['adsns_adunit_ids'], 0, $adsns_tabs[ $adsns_area ]['max_ads'] );
						$adsns_adunit_positions = isset( $_POST['adsns_adunit_position'] ) ? $_POST['adsns_adunit_position'] : array();

						if ( isset( $adsns_publisher_id ) && isset( $adsns_ad_client ) ) {
							foreach ( $adsns_adunit_ids as $adsns_adunit_id ) {
								try {
									$adsns_adunit_code = $adsns_adsense_adunits->getAdCode( $adsns_ad_client, $adsns_adunit_id )->getAdCode();
									$adsns_adunit_position = array_key_exists( $adsns_adunit_id, $adsns_adunit_positions ) ? $adsns_adunit_positions[ $adsns_adunit_id ] : NULL;
									$this->adsns_options['adunits'][ $adsns_area ][] = array(
										'id'       => $adsns_adunit_id,
										'position' => $adsns_adunit_position,
										'code'     => htmlspecialchars( $adsns_adunit_code )
									);
								} catch ( Google_Service_Exception $e ) {
									$adsns_err = $e->getErrors();
									$adsns_save_settings = false;
									$adsns_settings_notices[] = array(
										'class'    => 'error adsns_settings_notice',
										'message'  => sprintf( '%s<br/>%s<br/>%s', sprintf( __( 'An error occurred while obtaining the code for the block %s.', 'adsense' ), sprintf( '<strong>%s</strong>', $adsns_adunit_id ) ), $adsns_err[0]['message'], __( "Settings are not saved.", 'adsense' ) )
									);
								}
							}
						}
					}

					if ( $adsns_area != 'widget' ) {
						if ( isset( $this->adsns_options['adunits']['widget'] ) ) {
							if ( count( $this->adsns_options['adunits']['widget'] ) > 0 && count( $this->adsns_options['adunits'][ $adsns_area ] ) > 2 ) {
								$adsns_save_settings = false;
								$adsns_settings_notices[] = array(
									'class'    => 'error adsns_settings_notice',
									'message'  => sprintf( '%s<br/>%s<br/>%s', sprintf( __( "The maximum number of ad blocks on the page cannot be more than 3 ad blocks (%s).", 'adsense' ), sprintf( '<a href="https://support.google.com/adsense/answer/1346295?hl=en#Ad_limit_per_page" target="_blank">%s</a>', __( 'Learn more', 'adsense' ) ) ), sprintf( __( 'Please select a smaller number of ad blocks or disable the ad block display in the %s tab.', 'adsense' ), sprintf( '<strong>"%s"</strong>', __( 'Widget', 'adsense' ) ) ), __( "Settings are not saved.", 'adsense' ) )
								);
							}
						}
					} else {
						if ( isset( $this->adsns_options['adunits']['widget'] ) && count( $this->adsns_options['adunits']['widget'] ) > 0 ) {
							$adsns_crowded_tabs = '';
							$adsns_crowded_tabs_count = 0;
							foreach ( $this->adsns_options['adunits'] as $adsns_tab => $adsns_adunit ) {
								if ( $adsns_tab == 'widget' ) {
									continue;
								}
								if ( count( $adsns_adunit ) > 2 ) {
									$adsns_crowded_tabs .= sprintf( '"%s" (%s %s), ', $adsns_tabs[ $adsns_tab ]['tab']['title'], count( $adsns_adunit ), __( 'ad blocks', 'adsense' ) );
									$adsns_crowded_tabs_count++;
								}
							}
							$adsns_crowded_tabs = substr( $adsns_crowded_tabs, 0, -2 );
							if ( $adsns_crowded_tabs_count > 0 ) {
								if ( $adsns_crowded_tabs_count <= 1 ) {
									$adsns_settings_notices[] = array(
										'class'    => 'error adsns_settings_notice',
										'message'  => sprintf( '%s<br/>%s<br/>%s', sprintf( __( "The maximum number of ad blocks on the page cannot be more than 3 ad blocks (%s).", 'adsense' ), sprintf( '<a href="https://support.google.com/adsense/answer/1346295?hl=en#Ad_limit_per_page" target="_blank">%s</a>', __( 'Learn more', 'adsense' ) ) ), sprintf( __( 'To display the ad block in widget, please set a smaller number of ad blocks in the %s tab.', 'adsense' ), sprintf( '<strong>%s</strong>', $adsns_crowded_tabs ) ), __( "Settings are not saved.", 'adsense' ) )
									);
								} else {
									$adsns_settings_notices[] = array(
										'class'    => 'error adsns_settings_notice',
										'message'  => sprintf( '%s<br/>%s<br/>%s', sprintf( __( "The maximum number of ad blocks on the page cannot be more than 3 ad blocks (%s).", 'adsense' ), sprintf( '<a href="https://support.google.com/adsense/answer/1346295?hl=en#Ad_limit_per_page" target="_blank">%s</a>', __( 'Learn more', 'adsense' ) ) ), sprintf( __( 'To display the ad block in widget, please set a smaller number of ad blocks in tabs: %s.', 'adsense' ), sprintf( '<strong>%s</strong>', $adsns_crowded_tabs ) ), __( "Settings are not saved.", 'adsense' ) )
									);
								}
								$adsns_save_settings = false;
							}
						}
					}

					if ( $adsns_save_settings ) {
						update_option( 'adsns_settings', $this->adsns_options );
						$adsns_settings_notices[] = array(
							'class'    => 'updated adsns_settings_notice',
							'message'  => __( "Settings saved.", 'adsense' )
						);
					} else {
						$this->adsns_options = $adsns_old_options;
					}
				} else {
					$adsns_settings_notices[] = array(
						'class'    => 'error adsns_settings_notice',
						'message'  => __( "Settings are not saved.", 'adsense' )
					);
				}
			} ?>
			<div class="wrap" id="adsns_wrap">
				<div class="icon32 icon32-bws" id="icon-options-general"></div>
				<h2><?php _e( 'Google AdSense Settings', 'adsense' ); ?></h2>
				<h2 class="nav-tab-wrapper">
					<a class="nav-tab nav-tab-active" href="admin.php?page=adsense-plugin.php"><?php _e( 'Settings', 'adsense' ); ?></a>
					<a class="nav-tab" href="http://bestwebsoft.com/products/google-adsense/faq" target="_blank"><?php _e( 'FAQ', 'adsense' ); ?></a>
				</h2>
				<?php if ( isset( $adsns_api_notice ) ) {
					printf( '<div class="below-h2 %s"><p>%s</p></div>', $adsns_api_notice['class'], $adsns_api_notice['message'] );
				}
				if ( isset( $adsns_settings_notices ) ) {
					foreach ( $adsns_settings_notices as $adsns_settings_notice ) {
						printf( '<div class="below-h2 %s"><p>%s</p></div>', $adsns_settings_notice['class'], $adsns_settings_notice['message'] );
					}
				} ?>
				<div class="below-h2 updated adsns_new_settings_notice" style="display:none"><p><strong><?php _e( "Notice:", 'adsense' ); ?></strong> <?php _e( "The plugin's settings have been changed. In order to save them please don't forget to click the 'Save Changes' button.", 'adsense' ); ?></p></div>
				<?php if ( ! $this->adsns_adsense_api ) { ?>
					<form id="adsns_settings_form" action="admin.php?page=adsense-plugin.php" method="post">
						<div id="adsns_update">
							<p>
								<strong><?php _e( "Attention:", 'adsense' ); ?></strong> <?php _e( 'We updated the plugin to use Google AdSense API, which is not compatible with the old settings. At the moment, plugin use old settings. But for further plugin usage with a new Google AdSense API, you will need to re-configure the ad blocks display. Please note that the old settings and plugin ad blocks in the frontend will be removed.', 'adsense' ); ?>
								<div><input class="button-primary" type="submit" name="adsns_upgrade" value="<?php _e( 'Upgrade to new functionality', 'adsense' ); ?>"></div>
							</p>
							<?php wp_nonce_field( plugin_basename( __FILE__ ), 'adsns_nonce_name' ); ?>
						</div>
					</form>
				<?php } else { ?>
					<form id="adsns_settings_form" action="admin.php?page=adsense-plugin.php<?php echo $adsns_form_action; ?>" method="post">
						<table id="adsns_api" class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e( 'Remote work with Google AdSense', 'adsense' ); ?></th>
								<td>
									<?php if ( $adsns_client->getAccessToken() ) { ?>
										<div id="adsns_api_buttons">
											<input class="button-secondary" name="adsns_logout" type="submit" value="<?php _e( 'Log out from Google AdSense', 'adsense' ); ?>" />
										</div>
									<?php } else {
										$adsns_state = mt_rand();
										$adsns_client->setState( $adsns_state );
										$_SESSION[ 'gglstmp_state' . $adsns_blog_prefix ] = $adsns_client;
										$adsns_auth_url = $adsns_client->createAuthUrl(); ?>
										<div id="adsns_authorization_notice">
											<?php _e( "Please authorize via your Google Account to manage ad blocks.", 'adsense' ); ?>
										</div>
										<a id="adsns_authorization_button" class="button-primary" href="<?php echo $adsns_auth_url; ?>" target="_blank" onclick="window.open(this.href,'','top='+(screen.height/2-560/2)+',left='+(screen.width/2-640/2)+',width=640,height=560,resizable=0,scrollbars=0,menubar=0,toolbar=0,status=1,location=0').focus(); return false;"><?php _e( 'Get Authorization Code', 'adsense' ); ?></a>
										<div id="adsns_authorization_form">
											<input id="adsns_authorization_code" name="adsns_authorization_code" type="text" autocomplete="off" maxlength="100">
											<input id="adsns_authorize" class="button-primary" name="adsns_authorize" type="submit" value="<?php _e( 'Authorize', 'adsense' ); ?>">
										</div>
									<?php } ?>
								</td>
							</tr>
							<?php if ( isset( $adsns_publisher_id ) ) { ?>
								<tr valign="top">
									<th scope="row"><?php _e( 'Your Publisher ID:', 'adsense' ); ?></th>
									<td>
										<span id="adsns_publisher_id"><?php echo $adsns_publisher_id; ?></span>
									</td>
								</tr>
							<?php } ?>
						</table>
						<?php if ( isset( $adsns_publisher_id ) && isset( $adsns_tabs[ $adsns_current_tab ] ) ) { ?>
							<h2 id="adsns-tabs" class="nav-tab-wrapper">
								<?php foreach( $adsns_tabs as $adsns_tab => $adsns_tab_data ) {
									if ( isset( $this->adsns_options['adunits'][ $adsns_tab ] ) ) {
										$adsns_count_ads = count( $this->adsns_options['adunits'][ $adsns_tab ] );
									} else {
										$adsns_count_ads = 0;
									}
									printf( '<a class="nav-tab%s" href="%s">%s <span class="adsns_count_ads">%d</span></a>', ( $adsns_tab == $adsns_current_tab ) ? ' nav-tab-active' : '', $adsns_tab_data['tab']['url'], $adsns_tab_data['tab']['title'], $adsns_count_ads );
								} ?>
							</h2>
							<div id="adsns_usage_notice">
								<p><?php printf( '<strong>%s</strong> %s <a href="https://support.google.com/adsense/answer/1346295?hl=en#Ad_limit_per_page" target="_blank">%s</a>.', __( 'Please note:', 'adsense' ), __( 'The maximum number of ad blocks on the page cannot be more than 3 ad blocks.', 'adsense' ), __( 'Learn more', 'adsense' ) ); ?></p>
								<?php if ( $adsns_current_tab == 'widget' ) { ?>
									<p><?php printf( __( "Please don't forget to place the AdSense widget into a needed sidebar on the %s.", 'adsense' ), sprintf( '<a href="widgets.php" target="_blank">%s</a>', __( 'widget page', 'adsense' ) ) ); ?></p>
								<?php } ?>
								<p>
									<?php printf( __( 'Add or mange existing ad blocks in the %s.', 'adsense' ), sprintf( '<a href="https://www.google.com/adsense/app#main/myads-viewall-adunits" target="_blank">%s</a>', __( 'Google AdSense', 'adsense' ) ) ); ?><br />
									<span class="bws_info"><?php printf( __( 'After adding the ad block in Google AdSense, please %s to see the new ad block in the list of plugin ad blocks.', 'adsense' ), sprintf( '<a href="admin.php?page=adsense-plugin.php%s">%s</a>', $adsns_form_action, __( 'reload the page', 'adsense' ) ) ) ; ?></span>
								</p>
							</div>
							<?php if ( isset( $this->adsns_options['adunits'][ $adsns_current_tab ] ) ) {
								foreach ( $this->adsns_options['adunits'][ $adsns_current_tab ] as $adsns_tbl_adunit ) {
									$adsns_tbl_adunits[ $adsns_tbl_adunit['id'] ] = $adsns_tbl_adunit['position'];
								}
							}
							$adsns_lt = new Adsns_List_Table();
							$adsns_lt->adsns_tbl_data = $adsns_tbl_data;
							$adsns_lt->adsns_tbl_adunits = ( isset( $adsns_tbl_adunits ) && is_array( $adsns_tbl_adunits ) ) ? $adsns_tbl_adunits : array();
							$adsns_lt->adsns_adunit_positions = $adsns_tabs[ $adsns_current_tab ]['adunit_positions'];
							$adsns_lt->prepare_items();
					 		$adsns_lt->display();
					 	} ?>
						<div id="adsns_save_settings">
							<input type="hidden" name="adsns_area" value="<?php echo $adsns_current_tab; ?>" />
							<input type="submit" class="button-primary" name="adsns_save_settings" id="adsns_save_settings" value="<?php _e( 'Save Changes', 'adsense' ); ?>" />
							<?php wp_nonce_field( plugin_basename( __FILE__ ), 'adsns_nonce_name' ); ?>
						</div>
					</form>
				<?php }
				bws_plugin_reviews_block( $this->adsns_plugin_info['Name'], 'adsense-plugin' ); ?>
			</div>
		<?php }

		/* Including scripts and stylesheets for admin interface of plugin */
		public function adsns_write_admin_head() {
			global $wp_version;
			if ( isset( $_GET['page'] ) && "adsense-plugin.php" == $_GET['page'] ) {
				wp_enqueue_script( 'adsns_admin_script', plugins_url( 'js/admin.js' , __FILE__ ) . sprintf( '?v=%s', $this->adsns_plugin_info["Version"] ) );
				if ( $wp_version < 3.8 ) {
					wp_enqueue_style( 'adsns_stylesheet', plugins_url( 'css/style_wp_before_3.8.css', __FILE__ ) );
				} else {
					wp_enqueue_style( 'adsns_stylesheet', plugins_url( 'css/style.css', __FILE__ ) . sprintf( '?v=%s', $this->adsns_plugin_info["Version"] ) );
				}
			}
		}

		/* Stylesheets for ads */
		function adsns_head() {
			wp_enqueue_style( 'adsns', plugins_url( 'css/adsns.css', __FILE__ ) . sprintf( '?v=%s', $this->adsns_plugin_info["Version"] ) );
		}

		/* Display notice in the main dashboard page / plugins page */
		function adsns_plugin_notice() {
			global $hook_suffix;
			if ( ! $this->adsns_adsense_api && ! is_network_admin() && ( $hook_suffix == 'index.php' || $hook_suffix == 'plugins.php' ) ) {
				ob_start();
				printf(
					'<div class="error adsns_update_notice"><p><strong>%s</strong> %s</p></div>',
					__( 'Attention:', 'adsense' ),
					sprintf( __( 'Google AdSense by BestWebSoft plugin was updated to use Google AdSense API, which is not compatible with the old settings. For further plugin usage, you will need to %s', 'adsense' ), sprintf( '<a href="admin.php?page=adsense-plugin.php">%s</a>', __( 're-configure it.', 'adsense' ) ) )
				);
				echo ob_get_clean();
			}
		}

		/*
		*displays AdSense in widget
		*@return array()
		*/
		function adsns_widget_display() {
			global $adsns_count;
			$title = $this->adsns_options['widget_title'];
			if ( ! $this->adsns_adsense_api ) {
				echo '<aside class="widget widget-container adsns_widget"><h1 class="widget-title">' . $title . '</h1>';
				if ( $adsns_count < $this->adsns_options['max_ads'] ) {
					echo '<div class="ads">' . $this->adsns_options['code'] . '</div>';
					$this->adsns_options['num_show']++;

					update_option( 'adsns_settings', $this->adsns_options );
					$adsns_count = $this->adsns_options['num_show'];
				}
				echo "</aside>";
			} else {
				if ( isset( $this->adsns_options['adunits']['widget'] ) && ! empty( $this->adsns_options['adunits']['widget'] ) ) {
					$adsns_ad_unit_id = $this->adsns_options['adunits']['widget'][0]['id'];
					$adsns_ad_unit_code = htmlspecialchars_decode( $this->adsns_options['adunits']['widget'][0]['code'] );
					printf( '<aside class="widget widget-container adsns_widget"><h1 class="widget-title">%s</h1><div id="%s" class="ads ads_widget">%s</div></aside>', $title, $adsns_ad_unit_id, $adsns_ad_unit_code );
				}
			}
		}

		/*
		*Register widget for use in sidebars.
		*Registers widget control callback for customizing options
		*/
		function adsns_register_widget() {
			wp_register_sidebar_widget(
				'adsns_widget', /* Unique widget id */
				'AdSense', /* Widget name */
				array( $this, 'adsns_widget_display' ), /* Callback function */
				array( 'description' => 'Widget displays AdSense' ) /* Options */
			);
			wp_register_widget_control(
				'adsns_widget', /* Unique widget id */
				'AdSense', /* Widget name */
				array( $this, 'adsns_widget_control' ) /* Callback function */
			);
		}

		/*
		*Registers widget control callback for customizing options
		*@return array
		*/
		function adsns_widget_control() {
			if ( isset( $_POST["adsns-widget-submit"] ) ) {
				$this->adsns_options['widget_title'] = strip_tags( stripslashes( $_POST["adsns-widget-title"] ) );
				update_option( 'adsns_settings', $this->adsns_options );
			}
			$title = isset( $this->adsns_options['widget_title'] ) ? $this->adsns_options['widget_title'] : '' ;
			printf( '<p><label for="adsns-widget-title">%s<input class="widefat" id="adsns-widget-title" name="adsns-widget-title" type="text" value="%s" /></label></p><input type="hidden" id="adsns-widget-submit" name="adsns-widget-submit" value="1" />', __( 'Title', 'adsense' ), $title );
		}
	} /* Class */
}


if ( ! class_exists( 'Adsns_List_Table' ) ) {
	
	global $wp_version;

	if ( ! class_exists( 'WP_List_Table' ) && ! version_compare( $wp_version, '3.1', '<' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	} else {
		return;
	}

	class Adsns_List_Table extends WP_List_Table {

		public $adsns_tbl_data, $adsns_tbl_adunits, $adsns_adunit_positions;

		function get_columns() {
			$columns = array(
				'id'	   => __( 'Display', 'adsense' ),
				'name'     => __( 'Name', 'adsense' ),
				'code'     => __( 'Id', 'adsense' ),
				'summary'  => __( 'Type / Size', 'adsense' ),
				'status'   => __( 'Status', 'adsense' ),
				'position' => __( 'Position', 'adsense' )
			);
			if ( ! $this->adsns_adunit_positions ) {
				unset( $columns['position'] );
			}
			return $columns;
		}

		function usort_reorder( $a, $b ) {
			$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';
			$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
			$result = strcmp( $a[$orderby], $b[$orderby] );
			return ( $order === 'asc' ) ? $result : -$result;
		}

		function get_sortable_columns() {
			$sortable_columns = array(
				'name'    => array( 'name',false ),
				'code'    => array( 'code',false ),
				'summary' => array( 'summary', false ),
				'status'  => array( 'status', false )
			);
			return $sortable_columns;
		}

		function prepare_items() {
			global $adsns_tbl_rows;
			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array( $columns, $hidden, $sortable );
			usort( $this->adsns_tbl_data, array( &$this, 'usort_reorder' ) );
			$this->items = $this->adsns_tbl_data;
		}

		function column_default( $item, $column_name ) {
			switch( $column_name ) {
				case 'id':
				case 'name':
				case 'code':
				case 'summary':
				case 'status':
				case 'position':
					return $item[ $column_name ];
			default:
				return print_r( $item, true );
			}
		}

		function column_id( $item ) {
			return sprintf( '<input class="adsns_adunit_ids" type="checkbox" name="adsns_adunit_ids[]" value="%s" %s/>', $item['id'], ( array_key_exists( $item['id'], $this->adsns_tbl_adunits ) ) ? 'checked="checked"' : '' );
		}

		function column_position( $item ) {
			$adsns_adunit_positions = is_array( $this->adsns_adunit_positions ) ? $this->adsns_adunit_positions : array();
			$adsns_position = '';
			foreach ( $adsns_adunit_positions as $value => $name ) {
				$adsns_position .= sprintf( '<option value="%s" %s>%s</option>', $value, ( array_key_exists( $item['id'], $this->adsns_tbl_adunits ) && $this->adsns_tbl_adunits[ $item['id'] ] == $value ) ? 'selected="selected"' : '', $name );
			}
			return sprintf(
				'<select class="adsns_adunit_position" name="adsns_adunit_position[%s]">%s</select>',
				$item['id'],
				$adsns_position
			);
		}
	}
}

?>