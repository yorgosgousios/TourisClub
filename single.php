<?php
// Fallback single template
// For products, load single-product.php
if (get_post_type() === 'product') {
  include(get_template_directory() . '/single-product.php');
  return;
}

// Default single post
get_header(); ?>

<div class="page-hero">
  <?php if (has_post_thumbnail()): ?>
    <div class="page-hero__bg" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(null, 'full')); ?>')"></div>
  <?php else: ?>
    <div class="page-hero__bg" style="background: var(--dark);"></div>
  <?php endif; ?>
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content">
    <h1 class="page-hero__title"><?php the_title(); ?></h1>
  </div>
</div>

<div class="page-content">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <?php the_content(); ?>
  <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>