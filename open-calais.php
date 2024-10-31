<?php

/**
 * Plugin Name: Open Calais
 * Plugin URI:  https://github.com/wpcomvip/metro/
 * Description: Use Open Calais to get suggested tags for your article.
 * Version:     1.2.5
 * Author:      Metro.co.uk
 * Author URI:  https://github.com/wpcomvip/metro/graphs/contributors
 * Text Domain: open-calais
 */

namespace Open_Calais;

if ( ! class_exists( 'Open_Calais' ) ) :

	/**
	 * Class Admin
	 *
	 * Use Open Calais to get suggested tags for your article.
	 *
	 * @package Open_Calais
	 */
	class Admin {

		/**
		 * API resource URL
		 *
		 * @var string
		 */
		const API_URL = 'https://api-eit.refinitiv.com/permid/calais';

		/**
		 * The cache group
		 *
		 * @var string
		 */
		const CACHE_GROUP = 'open_calais';

		/**
		 * The cache length (a day) in seconds.
		 *
		 * @var int
		 */
		public static $cache_length = 86400;

		/**
		 * Minimum permission required to access Open Calais functionality.
		 *
		 * @var string
		 */
		public static $capability = 'manage_categories';

		/**
		 * The maximum font size.
		 *
		 * @var int
		 */
		public static $font_max = 12;

		/**
		 * The minumum font size.
		 *
		 * @var int
		 */
		public static $font_min = 6;

		/**
		 * The default post type to perform these actions for.
		 *
		 * @var string
		 */
		public static $post_type = 'post';

		/**
		 * The default taxonomy this is intended to complement.
		 *
		 * @var string
		 */
		public static $taxonomy = 'post_tag';

		/**
		 * Error message text.
		 *
		 * @var string
		 */
		private static $error_message = 'No matching tags found.';

		/**
		 * Add initial hooks.
		 */
		public static function load() {
			add_action( 'admin_menu', [ __CLASS__, 'add_settings_page' ] );
			add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
			add_action( 'admin_init', [ __CLASS__, 'admin_init' ] );
		}

		/**
		 * Add the settings page.
		 */
		public static function add_settings_page() {
			add_options_page( 
				'Open Calais',
				'Open Calais',
				'manage_options',
				'open-calais-settings',
				[ __CLASS__, 'render_settings_page' ]
			);
		}

		/**
		 * Render the settings page.
		 */	
		public static function render_settings_page() {
			?>
			<div class="wrap">
				<h2>Open Calais Settings</h2>
				<form method="post" id="open-calais-settings" action="options.php">
					<?php
					settings_fields( 'open_calais' );
					do_settings_sections( 'open-calais-settings' );
					submit_button( 'Save Changes', 'primary', 'submit', true, [ 'id' => 'submit' ] );
					?>
				</form>
			</div>
			<?php
		}

		/**
		 * Register the settings.
		 */
		public static function register_settings() {

			// Add a generic settings section.
			add_settings_section(
				'open-calais-settings-general',
				'',
				'__return_false',
				'open-calais-settings'
			);

			// API key field.
			add_settings_field(
				'open-calais-api-key',
				'<label for="open_calais_api_token">API Token</label><br />
				<small><a href="https://developers.refinitiv.com/open-permid/intelligent-tagging-restful-api" target="_blank">Get your token here</a></small>',
				[
					__CLASS__,
					'api_token_html',
				],
				'open-calais-settings',
				'open-calais-settings-general'
			);

			register_setting( 'open_calais', 'open_calais_api_token', 'sanitize_text_field' );

			// Only show 
			add_settings_field(
				'open-calais-show-existing',
				'<label for="open_calais_show_existing">Match existing tags</label>
				<p><small>Only display Open Calais tags that match existing tags in your CMS</small></p>',
				[
					__CLASS__,
					'show_existing_html',
				],
				'open-calais-settings',
				'open-calais-settings-general'
			);

			register_setting( 'open_calais', 'open_calais_show_existing', [ __CLASS__, 'sanitize_checkbox' ] );
		}

		/**
		 * API key HTML.
		 */
		public static function api_token_html() {
			$value = get_option( 'open_calais_api_token' );
			printf(
				'<input type="text" id="open_calais_api_token" name="open_calais_api_token" class="regular-text" value="%s" />',
				esc_attr( $value )
			);
		}

		/**
		 * HTML for checkbox to only display existing .
		 */
		public static function show_existing_html() {
			$value = (bool) get_option( 'open_calais_show_existing' );
			printf(
				'<input type="checkbox" id="open_calais_show_existing" name="open_calais_show_existing" class="regular-text" value="1" %s />',
				( $value === true ) ? 'checked' : ''
			);
		}

		/**
		 * Sanitize checkbox.
		 *
		 * @param string $value The checkbox value
		 * @return bool The sanitized value
		 */
		public static function sanitize_checkbox( $value ) {
			return ( $value === '1' ) ? true : false;
		}

		/**
		 * Admin-only display customization.
		 */
		public static function admin_init() {

			/**
			 * Filter permissions for Open Calais functionality.
			 *
			 * @param string $capability The default capability.
			 */
			self::$capability = apply_filters( 'open_calais_capability', self::$capability );

			// Exit early if user doesn't meet minimum level.
			if ( ! current_user_can( self::$capability ) ) {
				return;
			}

			// Change the "Choose From Most Used" label.
			global $wp_taxonomies;
			if ( ! empty( $wp_taxonomies[ self::$taxonomy ]->labels->choose_from_most_used ) ) {
				$wp_taxonomies[ self::$taxonomy ]->labels->choose_from_most_used = 'Suggested tags from Open Calais&trade;';
			}

			// Check the current screen to add some additional hooks.
			add_action( 'current_screen', [ __CLASS__, 'current_screen' ] );

			// Filter tag cloud data.
			add_filter( 'wp_generate_tag_cloud_data', [ __CLASS__, 'filter_tag_cloud' ] );
		}

		/**
		 * Check current screen to determine if we should enqueue styles.
		 *
		 * @param WP_Screen $screen The screen object.
		 */
		public static function current_screen( $screen ) {

			// Print some extra styles if we're on the post editor
			if ( ! empty( $screen->base ) && 'post' === $screen->base ) {

				// Check if block editor is active and enqueue assets accordingly.
				if ( method_exists( $screen, 'is_block_editor' ) && $screen->is_block_editor() ) {
					add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_assets' ] );
				} else {
					add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_classic_editor_assets' ] );
				}
			}
		}

		/**
		 * Enqueue block editor assets.
		 */
		public static function enqueue_block_editor_assets() {

			wp_enqueue_script(
				'open-calais',
				plugin_dir_url( __FILE__ ) . 'assets/js/block-editor.js'
			);

			wp_localize_script(
				'open-calais',
				'OpenCalais',
				(array) self::get_tags_by_post_id( get_the_ID() )
			);

			wp_enqueue_style(
				'open-calais-block-editor',
				plugin_dir_url( __FILE__ ) . 'assets/css/block-editor-styles.css'
			);
		}

		/**
		 * Enqueue classic editor assets.
		 */
		public static function enqueue_classic_editor_assets() {
			
			// Enqueue stylesheet.
			wp_enqueue_style(
				'open-calais-classic-editor',
				plugin_dir_url( __FILE__ ) . 'assets/css/classic-editor-styles.css'
			);
		}

		/**
		 * Filter tag cloud data.
		 *
		 * @param array $data The tag cloud data.
		 * @return array The tag cloud data.
		 */
		public static function filter_tag_cloud( $data ) {

			$post_id = 0;

			// Make sure this only happens during AJAX action
			if ( ! empty( $_POST['action'] ) && 'get-tagcloud' === $_POST['action'] ) {

				// Make sure this is the correct taxonomy
				if ( ! empty( $_POST['tax'] ) && self::$taxonomy === $_POST['tax'] ) {

					// Array to contain our new tags
					$new_tags = [];

					// We need to get post ID from the referer since it doesn't come through in AJAX request
					$post_id = self::get_post_id_from_referer();

					// Query the API for tags
					$tags = self::get_tags_by_post_id( $post_id );

					// Make sure we weren't returned an empty set
					if ( ! empty( $tags ) ) {

						// Loop through tags
						foreach ( $tags as $tag ) {

							// Get the cloud data
							$cloud_data = self::get_cloud_data( $tag );

							// Make sure we were returned a valid response and add to our array
							if ( ! empty( $cloud_data ) ) {
								$new_tags[] = $cloud_data;
							}
						}
					}

					// If we got new data, reset the data array; otherwise, throw an error
					if ( ! empty( $new_tags ) ) {
						$data = $new_tags;
					} else {
						$template = '<span style="line-height: 1; word-spacing: 0;">Error fetching tags. <strong>%s</strong></span>';
						echo wp_kses_post( sprintf( $template, self::$error_message ) );
						exit;
					}
				}
			}

			/**
			 * Filter cloud data before returning.
			 *
			 * @param array $data    The cloud data.
			 * @param int   $post_id The post ID.
			 */
			return apply_filters( 'open_calais_cloud_data', $data, $post_id );
		}


		/**
		 * Get tag cloud-compatible data from a tag object.
		 *
		 * @param array $tag A tag with a name and score.
		 * @return array A tag cloud-compatible object.
		 */
		public static function get_cloud_data( $tag ) {

			$data = [];

			// At the minimum, we need a name and a score
			if ( ! empty( $tag['name'] ) && ! empty( $tag['score'] ) ) {

				// Create some variations of the name
				$name = $tag['name'];
				$slug = sanitize_title( $tag['name'] );

				// Check if we have a score and default to zero if none exists
				$score = ( ! empty( $tag['score'] ) && is_numeric( $tag['score'] ) ) ? $tag['score'] : 0;

				// Add the tag to our new array
				$data = [
					'id'         => 0,
					// Since this isn't coming from the CMS, omit the ID -- it's not necessarily needed
					'url'        => '#',
					// URL is ommitted even for regular tag clouds
					'name'       => $name,
					// The actual display name
					'title'      => $name,
					// Title attribute
					'slug'       => $slug,
					// Generated slug
					'real_count' => 0,
					// Omit this since this is for CMS tags
					'class'      => 'open-calais-' . $slug . ' open-calais-existing-' . wp_json_encode( $tag['existing'] ),
					// Generate a CSS class
					'font_size'  => self::get_font_size( $score ),
					// Calculate font size based on score
				];

			}

			return $data;
		}

		/**
		 * Get tag cloud font size.
		 *
		 * @param float $score The score we need to generate a font size from.
		 * @return float A font size.
		 */
		public static function get_font_size( $score ) {
			$score = (float) $score;

			return self::$font_min + ( ( self::$font_max - self::$font_min ) * $score );
		}

		/**
		 * Get post ID from referer URL.
		 *
		 * @return int The post ID.
		 */
		public static function get_post_id_from_referer() {

			// Default to zero
			$post_id = 0;

			// Make sure correct server var is set
			if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {

				// Break the URL into parts
				$parts = \wp_parse_url( esc_url_raw( $_SERVER['HTTP_REFERER'] ) );

				// Isolate the query string
				if ( ! empty( $parts['query'] ) ) {

					// Parse the query string and put vars into an array
					parse_str( $parts['query'], $vars );

					// Check if the post ID is set and cast to integer
					if ( ! empty( $vars['post'] ) ) {
						$post_id = (int) $vars['post'];
					}
				}
			}

			return $post_id;
		}

		/**
		 * Get tags by post ID
		 *
		 * @param int $post_id The post ID to get tags for
		 *
		 * @return array An array of tags
		 */
		public static function get_tags_by_post_id( $post_id = 0 ) {
			$post = get_post( $post_id );

			return ( ! empty( $post->post_content ) ) ? self::get_tags_by_content( $post->post_content ) : [];
		}

		/**
		 * Get tags for a block of content
		 *
		 * @param string $content The content to send to Open Calais
		 *
		 * @return array An array of tags
		 */
		public static function get_tags_by_content( $content = '' ) {

			// Remove shortcodes.
			$content = strip_shortcodes( $content );

			$tags    = [];
			if ( ! empty( $content ) ) {
				$cache_group = self::CACHE_GROUP;
				$cache_key   = $cache_group . '_' . md5( $content );
				$tags        = wp_cache_get( $cache_key, $cache_group );
				if ( ! $tags ) {
					$tags = self::query_api( $content );
					wp_cache_set( $cache_key, $tags, $cache_group, self::$cache_length);
				}
			}

			return $tags;
		}

		/**
		 * Get tags via API.
		 *
		 * @param string $content The content to send to Open Calais
		 *
		 * @return array An array of tags
		 */
		public static function query_api( $content = '' ) {

			// Array of tags
			$tags = [];

			// Array to keep track of unique entries
			$uniques = [];

			// Make sure API token is set.
			$api_token = get_option( 'open_calais_api_token' );
			if ( empty( $api_token ) ) {
				self::$error_message = 'No API token set!';
				return $tags;
			}

			// Make sure we have content
			if ( ! empty( $content ) ) {

				// Remote post to Open Calais API with headers and content
				$response = wp_remote_post(
					self::API_URL, array(
						'headers' => array(
							'X-AG-Access-Token' => $api_token,
							'Content-Type'      => 'text/raw',
							'outputFormat'      => 'application/json',
						),
						'body'    => wp_strip_all_tags( $content ),
						'timeout' => 10, // Default is 5 seconds, bump to 10 for long articles
					)
				);

				// Make sure we get a response
				if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {

					// Decode the JSON
					$json = json_decode( $response['body'] );

					// Make sure we get JSON back
					if ( is_object( $json ) ) {

						// Cast to an array so we can loop through
						$objects = (array) $json;

						// Go through each object
						foreach ( $objects as $k => $object ) {

							// Skip certain typeGroups
							if ( isset( $object->_typeGroup ) && in_array( $object->_typeGroup, [ 'topics' ], true ) ) {
								unset( $objects[ $k ] );
								continue;
							}

							// Skip certain types
							if ( isset( $object->_type ) && in_array( $object->_type, [ 'City', 'Country', 'Region', 'ProvinceOrState' ], true ) ) {
								unset( $objects[ $k ] );
								continue;
							}

							// Skip certain names
							if ( isset( $object->name ) && in_array( $object->name, [ 'United States' ], true ) ) {
								unset( $objects[ $k ] );
								continue;
							}

							// Get the data
							$data = self::get_tag_from_response_object( $object );

							// Skip low scores
							if ( isset( $data['score'] ) && $data['score'] <= 0.25 ) {
								continue;
							}

							// Make sure we get a valid reponse an add to our array
							if ( ! empty( $data ) && ! empty( $data['name'] ) ) {
								if ( ! in_array( $data['name'], $uniques, true ) ) {
									$tags[]    = $data;
									$uniques[] = $data['name'];
								}
							}
						}
					}

					$names = [];

					// Build array of names
					foreach ( $tags as $key => $tag ) {
						$names[ $key ] = strtolower( $tag['name'] );
					}

					// Temp store for existing tags
					$filtered_tags = [];

					// If set to only match existing tags, query those tags first and filter them.
					if ( (bool) get_option( 'open_calais_show_existing' ) === true && ! empty( $names )) {

						// Query terms by names
						$terms = get_terms(
							'post_tag', [
								'name'       => $names,
								'hide_empty' => false
							]
						);

						foreach ( $terms as $term ) {
							$key = array_search( strtolower( $term->name ), $names, true );
							if ( isset( $key ) && $key !== false ) {
								$filtered_tags[] = $tags[ $key ];
							}
						}
					} else {

						// Return all tags.
						foreach ( $tags as $tag ) {
							$filtered_tags[] = $tag;
						}
					}

					// Set filtered tags as the ones to return
					$tags = $filtered_tags;

					// Sort tags
					uasort( $tags, [ __CLASS__, 'sort_tags' ] );
				} else {
					self::$error_message = 'Network request to API failed.';
				}
			}

			return $tags;
		}

		/**
		 * Get tag data from a response object.
		 *
		 * @param object $object A response object from the API.
		 * @return array Tag data with name and score.
		 */
		public static function get_tag_from_response_object( $object ) {

			$data = [];

			// A _typeGroup and name are the minimum required for us to use
			if ( ! empty( $object->_typeGroup ) && ! empty( $object->name ) ) {

				// Some tags use underscores instead of spaces for some reason
				$data['name']     = str_replace( '_', ' ', $object->name );
				$data['existing'] = false;

				// Different handling for different type groups
				if ( 'socialTag' === $object->_typeGroup ) {

					// Each socialTag is ranked by importance with a value of 1 to 3 (with 1 being most relevant)
					if ( ! empty( $object->importance ) ) {
						$data['score'] = (float) ( 1 / $object->importance );
					}
				} elseif ( 'topics' === $object->_typeGroup ) {

					// Each topic is ranked by a score with a values of 0 to 1 (with 1 being most relevant)
					if ( ! empty( $object->score ) ) {
						$data['score'] = (float) $object->score;
					}
				} elseif ( 'entities' === $object->_typeGroup ) {

					// There are several different kinds of entities -- make sure we have a type
					if ( ! empty( $object->_type ) ) {

						// Person gets special handling
						if ( 'Person' === $object->_type ) {

							// People are ranked by confidence level
							if ( ! empty( $object->confidencelevel ) ) {
								$data['score'] = (float) $object->confidencelevel;
							}
						} else {

							// Other entities are ranked by reference
							if ( ! empty( $object->relevance ) ) {
								$data['score'] = (float) $object->relevance;
							}
						}
					}
				}

				$existing = get_term_by( 'name', $data['name'], 'post_tag' );
				if ( $existing ) {
					$data['existing'] = true;
					$data['term_id']  = $existing->term_id;
				}
			}

			return $data;
		}

		/**
		 * Callback function by sorting an array of tags.
		 *
		 * @param array $a The first Open Calais object to be sorted.
		 * @param array $b The second Open Calais object to be sorted.
		 * @return int -1 if first object first, 1 if second, 0 if equal.
		 */
		public static function sort_tags( $a, $b ) {

			// Default to zeo
			$int = 0;

			// Make sure the names of both objects to compare are set
			if ( ! empty( $a['name'] ) && ! empty( $b['name'] ) ) {

				// Lowercase everything so casing doesn't affect sorting
				$a_comp = strtolower( $a['name'] );
				$b_comp = strtolower( $b['name'] );

				// Check which comes first alphabetically
				if ( $a_comp < $b_comp ) {
					$int = -1;
				} elseif ( $a_comp > $b_comp ) {
					$int = 1;
				}
			}

			return $int;
		}
	}

	Admin::load();

endif;
