<?php

function shopkeeper_socials($atts, $content = null) {   
    extract(shortcode_atts(array(
        'items_align' => 'left'
    ), $atts));
    ob_start();

    global $social_media_profiles;

    ?>

    <div class="site-social-icons-shortcode">
        <ul class="<?php echo esc_html($items_align); ?>">

            <?php foreach($social_media_profiles as $social) : ?>

                <?php if ( get_option( 'sk_' . $social['link'] ) ) : ?>
                    
                    <li class="site-social-icons-<?php echo $social['slug']; ?>">
                        <a target="_blank" href="<?php echo esc_url(get_option( 'sk_' . $social['link'], '#' )); ?>">
                            <i class="<?php echo $social['icon']; ?>"></i>
                            <span><?php echo $social['name']; ?></span>
                        </a>
                    </li>

                <?php endif; ?>

            <?php endforeach; ?>

        </ul>
    </div>
    
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode('social-media', 'shopkeeper_socials');