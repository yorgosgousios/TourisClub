<?php
// page-shop.php
get_header(); ?>

<?php
if (have_posts()) { the_post(); }
$pid = get_the_ID();

$pg = function($field) use ($pid) {
  return function_exists('get_field') ? get_field($field, $pid) : '';
};
?>

<!-- Hero -->
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

<!-- Intro -->
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

<!-- Product Grid -->
<?php
$products = new WP_Query(array(
  'post_type'      => 'product',
  'posts_per_page' => -1,
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
));
?>
<?php if ($products->have_posts()): ?>
<section class="shop-section">
  <div class="shop-grid">
    <?php while ($products->have_posts()): $products->the_post(); ?>
    <a href="<?php the_permalink(); ?>" class="shop-card">
      <?php if (has_post_thumbnail()): ?>
      <div class="shop-card__image">
        <img src="<?php echo esc_url(get_the_post_thumbnail_url(null, 'medium_large')); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
      </div>
      <?php endif; ?>
      <div class="shop-card__content">
        <h3 class="shop-card__title"><?php the_title(); ?></h3>
        <?php $desc = function_exists('get_field') ? get_field('product_short_desc') : ''; if ($desc): ?>
          <p class="shop-card__desc"><?php echo esc_html($desc); ?></p>
        <?php endif; ?>
        <?php $price = function_exists('get_field') ? get_field('product_price') : ''; if ($price): ?>
          <span class="shop-card__price"><?php echo esc_html($price); ?></span>
        <?php endif; ?>
      </div>
    </a>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>