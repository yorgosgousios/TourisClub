<?php get_header(); ?>

<?php
if (have_posts()) { the_post(); }
$pid = get_the_ID();

$pg = function($field) use ($pid) {
  return function_exists('get_field') ? get_field($field, $pid) : '';
};

$price = $pg('product_price');
$short_desc = $pg('product_short_desc');
$full_desc = $pg('product_full_desc');

// Build gallery
$gallery = array();
$thumb = get_the_post_thumbnail_url(null, 'large');
if ($thumb) $gallery[] = $thumb;
for ($i = 1; $i <= 6; $i++) {
  $img = function_exists('get_field') ? get_field("product_gallery_{$i}", $pid) : false;
  if ($img) $gallery[] = $img['url'];
}
?>

<div class="single-product single-product-page">
  <div class="single-product__inner">

    <!-- Left: Gallery -->
    <div class="single-product__gallery">
      <?php if ($gallery): ?>
        <div class="single-product__main-image">
          <img id="product-main-img" src="<?php echo esc_url($gallery[0]); ?>" alt="<?php the_title_attribute(); ?>">
        </div>
        <?php if (count($gallery) > 1): ?>
        <div class="single-product__thumbs">
          <?php foreach ($gallery as $idx => $img_url): ?>
            <button class="single-product__thumb <?php echo $idx === 0 ? 'active' : ''; ?>" data-src="<?php echo esc_url($img_url); ?>">
              <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?> thumbnail" loading="lazy">
            </button>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <!-- Right: Info -->
    <div class="single-product__info">
      <a href="<?php echo esc_url(get_permalink(get_page_by_path('memorabilia') ?: get_page_by_path('memorabilia'))); ?>" class="single-product__back">
        <i class="fas fa-arrow-left"></i> Back to Shop
      </a>

      <h1 class="single-product__title"><?php the_title(); ?></h1>

      <?php if ($price): ?>
        <div class="single-product__price"><?php echo esc_html($price); ?></div>
      <?php endif; ?>

      <?php if ($full_desc): ?>
        <div class="single-product__desc"><?php echo wp_kses_post($full_desc); ?></div>
      <?php elseif ($short_desc): ?>
        <div class="single-product__desc"><p><?php echo esc_html($short_desc); ?></p></div>
      <?php endif; ?>

      <button class="btn btn--olive single-product__interest-btn" id="open-interest-modal">
        I'm Interested
      </button>
    </div>

  </div>
</div>

<!-- Interest Modal -->
<div class="interest-modal" id="interest-modal">
  <div class="interest-modal__overlay" id="interest-modal-overlay"></div>
  <div class="interest-modal__content">
    <button class="interest-modal__close" id="close-interest-modal">
      <i class="fas fa-times"></i>
    </button>
    <h3 class="interest-modal__title">Interested in this product?</h3>
    <p class="interest-modal__subtitle">Fill in your details and we'll get back to you.</p>

    <form class="interest-modal__form" id="interest-form">
      <input type="hidden" name="product_name" value="<?php the_title_attribute(); ?>">
      <input type="hidden" name="product_url" value="<?php the_permalink(); ?>">

      <div class="interest-modal__field">
        <label for="interest-name">Full Name *</label>
        <input type="text" id="interest-name" name="name" required>
      </div>

      <div class="interest-modal__field">
        <label for="interest-email">Email *</label>
        <input type="email" id="interest-email" name="email" required>
      </div>

      <div class="interest-modal__field">
        <label for="interest-phone">Phone</label>
        <input type="tel" id="interest-phone" name="phone">
      </div>

      <div class="interest-modal__field">
        <label for="interest-message">Message</label>
        <textarea id="interest-message" name="message" rows="3" placeholder="Any questions or special requests..."></textarea>
      </div>

      <button type="submit" class="btn btn--olive interest-modal__submit">Send Inquiry</button>
      <div class="interest-modal__success" id="interest-success" style="display:none;">
        <i class="fas fa-check-circle"></i> Thank you! We'll be in touch soon.
      </div>
    </form>
  </div>
</div>

<?php get_footer(); ?>