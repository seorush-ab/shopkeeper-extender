<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//==============================================================================
//  Enqueue Editor Assets
//==============================================================================
add_action( 'enqueue_block_editor_assets', 'gbt_18_sk_social_media_editor_assets' );
if ( ! function_exists( 'gbt_18_sk_social_media_editor_assets' ) ) {
    function gbt_18_sk_social_media_editor_assets() {
    	
        wp_register_script(
            'gbt_18_sk_social_media_script',
            plugins_url( 'block.js', __FILE__ ),
            array( 'wp-blocks', 'wp-components', 'wp-editor', 'wp-i18n', 'wp-element' )
        );

        wp_register_style(
            'gbt_18_sk_social_media_editor_styles',
            plugins_url( 'assets/css/editor.css', __FILE__ ),
            array( 'wp-edit-blocks' ),
            filemtime(plugin_dir_path(__FILE__) . 'assets/css/editor.css')
        );
    }
}

//==============================================================================
//  Enqueue Frontend Assets
//==============================================================================
add_action( 'enqueue_block_assets', 'gbt_18_sk_social_media_assets' );
if ( ! function_exists( 'gbt_18_sk_social_media_assets' ) ) {
    function gbt_18_sk_social_media_assets() {
        
        wp_enqueue_style(
            'gbt_18_sk_social_media_styles',
            plugins_url( 'assets/css/style.css', __FILE__ ),
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'assets/css/style.css')
        );
    }
}

//==============================================================================
//  Frontend Output
//==============================================================================
if ( function_exists( 'register_block_type' ) ) {
    register_block_type( 'getbowtied/sk-social-media-profiles', array(
        'editor_style'      => 'gbt_18_sk_social_media_editor_styles',
        'editor_script'     => 'gbt_18_sk_social_media_script',
    	'attributes'            => array(
    		'align'			    => array(
    			'type'          => 'string',
    			'default'		=> 'left',
    		),
            'fontSize'          => array(
                'type'          => 'number',
                'default'       => '24',
            ),
            'fontColor'         => array(
                'type'          => 'string',
                'default'       => '#000',
            ),
    	),

    	'render_callback' => 'gbt_18_sk_social_media_frontend_output',
    ) );
}

if ( ! function_exists( 'gbt_18_sk_social_media_frontend_output' ) ) {
    function gbt_18_sk_social_media_frontend_output($attributes) {

    	global $shopkeeper_theme_options, $social_media_profiles;

    	extract(shortcode_atts(
    		array(
    			"align"      => 'left',
                'fontSize'   => '24',
                'fontColor'  => '#000',
    		), $attributes));
        ob_start();

        ?>

        <div class="gbt_18_sk_social_media_wrapper">
            <ul class="gbt_18_sk_social_media_list <?php echo esc_html($align); ?>">
                <?php foreach($social_media_profiles as $social) : ?>
                	<?php if ( (isset($shopkeeper_theme_options[$social['link']])) && (trim($shopkeeper_theme_options[$social['link']]) != "" ) ) : ?>
                		<li class="gbt_18_sk_social_media_item">
                		    <a class="gbt_18_sk_social_media_link" 
                               target="_blank"
                               style="color:<?php echo $fontColor?>; font-size:<?php echo $fontSize?> px"
                               href="<?php echo esc_url($shopkeeper_theme_options[$social['link']]); ?>">
                                <i class="<?php echo $social['icon']; ?>"></i>
                    		    <span class="<?php echo $social['icon']; ?>"></span>
                		    </a>
                        </li>
                	<?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

    	<?php return ob_get_clean();
    }
}