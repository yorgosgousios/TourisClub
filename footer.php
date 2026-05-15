<?php
  $front_id = get_option('page_on_front');
  $tc = function($field) use ($front_id) {
    return function_exists('get_field') ? get_field($field, $front_id) : '';
  };
?>

<footer class="site-footer">
  <div class="footer-grid">
    <div>
      <div class="footer-grid__logo">
        <a href="<?php echo esc_url(home_url('/')); ?>">
          <img src="<?php echo esc_url(get_theme_file_uri('/assets/logo-final.png')); ?>" alt="Touris Club" />
        </a>
      </div>
      <p class="footer-grid__about"><?php echo esc_html($tc('footer_about') ?: 'A unique estate near Ancient Olympia offering authentic Greek cuisine, event hosting, cultural activities and a taste of the Peloponnese.'); ?></p>
      <div class="footer-social">
        <?php if ($fb = $tc('footer_facebook')): ?>
          <a href="<?php echo esc_url($fb); ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
        <?php endif; ?>
        <?php if ($yt = $tc('footer_youtube')): ?>
          <a href="<?php echo esc_url($yt); ?>" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
        <?php endif; ?>
        <?php if ($ig = $tc('footer_instagram')): ?>
          <a href="<?php echo esc_url($ig); ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        <?php endif; ?>
      </div>
    </div>

    <div>
      <div class="footer-grid__title">Explore</div>
      <ul>
        <?php for ($i = 1; $i <= 6; $i++):
          $label = $tc("footer_explore_{$i}_label");
          $url = $tc("footer_explore_{$i}_url");
          if (!$label) continue;
        ?>
          <li><a href="<?php echo esc_url($url ?: '#'); ?>"><?php echo esc_html($label); ?></a></li>
        <?php endfor; ?>
      </ul>
    </div>

    <div>
      <div class="footer-grid__title">Activities</div>
      <ul>
        <?php for ($i = 1; $i <= 6; $i++):
          $label = $tc("footer_activity_{$i}_label");
          $url = $tc("footer_activity_{$i}_url");
          if (!$label) continue;
        ?>
          <li><a href="<?php echo esc_url($url ?: '#'); ?>"><?php echo esc_html($label); ?></a></li>
        <?php endfor; ?>
      </ul>
    </div>

    <div class="footer-grid__contact">
      <div class="footer-grid__title">Contact</div>
      <?php if ($addr = $tc('footer_address')): ?>
        <p><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($addr); ?></p>
      <?php endif; ?>
      <?php if ($phone = $tc('footer_phone')): ?>
        <p><i class="fas fa-phone"></i> <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></p>
      <?php endif; ?>
      <?php if ($email = $tc('footer_email')): ?>
        <p><i class="fas fa-envelope"></i> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
      <?php endif; ?>
    </div>
  </div>

  <div class="footer-bottom">
    &copy; <?php echo date('Y'); ?> Touris Club. All rights reserved.
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>