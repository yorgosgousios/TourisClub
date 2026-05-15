<?php get_header(); ?>

<?php
if (have_posts()) { the_post(); }
$pid = get_the_ID();

$pg = function($field) use ($pid) {
  return function_exists('get_field') ? get_field($field, $pid) : '';
};
?>

<!-- ========================
     HERO BANNER
     ======================== -->
<?php
  $hero_img = function_exists('get_field') ? get_field('page_hero_image', $pid) : false;
  $hero_url = $hero_img ? $hero_img['url'] : get_the_post_thumbnail_url(null, 'full');
?>
<div class="page-hero">
  <?php if ($hero_url): ?>
    <div class="page-hero__bg" style="background-image: url('<?php echo esc_url($hero_url); ?>')"></div>
  <?php else: ?>
    <div class="page-hero__bg" style="background: var(--dark);"></div>
  <?php endif; ?>
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content">
    <h1 class="page-hero__title"><?php the_title(); ?></h1>
    <?php if ($pg('page_hero_subtitle')): ?>
      <p class="page-hero__subtitle"><?php echo esc_html($pg('page_hero_subtitle')); ?></p>
    <?php endif; ?>
  </div>
</div>


<!-- ========================
     INTRO BLOCK
     ======================== -->
<?php if ($pg('page_intro_title') || $pg('page_intro_text')): ?>
<section class="page-intro">
  <div class="page-intro__inner">
    <?php if ($pg('page_intro_eyebrow')): ?>
      <div class="page-intro__eyebrow"><?php echo esc_html($pg('page_intro_eyebrow')); ?></div>
    <?php endif; ?>
    <?php if ($pg('page_intro_title')): ?>
      <h2 class="page-intro__title"><?php echo esc_html($pg('page_intro_title')); ?></h2>
    <?php endif; ?>
    <div class="page-intro__divider"></div>
    <?php if ($pg('page_intro_text')): ?>
      <div class="page-intro__text"><?php echo wp_kses_post($pg('page_intro_text')); ?></div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>


<!-- ========================
     CONTENT BLOCKS
     ======================== -->
<?php
$blocks = array();
for ($i = 1; $i <= 6; $i++) {
  $block_image = function_exists('get_field') ? get_field("block_{$i}_image", $pid) : false;
  $block_title = $pg("block_{$i}_title");
  $block_text  = $pg("block_{$i}_text");
  if (!$block_image && !$block_title && !$block_text) continue;

  $blocks[] = array(
    'image'    => $block_image ? $block_image['url'] : '',
    'title'    => $block_title,
    'text'     => $block_text,
    'btn_text' => $pg("block_{$i}_btn_text"),
    'btn_link' => $pg("block_{$i}_btn_link"),
  );
}
?>
<?php if ($blocks): ?>
<?php foreach ($blocks as $idx => $block):
  $anchor = $block['title'] ? sanitize_title($block['title']) : 'block-' . ($idx + 1);
?>
<section id="<?php echo esc_attr($anchor); ?>" class="content-block <?php echo ($idx % 2 !== 0) ? 'content-block--reversed' : ''; ?>">
  <div class="content-block__inner">
    <?php if ($block['image']): ?>
    <div class="content-block__media">
      <img src="<?php echo esc_url($block['image']); ?>" alt="<?php echo esc_attr($block['title'] ?: 'Touris Club'); ?>" loading="lazy">
    </div>
    <?php endif; ?>
    <div class="content-block__text">
      <?php if ($block['title']): ?>
        <h3 class="content-block__title"><?php echo esc_html($block['title']); ?></h3>
      <?php endif; ?>
      <?php if ($block['text']): ?>
        <div class="content-block__body"><?php echo wp_kses_post($block['text']); ?></div>
      <?php endif; ?>
      <?php if ($block['btn_text']): ?>
        <a href="<?php echo esc_url($block['btn_link'] ?: '#'); ?>" class="btn btn--olive"><?php echo esc_html($block['btn_text']); ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endforeach; ?>
<?php endif; ?>


<!-- ========================
     WP CONTENT (fallback)
     ======================== -->
<?php
  $wp_content = get_the_content();
  if ($wp_content && trim($wp_content)):
?>
<div class="page-content">
  <?php echo apply_filters('the_content', $wp_content); ?>
</div>
<?php endif; ?>


<!-- ========================
     PAGE GALLERY
     ======================== -->
<?php
$pgallery = array();
for ($i = 1; $i <= 12; $i++) {
  $gimg = function_exists('get_field') ? get_field("page_gallery_image_{$i}", $pid) : false;
  if ($gimg) $pgallery[] = $gimg;
}
?>
<?php if ($pgallery): ?>
<section class="page-gallery">
  <?php if ($pg('page_gallery_title')): ?>
    <h2 class="page-gallery__title"><?php echo esc_html($pg('page_gallery_title')); ?></h2>
  <?php endif; ?>
  <div class="page-gallery__grid">
    <?php foreach ($pgallery as $gimg): ?>
    <div class="page-gallery__item">
      <img src="<?php echo esc_url($gimg['url']); ?>" alt="<?php echo esc_attr($gimg['alt'] ?: 'Touris Club'); ?>" loading="lazy">
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>


<!-- ========================
     PAGE CTA
     ======================== -->
<?php if ($pg('page_cta_title')): ?>
<section class="section-cta">
  <?php
    $pcta_bg = function_exists('get_field') ? get_field('page_cta_bg', $pid) : false;
    $pcta_bg_url = $pcta_bg ? $pcta_bg['url'] : '';
  ?>
  <div class="section-cta__bg" style="background-image: url('<?php echo esc_url($pcta_bg_url); ?>')"></div>
  <div class="section-cta__overlay"></div>
  <div class="section-cta__content">
    <h2 class="section-cta__title"><?php echo esc_html($pg('page_cta_title')); ?></h2>
    <?php if ($pg('page_cta_text')): ?>
      <p class="section-cta__text"><?php echo esc_html($pg('page_cta_text')); ?></p>
    <?php endif; ?>
    <?php if ($pg('page_cta_btn_text')): ?>
      <a href="<?php echo esc_url($pg('page_cta_btn_link') ?: '#'); ?>" class="btn btn--outline-white"><?php echo esc_html($pg('page_cta_btn_text')); ?></a>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>