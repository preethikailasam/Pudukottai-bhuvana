<?php
/**
 * @subpackage Admin
 * @since 1.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! get_theme_mod( 'misc_admin_about', true ) ) return;

if ( ! class_exists( 'ZOOM_About_Page' ) ) {
	/**
	 * Singleton class used for generating the about page of the theme.
	 */
	class ZOOM_About_Page {
		/**
		 * Define the version of the class.
		 *
		 * @var string $version The ZOOM_About_Page class version.
		 */
		private $version = '1.0.0';
		/**
		 * Used for loading the texts and setup the actions inside the page.
		 *
		 * @var array $config The configuration array for the theme used.
		 */
		private $config;
		/**
		 * Get the theme name using wp_get_theme.
		 *
		 * @var string $theme_name The theme name.
		 */
		private $theme_name;
		/**
		 * Get the theme slug ( theme folder name ).
		 *
		 * @var string $theme_slug The theme slug.
		 */
		private $theme_slug;
		/**
		 * The current theme object.
		 *
		 * @var WP_Theme $theme The current theme.
		 */
		private $theme;
		/**
		 * Holds the theme version.
		 *
		 * @var string $theme_version The theme version.
		 */
		private $theme_version;
		/**
		 * Define the menu item name for the page.
		 *
		 * @var string $menu_name The name of the menu name under Appearance settings.
		 */
		private $menu_name;
		/**
		 * Define the page title name.
		 *
		 * @var string $page_name The title of the About page.
		 */
		private $page_name;
		/**
		 * Define the page tabs.
		 *
		 * @var array $tabs The page tabs.
		 */
		private $tabs;
		/**
		 * Define the html notification content displayed upon activation.
		 *
		 * @var string $notification The html notification content.
		 */
		private $notification;
		/**
		 * The single instance of ZOOM_About_Page
		 *
		 * @var ZOOM_About_Page $instance The  ZOOM_About_Page instance.
		 */
		private static $instance;

		/**
		 * The Main ZOOM_About_Page instance.
		 *
		 * We make sure that only one instance of ZOOM_About_Page exists in the memory at one time.
		 *
		 * @param array $config The configuration array.
		 */
		public static function init( $config ) {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof ZOOM_About_Page ) ) {
				self::$instance = new ZOOM_About_Page;
				if ( ! empty( $config ) && is_array( $config ) ) {
					self::$instance->config = $config;
					self::$instance->setup_config();
					self::$instance->setup_actions();
				}
			}

		}

		/**
		 * Setup the class props based on the config array.
		 */
		public function setup_config() {
			$theme = wp_get_theme();
			if ( is_child_theme() ) {
				$this->theme_name = $theme->parent()->get( 'Name' );
				$this->theme      = $theme->parent();
			} else {
				$this->theme_name = $theme->get( 'Name' );
				$this->theme      = $theme->parent();
			}
			$this->theme_version = $theme->get( 'Version' );
			$this->theme_slug    = $theme->get_template();
			$this->menu_name     = isset( $this->config['menu_name'] ) ? $this->config['menu_name'] : 'About ' . $this->theme_name;
			$this->page_name     = isset( $this->config['page_name'] ) ? $this->config['page_name'] : 'About ' . $this->theme_name;
			$this->notification  = isset( $this->config['notification'] ) ? $this->config['notification'] : ( '<p>' . sprintf( 'Welcome! Thank you for choosing %1$s! To fully take advantage of the best our theme can offer please make sure you visit our %2$swelcome page%3$s.', $this->theme_name, '<a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-welcome' ) ) . '">', '</a>' ) . '</p><p><a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-welcome' ) ) . '" class="button" style="text-decoration: none;">' . sprintf( 'Get started with %s', $this->theme_name ) . '</a></p>' );
			$this->tabs          = isset( $this->config['tabs'] ) ? $this->config['tabs'] : array();

		}

		/**
		 * Setup the actions used for this page.
		 */
		public function setup_actions() {

			add_action( 'admin_menu', array( $this, 'register' ) );
			/* activation notice */
			add_action( 'load-themes.php', array( $this, 'activation_admin_notice' ) );
			/* enqueue script and style for about page */
			add_action( 'admin_enqueue_scripts', array( $this, 'style_and_scripts' ) );
			/* Load content via Ajax */
			add_action( 'wp_ajax_get_content_via_ajax', array( $this, 'get_content_via_ajax' ) );
			
		}

		/**
		 * Hide required tab if no actions present.
		 *
		 * @return bool Either hide the tab or not.
		 */
		public function hide_required( $value, $tab ) {
			if ( $tab != 'recommended_actions' ) {
				return $value;
			}
			$required = $this->get_required_actions();
			if ( count( $required ) == 0 ) {
				return false;
			} else {
				return true;
			}
		}


		/**
		 * Register the menu page under Appearance menu.
		 */
		function register() {
			if ( ! empty( $this->menu_name ) && ! empty( $this->page_name ) ) {

				$count = 0;

				$actions_count = $this->get_required_actions();

				if ( ! empty( $actions_count ) ) {
					$count = count( $actions_count );
				}

				$title = $count > 0 ? $this->page_name . '<span class="badge-action-count">' . esc_html( $count ) . '</span>' : $this->page_name;

				add_theme_page( $this->menu_name, $title, 'activate_plugins', $this->theme_slug . '-welcome', array(
					$this,
					'zoom_about_page_render',
				) );
			}
		}

		/**
		 * Adds an admin notice upon successful activation.
		 */
		public function activation_admin_notice() {
			global $pagenow;
			if ( is_admin() && ( 'themes.php' == $pagenow ) && isset( $_GET['activated'] ) ) {
				add_action( 'admin_notices', array( $this, 'zoom_about_page_welcome_admin_notice' ), 99 );
			}
		}

		/**
		 * Display an admin notice linking to the about page
		 */
		public function zoom_about_page_welcome_admin_notice() {
			if ( ! empty( $this->notification ) ) {
				echo '<div class="updated notice is-dismissible">';
				echo wp_kses_post( $this->notification );
				echo '</div>';
			}
		}

		/**
		 * Render the main content page.
		 */
		public function zoom_about_page_render() {

			if ( ! empty( $this->config['welcome_title'] ) ) {
				$welcome_title = $this->config['welcome_title'];
			}
			if ( ! empty( $this->config['welcome_content'] ) ) {
				$welcome_content = $this->config['welcome_content'];
			}

			if ( ! empty( $welcome_title ) || ! empty( $welcome_content ) || ! empty( $this->tabs ) ) {

				echo '<div id="zoom-about-page-main" class="wrap about-wrap epsilon-wrap">';
				echo '<div class="theme-title-cont">';
				echo '<div class="zoom-badge-top-small"><img src="'.esc_url( get_template_directory_uri() . '/inc/admin/zoom-about-page/images/logo.png' ).'"><div class="show-off"></div></div>';
				if ( ! empty( $welcome_title ) ) {
					echo '<h1>';
					echo esc_html( $welcome_title );
					echo '</h1><hr class="style15">';
				}
				
				if ( ! empty( $welcome_content ) ) {
					echo '<div class="about-text custom-about-text">' . wp_kses_post( $welcome_content ) . '</div>';
				}
				echo '</div>';

				echo '<div class="theme-logo-cont"><div class="zoom-badge epsilon-welcome-logo"><img src="'.esc_url( get_template_directory_uri() . '/inc/admin/zoom-about-page/images/logo.png' ).'"><div class="show-off"></div></div><span class="about-theme-version">'.esc_html__( 'Version', 'zoom-lite' ).' '.( ! empty( $this->theme_version ) ? esc_html( $this->theme_version ) : esc_html__( 'none', 'zoom-lite' ) ).'</span></div>';

				/* Display tabs */
				if ( ! empty( $this->tabs ) ) {
					$active_tab = isset( $_GET['tab'] ) && sanitize_text_field( wp_unslash( $_GET['tab'] ) ) ? wp_unslash( $_GET['tab'] ) : 'getting_started';

					echo '<h2 class="nav-tab-wrapper wp-clearfix">';

					$actions_count = $this->get_required_actions();

					$count = 0;

					if ( ! empty( $actions_count ) ) {
						$count = count( $actions_count );
					}
					
					$classs = '';

					foreach ( $this->tabs as $tab_key => $tab_name ) {
						
						if ( ( $tab_key != 'changelog' ) || ( ( $tab_key == 'changelog' ) && isset( $_GET['show'] ) && ( $_GET['show'] == 'yes' ) ) ) {

							if ( ( $count == 0 ) && ( $tab_key == 'recommended_actions' ) ) {
								continue;
							}
							
							if ( $tab_key == 'optimize_your_theme' ) {
								$classs = ' dis_on_optimize_your_theme';
							}

							echo '<a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-welcome' ) ) . '&tab=' . esc_html( $tab_key ) . '" class="nav-tab' . ( $active_tab == $tab_key ? ' nav-tab-active' : '' ) . esc_attr( $classs ) . '" role="tab" data-toggle="tab">';
							echo esc_html( $tab_name );
							
							if ( $tab_key == 'recommended_actions' ) {
								$count = 0;

								$actions_count = $this->get_required_actions();

								if ( ! empty( $actions_count ) ) {
									$count = count( $actions_count );
								}
								if ( $count > 0 ) {
									echo '<span class="badge-action-count">' . esc_html( $count ) . '</span>';
								}
							}
							echo '</a>';
							
						}

					}

					echo '</h2>';

					/* Display content for current tab */
					if ( method_exists( $this, $active_tab ) ) {
						$this->$active_tab();
					}
				}

				echo '</div><!--/.wrap.about-wrap-->';
			}
		}

		/*
		 * Call plugin api
		 */
		public function call_plugin_api( $slug ) {
			
			include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

			if ( false === ( $call_api = get_transient( 'zoom_about_page_plugin_information_transient_' . $slug ) ) ) {
				
				$call_api = plugins_api( 'plugin_information', array(
					'slug'   => $slug,
					'fields' => array(
						'downloaded'        => false,
						'rating'            => false,
						'description'       => false,
						'short_description' => true,
						'donate_link'       => false,
						'tags'              => false,
						'sections'          => true,
						'homepage'          => true,
						'added'             => false,
						'last_updated'      => false,
						'compatibility'     => false,
						'tested'            => false,
						'requires'          => false,
						'downloadlink'      => false,
						'icons'             => true
					)
				) );
				
				set_transient( 'zoom_about_page_plugin_information_transient_' . $slug, $call_api, 3 * DAY_IN_SECONDS );
				
			}

			return $call_api;
		}

		public function check_if_plugin_active( $slug, $filenm ) {

			$path = WPMU_PLUGIN_DIR . '/' . $slug . '/' . $filenm . '.php';
			if ( ! file_exists( $path ) ) {
				$path = WP_PLUGIN_DIR . '/' . $slug . '/' . $filenm . '.php';
				if ( ! file_exists( $path ) ) {
					$path = false;
				}
			}

			if ( file_exists( $path ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

				$needs = is_plugin_active( $slug . '/' . $filenm . '.php' ) ? 'deactivate' : 'activate';

				return array( 'status' => is_plugin_active( $slug . '/' . $filenm . '.php' ), 'needs' => $needs );
			}

			return array( 'status' => false, 'needs' => 'install' );
		}

		/**
		 * Get icon of wordpress.org plugin
		 * @param $arr
		 *
		 * @return mixed
		 */
		public function get_plugin_icon( $arr ) {

			if ( ! empty( $arr['svg'] ) ) {
				$plugin_icon_url = $arr['svg'];
			} elseif ( ! empty( $arr['2x'] ) ) {
				$plugin_icon_url = $arr['2x'];
			} elseif ( ! empty( $arr['1x'] ) ) {
				$plugin_icon_url = $arr['1x'];
			} else {
				$plugin_icon_url = get_template_directory_uri() . '/inc/admin/zoom-about-page/images/placeholder_plugin.png';
			}

			return $plugin_icon_url;
		}

		public function create_action_link( $state, $slug, $fname ) {

			switch ( $state ) {
				case 'install':
					return wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => $slug
							),
							network_admin_url( 'update.php' )
						),
						'install-plugin_' . $slug
					);
					break;
				case 'deactivate':
					return add_query_arg( array(
						'action'        => 'deactivate',
						'plugin'        => rawurlencode( $slug . '/' . $fname . '.php' ),
						'plugin_status' => 'all',
						'paged'         => '1',
						'_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $slug . '/' . $fname . '.php' ),
					), network_admin_url( 'plugins.php' ) );
					break;
				case 'activate':
					return add_query_arg( array(
						'action'        => 'activate',
						'plugin'        => rawurlencode( $slug . '/' . $fname . '.php' ),
						'plugin_status' => 'all',
						'paged'         => '1',
						'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $slug . '/' . $fname . '.php' ),
					), network_admin_url( 'plugins.php' ) );
					break;
			}
		}

		/**
		 * Getting started tab
		 */
		public function getting_started() {

			if ( ! empty( $this->config['getting_started'] ) ) {

				$getting_started = $this->config['getting_started'];

				if ( ! empty( $getting_started ) ) {

					echo '<div id="min-margin-top" class="feature-section three-col">';

					foreach ( $getting_started as $getting_started_item ) {

						echo '<div class="col">';
						if ( ! empty( $getting_started_item['title'] ) ) {
							echo '<h3>';
							if ( ! empty( $getting_started_item['icon'] ) ) {
								echo '<i class="' . esc_attr( $getting_started_item['icon'] ) . '"></i>';
							}
							echo esc_html( $getting_started_item['title'] ) . '</h3>';
						}
						if ( ! empty( $getting_started_item['text'] ) ) {
							echo '<p>' . esc_html( $getting_started_item['text'] ) . '</p>';
						}
						if ( ! empty( $getting_started_item['button_link'] ) && ! empty( $getting_started_item['button_label'] ) ) {

							echo '<p>';
							$button_class = '';
							if ( $getting_started_item['is_button'] ) {
								$button_class = 'button button-primary';
							}

							$count = 0;

							$actions_count = $this->get_required_actions();

							if ( ! empty( $actions_count ) ) {
								$count = count( $actions_count );
							}
							
							$action_task = false;
							if ( $getting_started_item['recommended_actions'] && isset( $count ) ) {
								if ( $count == 0 ) {
									$action_task = true;
								} else {
									$action_task = false;
								}
							}

                            $button_new_tab = '_self';
                            if ( isset( $getting_started_item['is_new_tab'] ) ) {
                                if ( $getting_started_item['is_new_tab'] ) {
                                    $button_new_tab = '_blank';
                                }
                            }

							if ( $action_task ) {
								echo '<span class="all-task-completed"><span class="dashicons dashicons-yes task-ok"></span>'.esc_html__( 'GREAT! All Actions Are Done!', 'zoom-lite' ).'</span>';
							} else {
							echo '<a target="' . esc_attr( $button_new_tab ) . '" href="' . esc_url( $getting_started_item['button_link'] ) . '"class="' . esc_attr( $button_class ) . '">' . esc_html( $getting_started_item['button_label'] ) . '</a>';
							}
							echo '</p>';
						}

						echo '</div><!-- .col -->';
					}
					echo '</div><!-- .feature-section three-col -->';
					
					// Active Plugins Currently Supported
					echo '<hr style="margin-top:30px;margin-bottom:0" class="style14"><div class="feature-section one-col one-col-custom">';
					echo '<div class="col">';
					
					$plugin_details = $this->config['supported_plugins'];
					$activeplugins = array();
					
					if ( class_exists( 'WP_Composer' ) ) { $activeplugins[] = 'wpc'; }
					if ( class_exists( 'Vc_Manager' ) ) { $activeplugins[] = 'vc'; }
					if ( class_exists( 'KingComposer' ) ) { $activeplugins[] = 'kcom'; }
					if ( class_exists( 'RevSlider' ) ) { $activeplugins[] = 'rvs'; }
					if ( class_exists( 'SiteOrigin_Panels' ) ) { $activeplugins[] = 'sopb'; }
					if ( zoom_jetpack_active_module( 'infinite-scroll' ) ) { $activeplugins[] = 'jpk'; }
					
					
					if ( ! empty( $activeplugins ) ) {
						
						echo '<h3 class="supported-plugin-title"><span class="dashicons dashicons-active-plugins dashicons-admin-plugins"></span>' . esc_html__( 'Your Active Plugins Currently Supported', 'zoom-lite' ) . '</h3>';

						foreach ( $activeplugins as $active ) {

							echo '<div class="rec-plugin-list-cont"><div class="rec-icon '.esc_attr( $plugin_details[$active]['icon_class'] ).'"></div><div class="rec-action"><span class="rec-plugin-name">'.esc_html( $plugin_details[$active]['title'] ).'</span><span class="rec-plugin-desc">'.sprintf( esc_html( $plugin_details[$active]['content_before_link'] ),
							( $plugin_details[$active]['use_link'] ? '<a class="learn-more-here" href="'.esc_url( $plugin_details[$active]['the_link'] ).'" '.( $plugin_details[$active]['is_new_tab'] ? 'target="_blank"' : '' ).'><span class="dashicons dashicons-custom-link dashicons-admin-links"></span>'.esc_html( $plugin_details[$active]['link_text'] ).'</a>' : '' ) ).'</span></div></div>';
							
						}
	
					}

					echo '</div>';
					echo '</div><!-- .feature-section one-col -->';
					
				}

			}
		}

		/**
		 * Recommended Actions tab
		 */
		public function recommended_actions() {

			$recommended_actions = isset( $this->config['recommended_actions'] ) ? $this->config['recommended_actions'] : array();

			if ( ! empty( $recommended_actions ) ) {

				echo '<div class="feature-section action-required demo-import-boxed" id="plugin-filter">';

				$actions = array();
				$req_actions = isset( $this->config['recommended_actions'] ) ? $this->config['recommended_actions'] : array();
				
				foreach ( $req_actions['content'] as $req_action ) {
					
					$actions[] = $req_action;
					
				}

				if ( ! empty( $actions ) && is_array( $actions ) ) {

					$zoom_about_page_show_required_actions = get_option( $this->theme_slug . '_required_actions' );

					foreach ( $actions as $action_key => $action_value ) {

						$hidden = false;

						if ( @$zoom_about_page_show_required_actions[ $action_value['id'] ] === false ) {
							$hidden = true;
						}
						if ( @$action_value['check'] ) {
							continue;
						}

						echo '<div class="zoom-about-page-action-required-box">';

						if ( ! empty( $action_value['title'] ) ) {
							echo '<h3>' . wp_kses_post( $action_value['title'] ) . '</h3>';
						}

						if ( ! empty( $action_value['description'] ) ) {
							echo '<p>' . wp_kses_post( $action_value['description'] ) . '</p>';
						}

						if ( ! empty( $action_value['plugin_slug'] ) ) {
							
							$active = $this->check_if_plugin_active( $action_value['plugin_slug'], $action_value['file_name'] );
							$external_link = ( isset( $action_value['external_link'] ) && $active['needs'] == 'install' ? esc_url( $action_value['external_link'] ) : false );
							$link_target = ( $external_link ? '_blank' : '_self' );
							$url    = ( $external_link ? $external_link : $this->create_action_link( $active['needs'], $action_value['plugin_slug'], $action_value['file_name'] ) );
							$label  = '';

							switch ( $active['needs'] ) {

								case 'install':
									$class = ( $external_link ? 'button' : 'install-now button' );
									if ( ! empty( $this->config['recommended_actions']['install_label'] ) ) {
										$label = $this->config['recommended_actions']['install_label'];
									}
									break;
								case 'activate':
									$class = 'activate-now button button-primary';
									if ( ! empty( $this->config['recommended_actions']['activate_label'] ) ) {
										$label = $this->config['recommended_actions']['activate_label'];
									}
									break;
								case 'deactivate':
									$class = 'deactivate-now button';
									if ( ! empty( $this->config['recommended_actions']['deactivate_label'] ) ) {
										$label = $this->config['recommended_actions']['deactivate_label'];
									}
									break;
							}

							?>
							<p class="plugin-card-<?php esc_attr_e( $action_value['plugin_slug'] ) ?> action_button <?php echo ( $active['needs'] !== 'install' && $active['status'] ) ? 'active' : '' ?>">
								<a data-slug="<?php esc_attr_e( $action_value['plugin_slug'] ) ?>"
								   class="<?php esc_attr_e( $class ); ?>"
                                   target="<?php esc_attr_e( $link_target ); ?>"
								   href="<?php echo esc_url( $url ) ?>"> <?php echo esc_html( $label ) ?> </a>
							</p>

							<?php

						}
						
						if ( ! empty( $action_value['update_theme'] ) ) {
							
							$label = $this->config['recommended_actions']['update_label'];
							$act_theme = get_template();
							$nonce = wp_create_nonce( 'upgrade-theme_' . $act_theme );
							$update_url = add_query_arg( array( 'action' => 'upgrade-theme', 'theme' => $act_theme, '_wpnonce' => $nonce ), admin_url( 'update.php' ) );

							?>
							<p class="plugin-card-<?php echo esc_attr( $action_value['id'] ) ?> action_button">
								<a class="activate-now button button-primary" href="<?php echo esc_url_raw( $update_url ) ?>"> <?php echo esc_html( $label ) ?> </a>
							</p>

							<?php

						}
						
						echo '</div>';
					}
				}
				echo '</div>';
			}
		}

		/**
		 * Recommended plugins tab
		 */
		public function recommended_plugins() {
			
			$recommended_plugins = $this->config['recommended_plugins'];
			if ( ! empty( $recommended_plugins ) ) {
				if ( ! empty( $recommended_plugins['content'] ) && is_array( $recommended_plugins['content'] ) ) {

					echo '<div data-secure="'.wp_create_nonce( "zoom_about_ajax_nonce" ).'" class="feature-section recommended-plugins three-col demo-import-boxed plugin-to-short ajax-recommended-plugins" id="plugin-filter">';
					echo '<div class="wntabloader"><span class="retv-msg">' . esc_html__( 'Retrieving data from server, please wait...', 'zoom-lite' ) . '</span></div>';
					echo '</div><!-- .recommended-plugins -->';

				}
			}
			
			
		}

		/**
		 * Support tab
		 */
		public function support() {
			echo '<div style="padding-top:15px" class="feature-section three-col">';

			if ( ! empty( $this->config['support_content'] ) ) {

				$support_steps = $this->config['support_content'];

				if ( ! empty( $support_steps ) ) {

					foreach ( $support_steps as $support_step ) {

						echo '<div class="col min-margin-bottom">';

						if ( ! empty( $support_step['title'] ) ) {
							echo '<h3>';
							if ( ! empty( $support_step['icon'] ) ) {
								echo '<i class="' . esc_attr( $support_step['icon'] ) . '"></i>';
							}
							echo esc_html( $support_step['title'] );
							echo '</h3>';
						}

						if ( ! empty( $support_step['text'] ) ) {
							echo '<p>' . esc_html( $support_step['text'] ) . '</p>';
						}

						if ( ! empty( $support_step['button_link'] ) && ! empty( $support_step['button_label'] ) ) {

							echo '<p>';
							$button_class = '';
							if ( $support_step['is_button'] ) {
								$button_class = 'button button-primary';
							}

							$button_new_tab = '_self';
							if ( isset( $support_step['is_new_tab'] ) ) {
								if ( $support_step['is_new_tab'] ) {
									$button_new_tab = '_blank';
								}
							}
							echo '<a target="' . esc_attr( $button_new_tab ) . '" href="' . esc_url( $support_step['button_link'] ) . '"class="' . esc_attr( $button_class ) . '">' . esc_html( $support_step['button_label'] ) . '</a>';
							echo '</p>';
						}

						echo '</div>';

					}

				}

			}

			echo '</div>';
			
				// Changelog
				$allowed_tags = zoom_wp_kses_allowed_html();
			
				echo '<div class="feature-section one-col min-padding-top">';
				echo '<div id="cl-col" class="col main-cl-col">';
				echo '<div class="cl-title-cont"><h3 class="cl-title">' . esc_html__( 'Update History', 'zoom-lite' ) . '</h3></div>';
				echo '<div class="cl-container">';
				echo wp_kses( $this->zooom_parse_changelog(), $allowed_tags );
				echo '</div><span class="cl-container-footer"></span></div></div>';
		}
		
		
		/**
		 * Optimize Your Theme
		 */
		public function optimize_your_theme() {
			
			$optimize_your_theme = $this->config['optimize_your_theme'];
			if ( ! empty( $optimize_your_theme ) ) {
				
				$optimize_data = $optimize_your_theme['content'];

				echo '<div class="optimize_your_theme feature-section three-col">';
				echo '<h3><i class="dashicons dashicons-megaphone"></i>'.$optimize_data['title'].'</h3>';
				echo '<p>'.$optimize_data['description'].'</p>';
				echo '<p>'.__( '<strong>WP Composer page builder plugin</strong> come in handy because this plugin has a lot of features that you needs. This plugin will allow you to create beautiful website just in minutes without writing any code. Just watch the following video and you will see it work like a magic!', 'zoom-lite' ).'</p>';
				echo '<div class="zoom_optz_content">';
				echo '<div class="zoom_optz_video_cont"><div class="zoom_optz_iframe_cont"><iframe class="zoom_iframe_video" src="https://www.youtube.com/embed/PVIaost_c2A?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe></div></div>';
				echo '<div class="zoom_optz_learn_cont">';
				echo '<h3><i class="dashicons dashicons-lightbulb"></i>'.esc_html__( 'Learn more here', 'zoom-lite' ).' :</h3>';
				echo '<h4><i class="dashicons dashicons-arrow-right"></i>'.esc_html__( 'Website', 'zoom-lite' ).' : <a href="'.esc_url_raw( 'https://ghozy.link/9z83m' ).'" target="_blank">wpcomposer.com</a></h4>';
				echo '<h4><i class="dashicons dashicons-arrow-right"></i>'.esc_html__( 'Demo', 'zoom-lite' ).' : <a href="'.esc_url_raw( 'https://ghozy.link/mr79c' ).'" target="_blank">source.wpcomposer.com</a></h4>';
				echo '<img class="wpc_logo" src="'.esc_url( get_template_directory_uri() . '/inc/admin/zoom-about-page/images/wpc_rocket.png' ).'">';
				echo '</div>';
				echo '</div>';
				echo '</div><!-- .recommended-plugins -->';
					
			}
			
			
		}
		
		
		/**
		 * Parse Changelog
		 */
		public function zooom_parse_changelog() {
			
				$filecl =  get_template_directory() . '/readme.txt';
				
				if( ! file_exists( $filecl ) ) {
					
					echo '<hr class="style14"><h4>' . esc_html__( 'No valid changelog was found', 'zoom-lite' ) . '</h4>';
					
					return;
				}
				
				if( ! is_readable( $filecl ) ) {
					
					echo '<hr class="style14"><h4>' . esc_html__( 'The changelog in readme.txt is not readable', 'zoom-lite' ) . '</h4>';
					
					return;
					
				}
					
					$content = implode( '', file( $filecl ) );
					$anchor = strpos( $content, '== Changelog ==' );
					
					if ( !empty( $content ) && $anchor !== false ) {
						
						$content = substr( $content, $anchor + strlen( '== Changelog ==' ) );
						$content = explode( "\n", $content );
						$changetype = array( 'new' => array(), 'improve' => array(), 'bugfixes' => array(), 'changes' => array(), 'remove' => array() );

						foreach ( $content as $n => $line ) {
			
							$line = trim( $line );
			
							if ( substr ( $line, 0, 1 ) == '*' ) {
			
								$line = trim( substr ( $line, 1 ) );
								
								if ( strpos( $line, '[New]' ) === 0 )
									$changetype['new'][] = substr ( $line, 5 );
								else if ( strpos($line, '[Improve]' ) === 0 )
									$changetype['improve'][] = substr ( $line, 9 );
								else if ( strpos($line, '[Fix]' ) === 0 )
									$changetype['bugfixes'][] = substr ( $line, 5 );
								else if ( strpos( $line, '[Remove]' ) === 0 )
									$changetype['remove'][] = substr ( $line, 8 );
								else $changetype['changes'][] = $line;
			
							}
							else {

								foreach ( $changetype as $label => $items ) {
									
									if ( $label == 'new' ) {
										$labeltitle = $label.'&nbsp;'.esc_html__( 'Features', 'zoom-lite' );
									}
									elseif ( $label == 'remove' ) {
										$labeltitle = esc_html__( 'Removed', 'zoom-lite' );
									} 
									else {
										$labeltitle = $label;
									}
									
									if ( count( $items ) > 0 ) {
										
										echo '<span class="whats-new"><span class="feature-label '.esc_attr( $label ).'"><span class="dashicons-cl-type '.esc_attr( $label ).'"></span>'.esc_html( $labeltitle ).'</span></span>';
										echo '<div class="cl-list-container">';
										echo '<ul>';
										foreach ( $items as $i => $item ) {
											if ( !empty( $item ) )
												echo '<li>'.esc_html( $item ).'</li>';
										}
										echo '</ul>';
										echo '</div>';
										
									}

								}
			
								$changetype = array( 'new' => array(), 'improve' => array(), 'bugfixes' => array(), 'changes' => array(), 'remove' => array() ); 
			
								if ( substr ( $line, strlen( $line ) -1 ) == '=' && substr ( $line, 0, 1 ) == '=' )
									echo '<h4 class="cl-version-title"><span class="dashicons-version dashicons dashicons-format-aside"></span>'.esc_html__( 'Version', 'zoom-lite' ).'&nbsp;'. esc_html( substr ( $line, 1, strlen( $line ) -2 ) ).'</h4><hr class="style14">';
									
							}
							
						}
						
						
					}
					
					else {
						
						echo '<hr class="style14"><h4>' . esc_html__( 'Error: Could not read data', 'zoom-lite' ) . '</h4>';
						
					}

		}

		/**
		 * Load css and scripts for the about page
		 */
		public function style_and_scripts( $hook_suffix ) {

			// this is needed on all admin pages, not just the about page, for the badge action count in the wordpress main sidebar
			wp_enqueue_style( 'zoom-about-page-css', get_template_directory_uri() . '/inc/admin/zoom-about-page/css/zoom_about_page_css.css' );

			if ( 'appearance_page_' . $this->theme_slug . '-welcome' == $hook_suffix ) {

				wp_enqueue_script( 'zoom-about-page-js', get_template_directory_uri() . '/inc/admin/zoom-about-page/js/zoom_about_page_scripts.js', array( 'jquery' ) );

				wp_enqueue_style( 'plugin-install' );
				wp_enqueue_script( 'plugin-install' );
				wp_enqueue_script( 'updates' );

			}

		}

		/**
		 * Return the valid array of required actions.
		 *
		 * @return array The valid array of required actions.
		 */
		private function get_required_actions() {
			
			if ( ! zoom_about_get_update_info() ) {
				
				unset( $this->config['recommended_actions']['content']['zoom-lite-theme'] );
				
			}
			
			$saved_actions = get_option( $this->theme_slug . '_required_actions' );
			if ( ! is_array( $saved_actions ) ) {
				$saved_actions = array();
			}
			$req_actions = isset( $this->config['recommended_actions'] ) ? $this->config['recommended_actions'] : array();
			$valid       = array();
			foreach ( $req_actions['content'] as $req_action ) {
				if ( ( ! isset( $req_action['check'] ) || ( isset( $req_action['check'] ) && ( $req_action['check'] == false ) ) ) && ( ! isset( $saved_actions[ $req_action['id'] ] ) ) ) {
					$valid[] = $req_action;
				}
			}

			return $valid;
		}
		
		
		/**
		 * Get Content via AJAX
		 */
		public function get_content_via_ajax() {
			
			// run a quick security check
			if( ! check_ajax_referer( 'zoom_about_ajax_nonce', 'security' ) )
				return;
				
				ob_start();	

					$recommended_plugins = $this->config['recommended_plugins'];
					
					foreach ( $recommended_plugins['content'] as $recommended_plugins_item ) {

						if ( ! empty( $recommended_plugins_item['slug'] ) ) {
							$info   = $this->call_plugin_api( $recommended_plugins_item['slug'] );
							if ( ! empty( $info->icons ) ) {
								$icon = $this->get_plugin_icon( $info->icons );
							}

							$active = $this->check_if_plugin_active( $recommended_plugins_item['slug'], $recommended_plugins_item['file_name'] );

							if ( ! empty( $active['needs'] ) ) {
								$url = $this->create_action_link( $active['needs'], $recommended_plugins_item['slug'], $recommended_plugins_item['file_name'] );
							}

							echo '<div id="'. esc_attr( $recommended_plugins_item['slug'] ) .'" class="col plugin_box">';
							if ( ! empty( $icon ) ) {
								echo '<img src="'.esc_url( $icon ).'" alt="plugin box image">';
							}
							if ( ! empty(  $info->version ) ) {
								echo '<span class="version plugin-title-on-list">'. esc_html( $info->name ).'</span>';
							}

							if ( ! empty( $info->name ) && ! empty( $active ) ) {
								echo '<div class="action_bar ' . ( ( $active['needs'] !== 'install' && $active['status'] ) ? 'active' : '' ) . '">';
								echo '<span class="plugin_name">' . ( ( $active['needs'] !== 'install' && $active['status'] ) ? 'Active' : '<span class="version-on-list">Version '. esc_html( $info->version ) ) . '</span></span>';
								echo '</div>';

								$label = '';

								switch ( $active['needs'] ) {
									case 'install':
										$class = 'install-now button';
										if ( ! empty( $this->config['recommended_plugins']['install_label'] ) ) {
											$label = $this->config['recommended_plugins']['install_label'];
										}
										break;
									case 'activate':
										$class = 'activate-now button button-primary';
										if ( ! empty( $this->config['recommended_plugins']['activate_label'] ) ) {
											$label = $this->config['recommended_plugins']['activate_label'];
										}
										break;
									case 'deactivate':
										$class = 'deactivate-now button';
										if ( ! empty( $this->config['recommended_plugins']['deactivate_label'] ) ) {
											$label = $this->config['recommended_plugins']['deactivate_label'];
										}
										break;
								}

								echo '<span class="plugin-card-' . esc_attr( $recommended_plugins_item['slug'] ) . ' action_button ' . ( ( $active['needs'] !== 'install' && $active['status'] ) ? 'active' : '' ) . '">';
								echo '<a data-slug="' . esc_attr( $recommended_plugins_item['slug'] ) . '" class="' . esc_attr( $class ) . '" href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
								echo '</span>';
							}
							echo '</div><!-- .col.plugin_box -->';
						}

					}

			$cntent = ob_get_clean();
					
			echo $cntent;
			
			wp_die();
			
		}

	}
	
}