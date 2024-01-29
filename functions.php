<?php

/**
 * Special PHP functions.
 *
 * @copyright Copyright (C) 2020 Ondrej Golasowski, Petr Kucera, Matyas Koc and contributors
 * @license   GPL(GPLv3)
 */

/**
 * Remove empty paragraphs created by wpautop()
 *
 * @author Ryan Hamilton
 * @link   https://gist.github.com/Fantikerz/5557617
 */
function remove_empty_p($content) {
    $content = force_balance_tags($content);
    $content = preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
    $content = preg_replace('~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content);
    return $content;
}

//Attach filter to the contents of articles.
add_filter('the_content', 'remove_empty_p', 0, 1);
//Allow post thumbnails.
add_theme_support('post-thumbnails');

/**
 * Attach a version suffix to the url of the style.css to force browsers to refresh CSS.
 *
 * @author Ondrej Golasowski
 */
function get_css_name() {
    $version = 36;
    return "/style.css?rnd=" . $version;
}

/**
 * Attach a version suffix to the url of the main.js to force browsers to refresh JS.
 *
 * @author Ondrej Golasowski
 */
function get_js_name() {
    $version = 4;
    return "/assets/js/main.js?rnd=" . $version;
}

/**
 * Attach a version suffix to the url of the campaign-banner.js to force browsers to refresh JS.
 *
 * @author Petr Kucera
 */
function get_js_campaign_banner_name() {
   $version = 4;
   return "/assets/js/campaign-banner.js?rnd=" . $version;
}

/**
 * Return the page slag by ID
 * 
 * @author Petr Kucera
 */
function get_id_by_slug($page_slug, $slug_page_type = 'page'){
   $find_page = get_page_by_path($page_slug, OBJECT, $slug_page_type);
   if ($find_page) {
      return $find_page->ID;
   } else {
      return null;
   }
}

/**
 * Check if the page has mother function
 * 
 * @author Petr Kucera
 */
function is_subpage($page_name){
   if(is_page($page_name)) return false;
   $id = get_the_ID();
   while(wp_get_post_parent_id($id) != 0){
      $id = wp_get_post_parent_id($id);
   }
   if($id == get_id_by_slug($page_name)) return true;
   return false;
}

/**
 * Create multiple socials icons.
 *
 * @param int Count of icons.
 *
 * @author Ondrej Golasowski
 */
function stcblog_add_socials($count, $customizer) {

    for ($id = 1; $id <= $count; $id++) {

        // Adding a template setting for a logo.
        $customizer->add_setting('settings_footer_logo' . $id, [
            'default' => ''
        ]);
        // Adding a template setting for a link.
        $customizer->add_setting('settings_footer_link' . $id, [
            'default' => ''
        ]);

        //Adding an image upload control to the Customizer.
        $customizer->add_control(new WP_Customize_Image_Control($customizer, 'control_footer_logo' . $id, [
            'label' => __('Socials ' . $id . ' - logo', 'stcblog'),
            'section' => 'section_footer',
            'settings' => 'settings_footer_logo' . $id
        ]));

        //Adding an url input control to the Customizer.
        $customizer->add_control(new WP_Customize_Control($customizer, 'control_footer_link' . $id, [
            'label' => __('Socials ' . $id . ' - link', 'stcblog'),
            'section' => 'section_footer',
            'settings' => 'settings_footer_link' . $id,
            'type' => 'url'
        ]));
    }
}

/**
 * Add GDPR link to footer.
 *
 * @author Ondrej Golasowski
 */
function stcblog_add_gdpr($customizer) {

    // Adding a template setting for a text.
    $customizer->add_setting('settings_footer_gdpr_text', [
        'default' => ''
    ]);
    // Adding a template setting for a link.
    $customizer->add_setting('settings_footer_gdpr_link', [
        'default' => ''
    ]);

    //Adding an text input upload control to the Customizer.
    $customizer->add_control(new WP_Customize_Control($customizer, 'control_footer_gdpr_text', [
        'label' => __('GDPR text', 'stcblog'),
        'section' => 'section_footer',
        'settings' => 'settings_footer_gdpr_text'
    ]));

    //Adding an url input control to the Customizer.
    $customizer->add_control(new WP_Customize_Control($customizer, 'control_footer_gdpr_link', [
        'label' => __('GDPR link', 'stcblog'),
        'section' => 'section_footer',
        'settings' => 'settings_footer_gdpr_link',
        'type' => 'url'
    ]));
}

/**
 * Add MarCom mail to footer.
 *
 * @author Petr Kučera
 */
function stcblog_add_MarCommail($customizer) {

    //Adding a template setting for a MarCom mail.
    $customizer->add_setting('settings_footer_MarCommail', [
        'default' => ''
    ]);

    //Adding an text input upload control to the Customizer.
    $customizer->add_control(new WP_Customize_Control($customizer, 'settings_footer_MarCommail', [
        'label' => __('MarCom mail', 'stcblog'),
        'section' => 'section_footer',
        'description' => 'Contact email for MarCom AG.',
        'settings' => 'settings_footer_MarCommail'
    ]));
}

//Adding functions to the Customizer.
function stcblog_customize_register($wp_customize) {

    // ***********************************
    // Footer section
    // ***********************************
    // Adding a section to the Customizer.
    $wp_customize->add_section('section_footer', [
        'title' => __('Footer', 'stcblog'),
        'priority' => 30,
    ]);
    // ===================================
    // Custom copyright text
    // ===================================
    // Adding the template setting.
    $wp_customize->add_setting('settings_footer_copyright', [
        'default' => ' STC, Všechna práva vyhrazena'
    ]);

    //Adding a control to the Customizer.
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'control_footer_copyright', [
        'label' => __('Copyright text', 'stcblog'),
        'section' => 'section_footer',
        'settings' => 'settings_footer_copyright'
    ]));

    // ===================================
    // Custom GDPR link
    // ===================================
    stcblog_add_gdpr($wp_customize);

    // ===================================
    // Custom MarCom mail
    // ===================================
    stcblog_add_MarCommail($wp_customize);

    // ===================================
    // Custom socials icons
    // ===================================
    stcblog_add_socials(5, $wp_customize);
}

add_action('customize_register', 'stcblog_customize_register');

/**
 * Calculates reading time according to the article word count.
 *
 * @return int Reading time.
 * @author Ondrej Golasowski
 */
function reading_time() {
    //Get contents of the current article.
    $content = get_post_field('post_content', $post->ID);
    $word_count = str_word_count(strip_tags($content));
    $readingtime = ceil($word_count / 200);

    //Chooses correct word for specified interval.
    switch ($readingtime) {
        case 1:
            $timer = " minuta";
            break;
        case 2:
        case 3:
        case 4:
            $timer = " minuty";
            break;
        default:
            $timer = " minut";
            break;
    }

    $totalreadingtime = $readingtime . $timer;

    return $totalreadingtime;
}


/**
 * Remove core update notifications for common users
 *
 * @author Petr Kučera
 */
function show_updated_only_to_admins() {
    if (!current_user_can('update_core')) {
        remove_action('admin_notices', 'update_nag', 3);
        remove_action('network_admin_notices', 'update_nag', 3);
    }
}

add_action('admin_head', 'show_updated_only_to_admins', 1);

/**
 * Add multiple authors
 * using Co-Author Plus plugin
 *
 * @author Petr Kučera
 */

if (function_exists('coauthors_posts_links')) {
    coauthors_posts_links();
} else {
    the_author_posts_link();
}

/**
 * Desible admin bar on website, because of caching
 *
 * @author Petr Kucera, Matyas Koc
 */
show_admin_bar(false);

/**
 * Remove info about wordpress for better security
 *
 * @author Petr Kucera, Matyas Koc
 */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');

if (is_user_logged_in() == false) {

    remove_action('wp_head', 'rest_output_link_wp_head', 10);

}

/**
 * Add more allowed mime types
 * DO NOT CHANGE WITHOUT APPROVAL
 *
 * @author Matyas Koc
 */
function additional_mime($mime_types) {
    $mime_types['mcworld'] = 'application/zip';
    $mime_types['zip'] = 'application/zip';
    return $mime_types;
}

add_filter('upload_mimes', 'additional_mime', 99);

function add_allow_upload_extension_exception($types, $file, $filename, $mimes) {
    // Do basic extension validation and MIME mapping
    $wp_filetype = wp_check_filetype($filename, $mimes);
    $ext = $wp_filetype['ext'];
    $type = $wp_filetype['type'];
    if (in_array($ext, array('mcworld', 'zip'))) { // Overrident extensions
        $types['ext'] = $ext;
        $types['type'] = $type;
    }
    return $types;
}

add_filter('wp_check_filetype_and_ext', 'add_allow_upload_extension_exception', 99, 4);

/**
 * Remove footer in admin-portal
 *
 * @author Matyas Koc
 */
add_filter('admin_footer_text', '__return_empty_string', 11);
add_filter('update_footer', '__return_empty_string', 11);
add_filter('admin_footer_text', '__return_empty_string');

/**
 * Disable application passwords
 *
 * @author Matyas Koc
 */
add_filter('wp_is_application_passwords_available', '__return_false');

/**
 * Disable XML-RPC
 *
 * @author Matyas Koc
 */
add_filter('xmlrpc_methods', function($methods) {
    unset($methods['pingback.ping']);
    return $methods;
});
add_filter('wp_headers', 'disable_x_pingback');
function disable_x_pingback($headers) {
    unset($headers['X-Pingback']);
    return $headers;
}
add_filter('xmlrpc_enabled', '__return_false');


/**
 * Remove Gutenberg Block Library CSS from loading on the frontend
 * 
 * @author Petr Kucera
 */
function smartwp_remove_wp_block_library_css(){
    // in singles and pages its solved by overwriting html :where(img) class attribute in _global.scss
    if (!is_single() && !is_page()) {
        wp_dequeue_style( 'wp-block-library' );
        // wp_dequeue_style( 'wp-block-library-theme' );
        // wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
    }
}
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );

/** 
 * Add a listen button to each post
 * 
 * @author Filip Cerny
 */
function add_listen_btn($content){
    if (is_single()) {
        $listen_btn = "<audio id=\"play_button\"controls=\"true\" src=\"empty\" class= \"article-audio\"></audio>";
        return $listen_btn . $content;
    }
    return $content;
}
add_filter('the_content', 'add_listen_btn', 20, 1);

/**
 * Disable WordPress emojis
 * 
 * @author Matyáš Koc, Petr Kucera
 */
function disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('embed_head', 'print_emoji_detection_script');
}
add_action('init', 'disable_emojis');

?>
