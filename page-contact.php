<?php
// page-contact.php
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

<!-- Contact Section -->
<section class="contact-section">
  <div class="contact-section__inner">

    <!-- Left: Info -->
    <div class="contact-info">
      <?php if ($pg('contact_heading')): ?>
        <h2 class="contact-info__title"><?php echo esc_html($pg('contact_heading')); ?></h2>
      <?php endif; ?>
      <?php if ($pg('contact_text')): ?>
        <p class="contact-info__text"><?php echo esc_html($pg('contact_text')); ?></p>
      <?php endif; ?>

      <div class="contact-info__details">
        <?php if ($pg('contact_address')): ?>
        <div class="contact-info__row">
          <i class="fas fa-map-marker-alt"></i>
          <span><?php echo esc_html($pg('contact_address')); ?></span>
        </div>
        <?php endif; ?>

        <?php if ($pg('contact_phone')): ?>
        <div class="contact-info__row">
          <i class="fas fa-phone"></i>
          <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $pg('contact_phone'))); ?>"><?php echo esc_html($pg('contact_phone')); ?></a>
        </div>
        <?php endif; ?>

        <?php if ($pg('contact_email')): ?>
        <div class="contact-info__row">
          <i class="fas fa-envelope"></i>
          <a href="mailto:<?php echo esc_attr($pg('contact_email')); ?>"><?php echo esc_html($pg('contact_email')); ?></a>
        </div>
        <?php endif; ?>

        <?php if ($pg('contact_hours')): ?>
        <div class="contact-info__row">
          <i class="fas fa-clock"></i>
          <span><?php echo esc_html($pg('contact_hours')); ?></span>
        </div>
        <?php endif; ?>
      </div>

      <?php
        $fb = $pg('contact_facebook');
        $yt = $pg('contact_youtube');
        $ig = $pg('contact_instagram');
        if ($fb || $yt || $ig):
      ?>
      <div class="contact-info__social">
        <?php if ($fb): ?><a href="<?php echo esc_url($fb); ?>" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
        <?php if ($ig): ?><a href="<?php echo esc_url($ig); ?>" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a><?php endif; ?>
        <?php if ($yt): ?><a href="<?php echo esc_url($yt); ?>" target="_blank" rel="noopener"><i class="fab fa-youtube"></i></a><?php endif; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Right: Form -->
    <div class="contact-form">
      <?php if ($pg('contact_form_shortcode')): ?>
        <?php echo do_shortcode($pg('contact_form_shortcode')); ?>
      <?php else: ?>
        <p>Install Contact Form 7 and paste the shortcode in the Contact Form field.</p>
      <?php endif; ?>
    </div>

  </div>
</section>

<!-- Map -->
<?php if ($pg('contact_map_embed')): ?>
<section class="section-map">
  <?php echo $pg('contact_map_embed'); ?>
</section>
<?php else: ?>
<section class="section-map">
  <iframe
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3145.123!2d21.6297!3d37.6380!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzfCsDM4JzE3LjAiTiAyMcKwMzcnNDguMCJF!5e0!3m2!1sen!2sgr!4v1"
    allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Touris Club Location"></iframe>
</section>
<?php endif; ?>

<?php get_footer(); ?>