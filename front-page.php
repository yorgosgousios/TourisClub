<?php get_header(); ?>

<?php
// Get front page ID for ACF
if (have_posts()) { the_post(); }
$pid = get_the_ID();

$tc = function($field) use ($pid) {
  return function_exists('get_field') ? get_field($field, $pid) : '';
};
?>

<!-- ========================
     HERO CAROUSEL
     ======================== -->
<?php
// Build slides array from individual fields
$slides = array();
for ($i = 1; $i <= 6; $i++) {
  $type  = $tc("slide_{$i}_type") ?: 'image';
  $image = function_exists('get_field') ? get_field("slide_{$i}_image", $pid) : false;
  $video = function_exists('get_field') ? get_field("slide_{$i}_video", $pid) : false;
  $title = $tc("slide_{$i}_title");

  // Skip empty slides (no image AND no video AND no title)
  if (!$image && !$video && !$title) continue;

  $slides[] = array(
    'type'     => $type,
    'image'    => $image ? $image['url'] : '',
    'video'    => $video ? $video['url'] : '',
    'poster'   => function_exists('get_field') ? get_field("slide_{$i}_poster", $pid) : false,
    'eyebrow'  => $tc("slide_{$i}_eyebrow"),
    'title'    => $title,
    'subtitle' => $tc("slide_{$i}_subtitle"),
    'btn_text' => $tc("slide_{$i}_btn_text"),
    'btn_link' => $tc("slide_{$i}_btn_link"),
  );
}
?>
<?php if ($slides): ?>
<section class="hero-carousel">
  <div class="glide" id="hero-glide">
    <div class="glide__track" data-glide-el="track">
      <ul class="glide__slides">
        <?php foreach ($slides as $slide):
          $poster_url = isset($slide['poster']['url']) ? $slide['poster']['url'] : $slide['image'];
        ?>
        <li class="glide__slide" data-slide-type="<?php echo esc_attr($slide['type']); ?>">
          <?php if ($slide['type'] === 'video' && $slide['video']): ?>
            <video class="hero-carousel__video" autoplay muted loop playsinline poster="<?php echo esc_url($poster_url); ?>">
              <source src="<?php echo esc_url($slide['video']); ?>" type="video/mp4">
            </video>
          <?php else: ?>
            <div class="hero-carousel__image" style="background-image: url('<?php echo esc_url($slide['image']); ?>')"></div>
          <?php endif; ?>
          <div class="hero-carousel__overlay"></div>
          <div class="hero-carousel__content">
            <?php if (!empty($slide['eyebrow'])): ?>
              <div class="hero-carousel__eyebrow"><?php echo esc_html($slide['eyebrow']); ?></div>
            <?php endif; ?>
            <h1 class="hero-carousel__title"><?php echo esc_html($slide['title']); ?></h1>
            <?php if (!empty($slide['subtitle'])): ?>
              <p class="hero-carousel__subtitle"><?php echo esc_html($slide['subtitle']); ?></p>
            <?php endif; ?>
            <?php if (!empty($slide['btn_text'])): ?>
              <a href="<?php echo esc_url($slide['btn_link'] ?: '#'); ?>" class="btn btn--outline-white"><?php echo esc_html($slide['btn_text']); ?></a>
            <?php endif; ?>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="glide__bullets" data-glide-el="controls[nav]">
      <?php foreach ($slides as $i => $s): ?>
        <button class="glide__bullet" data-glide-dir="=<?php echo $i; ?>"></button>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php else: ?>
<!-- Fallback hero if no slides -->
<section class="hero-carousel" style="height: 100vh; background: var(--dark); display: flex; align-items: center; justify-content: center;">
  <div style="text-align: center; color: var(--white); padding: 48px;">
    <h1 class="hero-carousel__title">Welcome to Touris Club</h1>
    <p class="hero-carousel__subtitle">Add slides in the WordPress admin to customize this section.</p>
  </div>
</section>
<?php endif; ?>


<!-- ========================
     INTRO SECTION
     ======================== -->
<?php if ($tc('intro_title')): ?>
<section class="section-intro">
  <?php if ($tc('intro_eyebrow')): ?>
    <div class="section-intro__eyebrow"><?php echo esc_html($tc('intro_eyebrow')); ?></div>
  <?php endif; ?>
  <h2 class="section-intro__title"><?php echo esc_html($tc('intro_title')); ?></h2>
  <div class="section-intro__divider"></div>
  <?php if ($tc('intro_text')): ?>
    <p class="section-intro__text"><?php echo esc_html($tc('intro_text')); ?></p>
  <?php endif; ?>
  <?php if ($tc('intro_btn_text')): ?>
    <a href="<?php echo esc_url($tc('intro_btn_link') ?: '#'); ?>" class="btn btn--olive"><?php echo esc_html($tc('intro_btn_text')); ?></a>
  <?php endif; ?>
</section>
<?php endif; ?>


<!-- ========================
     FEATURE CARDS
     ======================== -->
<?php
// Build cards array from individual fields
$cards = array();
for ($i = 1; $i <= 8; $i++) {
  $card_image = function_exists('get_field') ? get_field("card_{$i}_image", $pid) : false;
  $card_title = $tc("card_{$i}_title");
  if (!$card_image && !$card_title) continue;

  $cards[] = array(
    'image' => $card_image ? $card_image['url'] : '',
    'title' => $card_title,
    'desc'  => $tc("card_{$i}_desc"),
    'link'  => $tc("card_{$i}_link"),
  );
}
?>
<?php if ($cards): ?>
<section class="section-features">
  <div class="section-features__header">
    <?php if ($tc('features_eyebrow')): ?>
      <div class="section-features__eyebrow"><?php echo esc_html($tc('features_eyebrow')); ?></div>
    <?php endif; ?>
    <?php if ($tc('features_title')): ?>
      <h2 class="section-features__title"><?php echo esc_html($tc('features_title')); ?></h2>
    <?php endif; ?>
  </div>

  <div class="features-carousel">
    <div class="glide" id="features-glide">
      <div class="glide__track" data-glide-el="track">
        <ul class="glide__slides">
          <?php foreach ($cards as $card): ?>
          <li class="glide__slide">
            <a href="<?php echo esc_url($card['link'] ?: '#'); ?>" class="feature-card">
              <div class="feature-card__image" style="background-image: url('<?php echo esc_url($card['image']); ?>')"></div>
              <div class="feature-card__overlay"></div>
              <div class="feature-card__content">
                <h3 class="feature-card__title"><?php echo esc_html($card['title']); ?></h3>
                <?php if (!empty($card['desc'])): ?>
                  <p class="feature-card__desc"><?php echo esc_html($card['desc']); ?></p>
                <?php endif; ?>
                <span class="feature-card__link">Discover</span>
              </div>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="features-carousel__nav">
        <button class="features-carousel__btn" id="features-prev"><i class="fas fa-arrow-left"></i></button>
        <button class="features-carousel__btn" id="features-next"><i class="fas fa-arrow-right"></i></button>
      </div>
    </div>
  </div>
  </div>
</section>
<?php endif; ?>

<!-- ========================
     CTA BANNER
     ======================== -->
<?php if ($tc('cta_title')): ?>
<section class="section-cta">
  <?php
    $cta_bg = function_exists('get_field') ? get_field('cta_bg_image', $pid) : false;
    $cta_bg_url = $cta_bg ? $cta_bg['url'] : '';
  ?>
  <div class="section-cta__bg" style="background-image: url('<?php echo esc_url($cta_bg_url); ?>')"></div>
  <div class="section-cta__overlay"></div>
  <div class="section-cta__content">
    <?php if ($tc('cta_eyebrow')): ?>
      <div class="section-cta__eyebrow"><?php echo esc_html($tc('cta_eyebrow')); ?></div>
    <?php endif; ?>
    <h2 class="section-cta__title"><?php echo esc_html($tc('cta_title')); ?></h2>
    <?php if ($tc('cta_text')): ?>
      <p class="section-cta__text"><?php echo esc_html($tc('cta_text')); ?></p>
    <?php endif; ?>
    <?php if ($tc('cta_btn_text')): ?>
      <a href="<?php echo esc_url($tc('cta_btn_link') ?: '#'); ?>" class="btn btn--outline-white"><?php echo esc_html($tc('cta_btn_text')); ?></a>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>


<!-- ========================
     GALLERY CAROUSEL
     ======================== -->
<?php
// Build gallery array from individual image fields
$gallery = array();
for ($i = 1; $i <= 12; $i++) {
  $gimg = function_exists('get_field') ? get_field("gallery_image_{$i}", $pid) : false;
  if ($gimg) {
    $gallery[] = $gimg;
  }
}
?>
<?php if ($gallery): ?>
<section class="section-gallery">
  <div class="section-gallery__header">
    <?php if ($tc('gallery_eyebrow')): ?>
      <div class="section-gallery__eyebrow"><?php echo esc_html($tc('gallery_eyebrow')); ?></div>
    <?php endif; ?>
    <?php if ($tc('gallery_title')): ?>
      <h2 class="section-gallery__title"><?php echo esc_html($tc('gallery_title')); ?></h2>
    <?php endif; ?>
  </div>

  <div class="gallery-carousel">
    <div class="glide" id="gallery-glide">
      <div class="glide__track" data-glide-el="track">
        <ul class="glide__slides">
          <?php foreach ($gallery as $gimg): ?>
          <li class="glide__slide">
            <img src="<?php echo esc_url($gimg['url']); ?>" alt="<?php echo esc_attr($gimg['alt'] ?: 'Touris Club'); ?>" loading="lazy">
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <div class="gallery-carousel__nav">
      <button class="gallery-carousel__btn" id="gallery-prev"><i class="fas fa-chevron-left"></i></button>
      <button class="gallery-carousel__btn" id="gallery-next"><i class="fas fa-chevron-right"></i></button>
    </div>
  </div>
</section>
<?php endif; ?>





<!-- ========================
     MAP
     ======================== -->
<section class="section-map">
  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3159.0741657335598!2d21.620670875233614!3d37.64746037201772!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x136092fc6a4d8f4b%3A0x34024ca70f2c0748!2sTouris%20Club!5e0!3m2!1sel!2sgr!4v1775748836022!5m2!1sel!2sgr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>

<?php get_footer(); ?>