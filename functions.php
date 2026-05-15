<?php

/* =====================
   ENQUEUE ASSETS
   ===================== */
function touris_enqueue() {
  // Google Fonts
  wp_enqueue_style('google-fonts',
    'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Questrial&display=swap',
    array(), null
  );

  // Glide.js
  wp_enqueue_style('glide-css',
    'https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/css/glide.core.min.css',
    array(), '3.6.0'
  );
  wp_enqueue_script('glide-js',
    'https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/glide.min.js',
    array(), '3.6.0', true
  );

  // Font Awesome
  wp_enqueue_style('font-awesome',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
    array(), '6.5.0'
  );

  // Theme stylesheet
  wp_enqueue_style('touris-style', get_stylesheet_uri(), array(), '1.0');

  // Theme JS
  wp_enqueue_script('touris-js',
    get_theme_file_uri('/assets/theme.js'),
    array(), '1.0', true
  );
}
add_action('wp_enqueue_scripts', 'touris_enqueue');


/* =====================
   THEME SUPPORTS
   ===================== */
function touris_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('custom-logo');
  add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));

  register_nav_menus(array(
    'primary' => __('Primary Menu', 'touris-club'),
    'footer'  => __('Footer Menu', 'touris-club'),
  ));
}
add_action('after_setup_theme', 'touris_setup');

// Make page templates visible in the block editor
function touris_page_templates($templates) {
  $templates['page-contact.php'] = 'Contact Page';
  $templates['page-shop.php'] = 'Shop Page';
  return $templates;
}
add_filter('theme_page_templates', 'touris_page_templates');

/* =====================
   CUSTOM POST TYPE: PRODUCTS
   ===================== */
function touris_register_products() {
  register_post_type('product', array(
    'labels' => array(
      'name'          => 'Products',
      'singular_name' => 'Product',
      'add_new'       => 'Add Product',
      'add_new_item'  => 'Add New Product',
      'edit_item'     => 'Edit Product',
      'all_items'     => 'All Products',
      'menu_name'     => 'Shop Products',
    ),
    'public'       => true,
    'has_archive'  => false,
    'menu_icon'    => 'dashicons-cart',
    'supports'     => array('title', 'thumbnail'),
    'rewrite'      => array('slug' => 'products'),
    'show_in_rest' => true,
  ));
}
add_action('init', 'touris_register_products');


/* =====================
   ACF: HOMEPAGE FIELDS
   ===================== */
add_action('acf/init', 'touris_acf_fields');

function touris_acf_fields() {
  if (!function_exists('acf_add_local_field_group')) return;

  // ---- HERO CAROUSEL SLIDES (individual, up to 6) ----
  $hero_fields = array();
  for ($i = 1; $i <= 6; $i++) {
    $hero_fields[] = array('key' => "field_tc_slide_{$i}_tab",     'label' => "Slide {$i}",           'name' => '',                       'type' => 'tab');
    $hero_fields[] = array('key' => "field_tc_slide_{$i}_type",    'label' => 'Media Type',            'name' => "slide_{$i}_type",        'type' => 'select', 'choices' => array('image' => 'Image', 'video' => 'Video'), 'default_value' => 'image');
    $hero_fields[] = array('key' => "field_tc_slide_{$i}_image",   'label' => 'Background Image',      'name' => "slide_{$i}_image",       'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium', 'conditional_logic' => array(array(array('field' => "field_tc_slide_{$i}_type", 'operator' => '==', 'value' => 'image'))));
    $hero_fields[] = array('key' => "field_tc_slide_{$i}_video",   'label' => 'Background Video',      'name' => "slide_{$i}_video",       'type' => 'file', 'return_format' => 'array', 'mime_types' => 'mp4,webm,mov', 'conditional_logic' => array(array(array('field' => "field_tc_slide_{$i}_type", 'operator' => '==', 'value' => 'video'))));
    $hero_fields[] = array('key' => "field_tc_slide_{$i}_poster",  'label' => 'Video Poster Image',    'name' => "slide_{$i}_poster",      'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium', 'conditional_logic' => array(array(array('field' => "field_tc_slide_{$i}_type", 'operator' => '==', 'value' => 'video'))));
    $hero_fields[] = array('key' => "field_tc_slide_{$i}_eyebrow", 'label' => 'Eyebrow (italic)',      'name' => "slide_{$i}_eyebrow",     'type' => 'text', 'placeholder' => 'e.g. Since 1985');
    $hero_fields[] = array('key' => "field_tc_slide_{$i}_title",   'label' => 'Title',                 'name' => "slide_{$i}_title",       'type' => 'text', 'placeholder' => 'e.g. Welcome to Touris Club');
    $hero_fields[] = array('key' => "field_tc_slide_{$i}_sub",     'label' => 'Subtitle',              'name' => "slide_{$i}_subtitle",    'type' => 'textarea', 'rows' => 2);
    $hero_fields[] = array('key' => "field_tc_slide_{$i}_btn",     'label' => 'Button Text',           'name' => "slide_{$i}_btn_text",    'type' => 'text', 'placeholder' => 'e.g. Explore');
    $hero_fields[] = array('key' => "field_tc_slide_{$i}_link",    'label' => 'Button Link',           'name' => "slide_{$i}_btn_link",    'type' => 'url');
  }

  acf_add_local_field_group(array(
    'key'      => 'group_tc_hero',
    'title'    => '🏠 Homepage – Hero Carousel',
    'fields'   => $hero_fields,
    'location' => array(
      array(array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page')),
    ),
    'menu_order' => 0,
  ));

  // ---- INTRO SECTION ----
  acf_add_local_field_group(array(
    'key'      => 'group_tc_intro',
    'title'    => '🏠 Homepage – Intro Section',
    'fields'   => array(
      array('key' => 'field_tc_intro_eyebrow', 'label' => 'Eyebrow',     'name' => 'intro_eyebrow', 'type' => 'text',     'placeholder' => 'e.g. Our Story'),
      array('key' => 'field_tc_intro_title',   'label' => 'Title',       'name' => 'intro_title',   'type' => 'text',     'placeholder' => 'e.g. A Taste of Authentic Greece'),
      array('key' => 'field_tc_intro_text',    'label' => 'Description', 'name' => 'intro_text',    'type' => 'textarea', 'rows' => 4),
      array('key' => 'field_tc_intro_btn',     'label' => 'Button Text', 'name' => 'intro_btn_text','type' => 'text',     'placeholder' => 'e.g. Learn More'),
      array('key' => 'field_tc_intro_btn_link','label' => 'Button Link', 'name' => 'intro_btn_link','type' => 'url'),
    ),
    'location' => array(
      array(array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page')),
    ),
    'menu_order' => 1,
  ));

  // ---- FEATURE CARDS (individual, up to 8 — one per menu section) ----
  $card_fields = array(
    array('key' => 'field_tc_features_eyebrow', 'label' => 'Eyebrow', 'name' => 'features_eyebrow', 'type' => 'text', 'placeholder' => 'e.g. Discover'),
    array('key' => 'field_tc_features_title',   'label' => 'Title',   'name' => 'features_title',   'type' => 'text', 'placeholder' => 'e.g. The Touris Club Experience'),
  );
  for ($i = 1; $i <= 8; $i++) {
    $card_fields[] = array('key' => "field_tc_card_{$i}_tab",   'label' => "Card {$i}",     'name' => '',                    'type' => 'tab');
    $card_fields[] = array('key' => "field_tc_card_{$i}_image", 'label' => 'Image',          'name' => "card_{$i}_image",     'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium');
    $card_fields[] = array('key' => "field_tc_card_{$i}_title", 'label' => 'Title',          'name' => "card_{$i}_title",     'type' => 'text');
    $card_fields[] = array('key' => "field_tc_card_{$i}_desc",  'label' => 'Description',    'name' => "card_{$i}_desc",      'type' => 'textarea', 'rows' => 2);
    $card_fields[] = array('key' => "field_tc_card_{$i}_link",  'label' => 'Link',           'name' => "card_{$i}_link",      'type' => 'url');
  }

  acf_add_local_field_group(array(
    'key'      => 'group_tc_features',
    'title'    => '🏠 Homepage – Feature Cards',
    'fields'   => $card_fields,
    'location' => array(
      array(array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page')),
    ),
    'menu_order' => 2,
  ));

  // ---- GALLERY CAROUSEL (individual images, up to 12) ----
  $gallery_fields = array(
    array('key' => 'field_tc_gallery_eyebrow', 'label' => 'Eyebrow', 'name' => 'gallery_eyebrow', 'type' => 'text', 'placeholder' => 'e.g. Our Spaces'),
    array('key' => 'field_tc_gallery_title',   'label' => 'Title',   'name' => 'gallery_title',   'type' => 'text', 'placeholder' => 'e.g. A Venue Like No Other'),
  );
  for ($i = 1; $i <= 12; $i++) {
    $gallery_fields[] = array('key' => "field_tc_gimg_{$i}", 'label' => "Image {$i}", 'name' => "gallery_image_{$i}", 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium');
  }

  acf_add_local_field_group(array(
    'key'      => 'group_tc_gallery',
    'title'    => '🏠 Homepage – Gallery',
    'fields'   => $gallery_fields,
    'location' => array(
      array(array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page')),
    ),
    'menu_order' => 3,
  ));

  // ---- CTA BANNER ----
  acf_add_local_field_group(array(
    'key'      => 'group_tc_cta',
    'title'    => '🏠 Homepage – CTA Banner',
    'fields'   => array(
      array('key' => 'field_tc_cta_bg',       'label' => 'Background Image', 'name' => 'cta_bg_image',  'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
      array('key' => 'field_tc_cta_eyebrow',  'label' => 'Eyebrow',          'name' => 'cta_eyebrow',   'type' => 'text',  'placeholder' => 'e.g. Plan Your Event'),
      array('key' => 'field_tc_cta_title',    'label' => 'Title',            'name' => 'cta_title',     'type' => 'text',  'placeholder' => 'e.g. Host Your Dream Wedding'),
      array('key' => 'field_tc_cta_text',     'label' => 'Description',      'name' => 'cta_text',      'type' => 'textarea', 'rows' => 2),
      array('key' => 'field_tc_cta_btn',      'label' => 'Button Text',      'name' => 'cta_btn_text',  'type' => 'text',  'placeholder' => 'e.g. Contact Us'),
      array('key' => 'field_tc_cta_btn_link', 'label' => 'Button Link',      'name' => 'cta_btn_link',  'type' => 'url'),
    ),
    'location' => array(
      array(array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page')),
    ),
    'menu_order' => 4,
  ));

  // ---- FOOTER INFO ----
  $footer_fields = array(
    array('key' => 'field_tc_footer_about',    'label' => 'About Text',     'name' => 'footer_about',    'type' => 'textarea', 'rows' => 3),
    array('key' => 'field_tc_footer_phone',    'label' => 'Phone',          'name' => 'footer_phone',    'type' => 'text', 'default_value' => '+30 2624 02 31 03'),
    array('key' => 'field_tc_footer_email',    'label' => 'Email',          'name' => 'footer_email',    'type' => 'email', 'default_value' => 'thanostouris@gmail.com'),
    array('key' => 'field_tc_footer_address',  'label' => 'Address',        'name' => 'footer_address',  'type' => 'text', 'default_value' => 'Ancient Olympia, 27065, Greece'),
    array('key' => 'field_tc_footer_facebook', 'label' => 'Facebook URL',   'name' => 'footer_facebook', 'type' => 'url'),
    array('key' => 'field_tc_footer_youtube',  'label' => 'YouTube URL',    'name' => 'footer_youtube',  'type' => 'url'),
    array('key' => 'field_tc_footer_instagram','label' => 'Instagram URL',  'name' => 'footer_instagram','type' => 'url'),
    // Explore links
    array('key' => 'field_tc_footer_explore_tab', 'label' => 'Explore Links', 'name' => '', 'type' => 'tab'),
  );
  for ($i = 1; $i <= 6; $i++) {
    $footer_fields[] = array('key' => "field_tc_fexplore_{$i}_label", 'label' => "Explore Link {$i} Label", 'name' => "footer_explore_{$i}_label", 'type' => 'text', 'placeholder' => 'e.g. Restaurant');
    $footer_fields[] = array('key' => "field_tc_fexplore_{$i}_url",   'label' => "Explore Link {$i} URL",   'name' => "footer_explore_{$i}_url",   'type' => 'url');
  }
  // Activities links
  $footer_fields[] = array('key' => 'field_tc_footer_activities_tab', 'label' => 'Activities Links', 'name' => '', 'type' => 'tab');
  for ($i = 1; $i <= 6; $i++) {
    $footer_fields[] = array('key' => "field_tc_factivity_{$i}_label", 'label' => "Activity Link {$i} Label", 'name' => "footer_activity_{$i}_label", 'type' => 'text', 'placeholder' => 'e.g. Cooking Classes');
    $footer_fields[] = array('key' => "field_tc_factivity_{$i}_url",   'label' => "Activity Link {$i} URL",   'name' => "footer_activity_{$i}_url",   'type' => 'url');
  }

  acf_add_local_field_group(array(
    'key'      => 'group_tc_footer',
    'title'    => '🏠 Homepage – Footer Info',
    'fields'   => $footer_fields,
    'location' => array(
      array(array('param' => 'page_type', 'operator' => '==', 'value' => 'front_page')),
    ),
    'menu_order' => 5,
  ));

  // =============================================
  // INNER PAGE FIELDS (all pages except front page)
  // =============================================

  // ---- HERO BANNER ----
  acf_add_local_field_group(array(
    'key'      => 'group_tc_page_hero',
    'title'    => '📄 Page – Hero Banner',
    'fields'   => array(
      array('key' => 'field_tc_page_hero_image',    'label' => 'Hero Background Image', 'name' => 'page_hero_image',    'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => 'Recommended: 1920×800px or wider'),
      array('key' => 'field_tc_page_hero_subtitle',  'label' => 'Subtitle (italic)',     'name' => 'page_hero_subtitle',  'type' => 'text', 'placeholder' => 'e.g. A journey through taste'),
    ),
    'location' => array(
      array(
        array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
        array('param' => 'page_type', 'operator' => '!=', 'value' => 'front_page'),
      ),
    ),
    'menu_order' => 0,
  ));

  // ---- INTRO BLOCK ----
  acf_add_local_field_group(array(
    'key'      => 'group_tc_page_intro',
    'title'    => '📄 Page – Intro Block',
    'fields'   => array(
      array('key' => 'field_tc_page_intro_eyebrow', 'label' => 'Eyebrow',     'name' => 'page_intro_eyebrow', 'type' => 'text'),
      array('key' => 'field_tc_page_intro_title',   'label' => 'Title',       'name' => 'page_intro_title',   'type' => 'text'),
      array('key' => 'field_tc_page_intro_text',    'label' => 'Description', 'name' => 'page_intro_text',    'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0),
    ),
    'location' => array(
      array(
        array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
        array('param' => 'page_type', 'operator' => '!=', 'value' => 'front_page'),
      ),
    ),
    'menu_order' => 1,
  ));

  // ---- CONTENT BLOCKS (up to 6 alternating image/text) ----
  $block_fields = array();
  for ($i = 1; $i <= 6; $i++) {
    $block_fields[] = array('key' => "field_tc_block_{$i}_tab",    'label' => "Block {$i}",     'name' => '',                       'type' => 'tab');
    $block_fields[] = array('key' => "field_tc_block_{$i}_image",  'label' => 'Image',          'name' => "block_{$i}_image",       'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium');
    $block_fields[] = array('key' => "field_tc_block_{$i}_title",  'label' => 'Title',          'name' => "block_{$i}_title",       'type' => 'text');
    $block_fields[] = array('key' => "field_tc_block_{$i}_text",   'label' => 'Text',           'name' => "block_{$i}_text",        'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0);
    $block_fields[] = array('key' => "field_tc_block_{$i}_btn",    'label' => 'Button Text',    'name' => "block_{$i}_btn_text",    'type' => 'text', 'placeholder' => 'Optional');
    $block_fields[] = array('key' => "field_tc_block_{$i}_link",   'label' => 'Button Link',    'name' => "block_{$i}_btn_link",    'type' => 'url');
  }

  acf_add_local_field_group(array(
    'key'      => 'group_tc_page_blocks',
    'title'    => '📄 Page – Content Blocks',
    'fields'   => $block_fields,
    'location' => array(
      array(
        array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
        array('param' => 'page_type', 'operator' => '!=', 'value' => 'front_page'),
      ),
    ),
    'menu_order' => 2,
  ));

  // ---- PAGE GALLERY (up to 12 images) ----
  $pgallery_fields = array(
    array('key' => 'field_tc_page_gallery_title', 'label' => 'Gallery Title', 'name' => 'page_gallery_title', 'type' => 'text', 'placeholder' => 'e.g. Gallery'),
  );
  for ($i = 1; $i <= 12; $i++) {
    $pgallery_fields[] = array('key' => "field_tc_pgimg_{$i}", 'label' => "Image {$i}", 'name' => "page_gallery_image_{$i}", 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium');
  }

  acf_add_local_field_group(array(
    'key'      => 'group_tc_page_gallery',
    'title'    => '📄 Page – Gallery',
    'fields'   => $pgallery_fields,
    'location' => array(
      array(
        array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
        array('param' => 'page_type', 'operator' => '!=', 'value' => 'front_page'),
      ),
    ),
    'menu_order' => 3,
  ));

  // ---- PAGE CTA ----
  acf_add_local_field_group(array(
    'key'      => 'group_tc_page_cta',
    'title'    => '📄 Page – CTA Banner',
    'fields'   => array(
      array('key' => 'field_tc_page_cta_bg',       'label' => 'Background Image', 'name' => 'page_cta_bg',       'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'),
      array('key' => 'field_tc_page_cta_title',    'label' => 'Title',            'name' => 'page_cta_title',    'type' => 'text', 'placeholder' => 'e.g. Ready to visit?'),
      array('key' => 'field_tc_page_cta_text',     'label' => 'Description',      'name' => 'page_cta_text',     'type' => 'textarea', 'rows' => 2),
      array('key' => 'field_tc_page_cta_btn',      'label' => 'Button Text',      'name' => 'page_cta_btn_text', 'type' => 'text', 'placeholder' => 'e.g. Contact Us'),
      array('key' => 'field_tc_page_cta_link',     'label' => 'Button Link',      'name' => 'page_cta_btn_link', 'type' => 'url'),
    ),
    'location' => array(
      array(
        array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
        array('param' => 'page_type', 'operator' => '!=', 'value' => 'front_page'),
      ),
    ),
    'menu_order' => 4,
  ));

  // =============================================
  // CONTACT PAGE FIELDS
  // =============================================
  acf_add_local_field_group(array(
    'key'      => 'group_tc_contact',
    'title'    => '📞 Contact Page',
    'fields'   => array(
      array('key' => 'field_tc_contact_heading',   'label' => 'Heading',           'name' => 'contact_heading',        'type' => 'text', 'default_value' => 'Get In Touch'),
      array('key' => 'field_tc_contact_text',      'label' => 'Intro Text',        'name' => 'contact_text',           'type' => 'textarea', 'rows' => 3),
      array('key' => 'field_tc_contact_address',   'label' => 'Address',           'name' => 'contact_address',        'type' => 'text', 'default_value' => 'Ancient Olympia, 27065, Greece'),
      array('key' => 'field_tc_contact_phone',     'label' => 'Phone',             'name' => 'contact_phone',          'type' => 'text', 'default_value' => '+30 2624 02 31 03'),
      array('key' => 'field_tc_contact_email',     'label' => 'Email',             'name' => 'contact_email',          'type' => 'email', 'default_value' => 'thanostouris@gmail.com'),
      array('key' => 'field_tc_contact_hours',     'label' => 'Opening Hours',     'name' => 'contact_hours',          'type' => 'text', 'placeholder' => 'e.g. Mon–Sun: 10:00–23:00'),
      array('key' => 'field_tc_contact_facebook',  'label' => 'Facebook URL',      'name' => 'contact_facebook',       'type' => 'url'),
      array('key' => 'field_tc_contact_youtube',   'label' => 'YouTube URL',       'name' => 'contact_youtube',        'type' => 'url'),
      array('key' => 'field_tc_contact_instagram', 'label' => 'Instagram URL',     'name' => 'contact_instagram',      'type' => 'url'),
      array('key' => 'field_tc_contact_form',      'label' => 'Contact Form Shortcode', 'name' => 'contact_form_shortcode', 'type' => 'text', 'placeholder' => '[contact-form-7 id="123" title="Contact"]', 'instructions' => 'Paste your Contact Form 7 shortcode here'),
      array('key' => 'field_tc_contact_map',       'label' => 'Google Maps Embed', 'name' => 'contact_map_embed',      'type' => 'textarea', 'rows' => 3, 'instructions' => 'Paste the full Google Maps iframe embed code'),
    ),
    'location' => array(
      array(array('param' => 'page_template', 'operator' => '==', 'value' => 'page-contact.php')),
    ),
    'menu_order' => 0,
  ));

  // =============================================
  // PRODUCT CPT FIELDS
  // =============================================
  acf_add_local_field_group(array(
    'key'      => 'group_tc_product',
    'title'    => '🛍 Product Details',
    'fields'   => array(
      array('key' => 'field_tc_prod_desc',       'label' => 'Short Description',  'name' => 'product_short_desc',  'type' => 'textarea', 'rows' => 2, 'placeholder' => 'Brief description for the grid card'),
      array('key' => 'field_tc_prod_price',      'label' => 'Price',              'name' => 'product_price',       'type' => 'text', 'placeholder' => 'e.g. €12.00'),
      array('key' => 'field_tc_prod_full_desc',  'label' => 'Full Description',   'name' => 'product_full_desc',   'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0, 'instructions' => 'Detailed description shown on the single product page'),
      array('key' => 'field_tc_prod_gallery_tab','label' => 'Gallery',            'name' => '',                    'type' => 'tab'),
    ),
    'location' => array(
      array(array('param' => 'post_type', 'operator' => '==', 'value' => 'product')),
    ),
    'menu_order' => 0,
  ));

  // Product gallery (up to 6 images)
  $prod_gallery = array();
  for ($i = 1; $i <= 6; $i++) {
    $prod_gallery[] = array('key' => "field_tc_prodimg_{$i}", 'label' => "Image {$i}", 'name' => "product_gallery_{$i}", 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium');
  }
  acf_add_local_field_group(array(
    'key'      => 'group_tc_product_gallery',
    'title'    => '🛍 Product Gallery',
    'fields'   => $prod_gallery,
    'location' => array(
      array(array('param' => 'post_type', 'operator' => '==', 'value' => 'product')),
    ),
    'menu_order' => 1,
  ));
}