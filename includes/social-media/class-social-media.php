<?php

if ( ! class_exists( 'SKSocialMedia' ) ) :

	/**
	 * SKSocialMedia class.
	 *
	 * @since 1.4
	*/
	class SKSocialMedia {

		/**
		 * List of social media profiles.
		 *
		 * @since 1.4
		 * @var array
		 */
		protected $social_media_profiles = array();

		/**
		 * The single instance of the class.
		 *
		 * @since 1.4
		 * @var SKSocialMedia
		*/
		protected static $_instance = null;

		/**
		 * SKSocialMedia constructor.
		 *
		 * @since 1.4
		*/
		public function __construct() {
			
			if( !get_option( 'social_media_options_import', false ) ) {
				$done_import = $this->import_options();
				if( $done_import ) {
					update_option( 'social_media_options_import', true );
				}
			}

			$this->enqueue_styles();
			$this->set_profiles();
			$this->customizer_options();
			$this->create_shortcode();
			$this->create_wb_element();
		}

		/**
		 * Ensures only one instance of SKSocialMedia is loaded or can be loaded.
		 *
		 * @since 1.4
		 *
		 * @return SKSocialMedia
		*/
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Enqueue styles.
		 *
		 * @since 1.4
		 * @return void
		*/
		protected function enqueue_styles() {
			add_action( 'wp_enqueue_scripts', function() {
				wp_enqueue_style('sk-social-media-styles', plugins_url( 'assets/css/social-media.css', __FILE__ ), NULL );
			});
		}

		/**
		 * Imports social media links stored as theme mods into the options WP table.
		 *
		 * @since 1.4
		 * @return void
		 */
		private function import_options() {

			$done_import = true;

			foreach( $this->social_media_profiles as $social) {
				if( get_theme_mod( $social['link'] ) ) {
					update_option( 'sk_' . $social['link'], get_theme_mod( $social['link'], '' ) );
					if( get_option( 'sk_' . $social['link'] ) ) {
						remove_theme_mod( $social['link'] );
					} else {
						$done_import = false;
					}
				}
			}

			if( get_theme_mod( 'top_bar_social_icons' ) ) {
				update_option( 'sk_top_bar_social_icons', get_theme_mod( 'top_bar_social_icons' ) );
				if( get_option( 'sk_top_bar_social_icons' ) ) {
					remove_theme_mod( 'top_bar_social_icons' );
				} else {
					$done_import = false;
				}
			}

			if( get_theme_mod( 'footer_social_icons' ) ) {
				update_option( 'sk_footer_social_icons', get_theme_mod( 'footer_social_icons' ) );
				if( get_option( 'sk_footer_social_icons' ) ) {
					remove_theme_mod( 'footer_social_icons' );
				} else {
					$done_import = false;
				}
			}

			return $done_import;
		}

		/**
		 * Sets the social media profile array.
		 *
		 * @since 1.4
		 * @return void
		 */
		protected function set_profiles() {

			$this->social_media_profiles = array(
			    array( 
			        'link' 		=> 'facebook_link',
			        'slug' 		=> 'facebook',
			        'svg_path'  => 'd="M17.525,9H14V7c0-1.032,0.084-1.682,1.563-1.682h1.868v-3.18C16.522,2.044,15.608,1.998,14.693,2 C11.98,2,10,3.657,10,6.699V9H7v4l3-0.001V22h4v-9.003l3.066-0.001L17.525,9z"',
			        'icon' 		=> 'spk-icon-facebook-f',
			        'name' 		=> 'Facebook'
			    ),
			    array( 
			        'link' 		=> 'pinterest_link',
			        'slug' 		=> 'pinterest',
			        'svg_path'	=> 'd="M 12 2 C 6.477 2 2 6.477 2 12 C 2 17.523 6.477 22 12 22 C 17.523 22 22 17.523 22 12 C 22 6.477 17.523 2 12 2 z M 12 4 C 16.418 4 20 7.582 20 12 C 20 16.418 16.418 20 12 20 C 11.264382 20 10.555494 19.892969 9.8789062 19.707031 C 10.09172 19.278284 10.282622 18.826454 10.386719 18.425781 C 10.501719 17.985781 10.972656 16.191406 10.972656 16.191406 C 11.278656 16.775406 12.173 17.271484 13.125 17.271484 C 15.958 17.271484 18 14.665734 18 11.427734 C 18 8.3227344 15.467031 6 12.207031 6 C 8.1520313 6 6 8.7215469 6 11.685547 C 6 13.063547 6.73325 14.779172 7.90625 15.326172 C 8.08425 15.409172 8.1797031 15.373172 8.2207031 15.201172 C 8.2527031 15.070172 8.4114219 14.431766 8.4824219 14.134766 C 8.5054219 14.040766 8.4949687 13.958234 8.4179688 13.865234 C 8.0299688 13.394234 7.71875 12.529656 7.71875 11.722656 C 7.71875 9.6496562 9.2879375 7.6445312 11.960938 7.6445312 C 14.268937 7.6445313 15.884766 9.2177969 15.884766 11.466797 C 15.884766 14.007797 14.601641 15.767578 12.931641 15.767578 C 12.009641 15.767578 11.317063 15.006312 11.539062 14.070312 C 11.804063 12.953313 12.318359 11.747406 12.318359 10.941406 C 12.318359 10.220406 11.932859 9.6191406 11.130859 9.6191406 C 10.187859 9.6191406 9.4296875 10.593391 9.4296875 11.900391 C 9.4296875 12.732391 9.7109375 13.294922 9.7109375 13.294922 C 9.7109375 13.294922 8.780375 17.231844 8.609375 17.964844 C 8.5246263 18.326587 8.4963381 18.755144 8.4941406 19.183594 C 5.8357722 17.883113 4 15.15864 4 12 C 4 7.582 7.582 4 12 4 z"',
			        'icon' 		=> 'spk-icon-pinterest',
			        'name' 		=> 'Pinterest'
			    ),
			    array( 
			        'link' 		=> 'linkedin_link',
			        'slug' 		=> 'linkedin',
			        'svg_path'	=> 'd="M9,25H4V10h5V25z M6.501,8C5.118,8,4,6.879,4,5.499S5.12,3,6.501,3C7.879,3,9,4.121,9,5.499C9,6.879,7.879,8,6.501,8z M27,25h-4.807v-7.3c0-1.741-0.033-3.98-2.499-3.98c-2.503,0-2.888,1.896-2.888,3.854V25H12V9.989h4.614v2.051h0.065 c0.642-1.18,2.211-2.424,4.551-2.424c4.87,0,5.77,3.109,5.77,7.151C27,16.767,27,25,27,25z"',
			        'icon' 		=> 'spk-icon-linkedin',
			        'name' 		=> 'Linkedin'
			    ),
			    array( 
			        'link' 		=> 'twitter_link',
			        'slug' 		=> 'twitter',
			        'svg_path'	=> '',
			        'icon'		=> 'spk-icon-twitter',
			        'name' 		=> 'Twitter'
			    ),
			    array( 
			        'link' 		=> 'googleplus_link',
			        'slug' 		=> 'googleplus',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-google-plus',
			        'name' 		=> 'Google+'
			    ),
			    array( 
			        'link' 		=> 'rss_link',
			        'slug' 		=> 'rss',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-rss',
			        'name' 		=> 'RSS'
			    ),
			    array( 
			        'link' 		=> 'tumblr_link',
			        'slug' 		=> 'tumblr',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-tumblr',
			        'name' 		=> 'Tumblr'
			    ),
			    array( 
			        'link' 		=> 'instagram_link',
			        'slug' 		=> 'instagram',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-instagram',
			        'name' 		=> 'Instagram'
			    ),
			    array( 
			        'link' 		=> 'youtube_link',
			        'slug' 		=> 'youtube',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-youtube',
			        'name' 		=> 'Youtube'
			    ),
			    array( 
			        'link' 		=> 'vimeo_link',
			        'slug' 		=> 'vimeo',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-vimeo',
			        'name' 		=> 'Vimeo'
			    ),
			    array( 
			        'link' 		=> 'behance_link',
			        'slug' 		=> 'behace',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-behance',
			        'name' 		=> 'Behance'
			    ),
			    array( 
			        'link' 		=> 'dribbble_link',
			        'slug' 		=> 'dribbble',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-dribbble',
			        'name' 		=> 'Dribbble'
			    ),
			    array( 
			        'link' 		=> 'flickr_link',
			        'slug' 		=> 'flickr',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-flickr',
			        'name' 		=> 'Flickr'
			    ),
			    array( 
			        'link' 		=> 'git_link',
			        'slug' 		=> 'github',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-github',
			        'name' 		=> 'Git'
			    ),
			    array( 
			        'link' 		=> 'skype_link',
			        'slug' 		=> 'skype',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-skype',
			        'name' 		=> 'Skype'
			    ),
			    array( 
			        'link' 		=> 'weibo_link',
			        'slug' 		=> 'weibo',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-sina-weibo',
			        'name' 		=> 'Weibo'
			    ),
			    array( 
			        'link' 		=> 'foursquare_link',
			        'slug' 		=> 'foursquare',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-foursquare',
			        'name' 		=> 'Foursquare'
			    ),
			    array( 
			        'link' 		=> 'soundcloud_link',
			        'slug' 		=> 'soundcloud',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-soundcloud',
			        'name' 		=> 'Soundcloud'
			    ),
			    array( 
			        'link' 		=> 'vk_link',
			        'slug' 		=> 'vk',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-vk',
			        'name' 		=> 'VK'
			    ),
			    array( 
			        'link' 		=> 'houzz_link',
			        'slug' 		=> 'houzz',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-houzz',
			        'name' 		=> 'Houzz'
			    ),
			    array( 
			        'link' 		=> 'naver_line_link',
			        'slug' 		=> 'naver',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon spk-icon-naver-line-logo',
			        'name' 		=> 'Naver Line'
			    ),
			    array( 
			        'link' 		=> 'tripadvisor_link',
			        'slug' 		=> 'tripadvisor',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-tripadvisor',
			        'name' 		=> 'TripAdvisor'
			    ),
			    array( 
			        'link' 		=> 'wechat_link',
			        'slug' 		=> 'wechat',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-wechat',
			        'name' 		=> 'WeChat'
			    ),
			    array( 
			        'link' 		=> 'whatsapp_link',
			        'slug' 		=> 'whatsapp',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-whatsapp',
			        'name' 		=> 'WhatsApp'
			    ),
			    array( 
			        'link' 		=> 'telegram_link',
			        'slug' 		=> 'telegram',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-telegram',
			        'name' 		=> 'Telegram'
			    ),
			    array( 
			        'link' 		=> 'viber_link',
			        'slug' 		=> 'viber',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-viber',
			        'name' 		=> 'Viber'
			    ),
			    array( 
			        'link' 		=> 'spotify_link',
			        'slug' 		=> 'spotify',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-spotify',
			        'name' 		=> 'Spotify'
			    ),
			    array( 
			        'link' 		=> 'bandcamp_link',
			        'slug' 		=> 'bandcamp',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-bandcamp',
			        'name' 		=> 'Bandcamp'
			    ),
			    array( 
			        'link' 		=> 'snapchat_link',
			        'slug' 		=> 'snapchat',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-snapchat',
			        'name' 		=> 'Snapchat'
			    ),
			    array( 
			        'link' 		=> 'medium_link',
			        'slug' 		=> 'medium',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-medium-monogram-filled',
			        'name' 		=> 'Medium'
			    ),
			    array( 
			        'link' 		=> 'facebook_messenger_link',
			        'slug' 		=> 'facebook-messenger',
			        'svg_path'	=> '',
			        'icon' 		=> 'spk-icon-facebook-messenger',
			        'name' 		=> 'Facebook Messenger'
			    )
			);
		}

		/**
		 * Registers customizer options.
		 *
		 * @since 1.4
		 * @return void
		 */
		protected function customizer_options() {
			add_action( 'customize_register', array( $this, 'sk_social_media_customizer' ) );
		}

		/**
		 * Creates customizer options.
		 *
		 * @since 1.4
		 * @return void
		 */
		public function sk_social_media_customizer( $wp_customize ) {

			global $social_media_profiles;

			$theme = wp_get_theme();
			if ( $theme->template == 'shopkeeper') {

				$wp_customize->add_setting( 'sk_top_bar_social_icons', array(
					'type'		 => 'option',
					'capability' => 'manage_options',
					'transport'  => 'refresh',
				) );

				$wp_customize->add_control( 
					new WP_Customize_Toggle_Control(
						$wp_customize,
						'sk_top_bar_social_icons',
						array( 
							'label'       	=> esc_attr__( 'Top Bar Social Icons', 'shopkeeper-extender' ),
							'section'     	=> 'top_bar',
							'priority'    	=> 20,
							'default'		=> false,
						)
					)
				);

				$wp_customize->add_setting( 'sk_footer_social_icons', array(
					'type'		 => 'option',
					'capability' => 'manage_options',
					'transport'  => 'refresh',
				) );

				$wp_customize->add_control( 
					new WP_Customize_Toggle_Control(
						$wp_customize,
						'sk_footer_social_icons',
						array( 
							'label'       	=> esc_attr__( 'Social Networking Icons', 'shopkeeper-extender' ),
							'section'     	=> 'footer',
							'priority'    	=> 20,
							'default'		=> false,
						)
					)
				);
			}

			// Section
			$wp_customize->add_section( 'social_media', array(
		 		'title'       => esc_attr__( 'Social Media', 'shopkeeper-extender' ),
		 		'priority'    => 10,
		 	) );

			// Fields
			foreach($this->social_media_profiles as $social) :

				$wp_customize->add_setting( 'sk_' . $social['link'], array(
					'type'		 => 'option',
					'capability' => 'manage_options',
					'transport'  => 'refresh',
					'default' 	 => '',
				) );

				$wp_customize->add_control( 
					new WP_Customize_Control(
						$wp_customize,
						'sk_' . $social['link'],
						array( 
							'type'			=> 'text',
							'label'       	=> esc_attr__( $social['name'], 'shopkeeper-extender' ),
							'section'     	=> 'social_media',
							'priority'    	=> 10,
						)
					)
				);

			endforeach;
		}

		/**
		 * Adds social media shortcode.
		 *
		 * @since 1.4
		 * @return void
		 */
		protected function create_shortcode() {
			add_shortcode( 'social-media', array( $this, 'sk_social_media_shortcode' ) );
		}

		/**
		 * Creates social media shortcode.
		 *
		 * @since 1.4
		 * @return string
		 */
		public function sk_social_media_shortcode( $atts, $content = null ) {
			global $social_media_profiles;

		    extract( shortcode_atts( array(
		        'items_align' => 'left',
		        'type'		  => 'shortcode',
		        'fontsize'    => '24',
                'fontcolor'   => '#000',
		        ), 
		    $atts ) );

		    $style = '';
		    if( $type == 'block') {
		    	if( !empty($fontsize) ) {
			    	$style .= 'font-size:' . $fontsize . 'px;';
			    }

			    if( !empty($fontcolor) ) {
			    	$style .= 'color:' . $fontcolor . ';';
			    }

			    if( !empty($style) ) {
			    	$style = 'style="' . $style . '"';
			    }
		    }

		    ob_start();

		    ?>

	        <ul class="sk_social_icons_list <?php echo esc_html($items_align); ?>">

	            <?php foreach($this->social_media_profiles as $social) : ?>

	                <?php if ( get_option( 'sk_' . $social['link'] ) ) : ?>
	                    
	                    <li class="sk_social_icon">
	                        <a class="sk_social_icon_link" target="_blank" 
	                        	href="<?php echo esc_url(get_option( 'sk_' . $social['link'], '#' )); ?>" 
	                        	<?php echo $style; ?>>
	                        	<!-- <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
									width="24" height="24"
									viewBox="0 0 24 24">    
									<path <?php // echo $social['svg_path']; ?>></path>
								</svg> -->
	                            <i class="<?php echo $social['icon']; ?>"></i>
	                        </a>
	                    </li>

	                <?php endif; ?>

	            <?php endforeach; ?>

	        </ul>
		    
		    <?php

		    $content = ob_get_contents();

		    ob_end_clean();

		    return $content;
		}

		/**
		 * Adds social media as a WPBakery element.
		 *
		 * @since 1.4
		 * @return void
		 */
		protected function create_wb_element() {

			vc_map( 
				array(
				   "name"			=> "Social Media Profiles",
				   "category"		=> 'Social',
				   "description"	=> "Links to your social media profiles",
				   "base"			=> "social-media",
				   "class"			=> "",
				   "icon"			=> "social-media",
				   "params" 		=> array(
						array(
							"type"			=> "dropdown",
							"holder"		=> "div",
							"class" 		=> "hide_in_vc_editor",
							"admin_label" 	=> true,
							"heading"		=> "Align",
							"param_name"	=> "items_align",
							"value"			=> array(
								"Left"		=> "left",
								"Center"	=> "center",
								"Right"		=> "right"
							)
						)
				   )
				)
			);
		}
	}

endif;

$sk_social_media = new SKSocialMedia;