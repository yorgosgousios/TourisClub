<?php get_header(); ?>

<div class="page-hero">
  <div class="page-hero__bg" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(null, 'full')); ?>')"></div>
  <div class="page-hero__overlay"></div>
  <div class="page-hero__content">
    <h1 class="page-hero__title"><?php the_title(); ?></h1>
  </div>
</div>

<div class="page-content">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <?php the_content(); ?>
  <?php endwhile; else: ?>
    <p>No content found.</p>
  <?php endif; ?>
</div>

<?php get_footer(); ?>
